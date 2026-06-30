<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Visite;
use App\Models\Stock;
use App\Models\Intrant;
use App\Models\Parcelle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ManagementController extends Controller
{
    // Affiche la liste des visites de terrain (Fichier: visits-control.blade.php)
    public function visites() {
        $visites = Visite::with(['user', 'parcelle'])
            ->orderBy('date_visite', 'asc')
            ->get();
        
        // Statistiques des visites
        $totalVisites = Visite::count();
        $visitesCeMois = Visite::whereMonth('date_visite', now()->month)
            ->whereYear('date_visite', now()->year)
            ->count();
        
        // Liste des clients approuvés pour le formulaire de planification
        $clients = User::where('role', 'client')
            ->where('is_active', true)
            ->where('status', 'approved')
            ->orderBy('name')
            ->get();

        // Clients avec stocks les plus bas pour Visites Urgentes
        $urgentClients = Stock::with('user')
            ->whereHas('user', function ($query) {
                $query->where('role', 'client')
                    ->where('is_active', true)
                    ->where('status', 'approved');
            })
            ->get()
            ->map(function ($stock) {
                $percentage = $stock->seuil_critique > 0
                    ? ($stock->quantite_actuelle / $stock->seuil_critique) * 100
                    : 0;

                return [
                    'id' => $stock->user->id,
                    'name' => $stock->user->name,
                    'location' => $stock->user->location ?? 'Non spécifié',
                    'intrant' => $stock->nom ?? 'Intrant',
                    'percentage' => round($percentage),
                    'is_critical' => $stock->quantite_actuelle <= $stock->seuil_critique,
                ];
            })
            ->sortBy('percentage')
            ->take(5)
            ->values();

        if ($urgentClients->count() > 0) {
            \App\Models\Notification::notifyManager(
                'visite',
                'Visites urgentes à planifier',
                $urgentClients->count() . ' agriculteur(s) ont des stocks critiques nécessitant une visite urgente.',
                'danger',
                url('/manager/visites')
            );
        }

        $allVisites = Visite::with(['user'])
            ->orderByDesc('date_visite')
            ->limit(50)
            ->get();

        return view('visits-control', compact('visites', 'totalVisites', 'visitesCeMois', 'clients', 'urgentClients', 'allVisites'));
    }

    // Créer une nouvelle visite
    public function storeVisite(Request $request) {
        try {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'date_visite' => 'required|date',
                'action_effectuee' => 'required|string',
                'recommandation' => 'nullable|string',
                'duree' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => 'Validation failed: ' . implode(', ', $validator->errors()->all())], 422);
            }

            $visite = Visite::create([
                'user_id' => $request->user_id,
                'date_visite' => $request->date_visite,
                'action_effectuee' => $request->action_effectuee,
                'recommandation' => $request->recommandation,
                'duree' => $request->duree ?? 60,
                'parcelle_id' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Visite créée avec succès',
                'visite' => $visite,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur: ' . $e->getMessage()], 500);
        }
    }

    // Affiche la gestion des stocks d'intrants (Fichier: stocks.blade.php)
    public function stocks() {
        $stocks = Stock::with('user')->orderBy('nom')->get();
        $criticalStocks = Stock::with('user')
            ->whereColumn('quantite_actuelle', '<=', 'seuil_critique')
            ->orderBy('nom')
            ->get();
        
        // Calculer la valeur totale du stock
        $totalValue = Stock::selectRaw('SUM(quantite_actuelle * cout_unitaire) as total')->first()->total ?? 0;
        
        return view('stocks', compact('stocks', 'criticalStocks', 'totalValue'));
    }

    // NOUVEAU : Affiche les analyses BI (Fichier: analyses-bi.blade.php)
    public function analysesBi()
    {
        $stats = [
            'production_totale' => \App\Models\Recolte::sum('quantite') ?? 0,
            'revenus_totaux' => \App\Models\Recolte::sum('revenu_total') ?? 0,
            'rendement_moyen' => 0,
            'agriculteurs_actifs' => \App\Models\User::where('role', 'client')->where('is_active', true)->count(),
            'parcelles_actives' => \App\Models\Parcelle::count(),
            'alertes_critiques' => \App\Models\Stock::whereColumn('quantite_actuelle', '<=', 'seuil_critique')->count(),
        ];

        $surfaceTotale = \App\Models\Parcelle::sum('surface') ?? 0;
        $stats['rendement_moyen'] = $surfaceTotale > 0 ? ($stats['production_totale'] / $surfaceTotale) : 0;

        // Debug
        \Log::info('BI Stats', $stats);

        $topFarmers = \App\Models\User::where('role', 'client')
            ->where('is_active', true)
            ->with(['recoltes', 'parcelles', 'stocks'])
            ->get()
            ->map(function ($client) {
                $totalRecolte = $client->recoltes->sum('quantite');
                $surface = $client->parcelles->sum('surface');
                $rendement = $surface > 0 ? ($totalRecolte / $surface) : 0;
                $benefice = $client->recoltes->sum('benefice_net');
                $criticalStocks = $client->stocks->where('quantite_actuelle', '<=', 'seuil_critique')->count();
                $score = 0;
                if ($rendement >= 1.0) $score += 40;
                elseif ($rendement >= 0.5) $score += 25;
                else $score += 10;
                if ($benefice >= 500000) $score += 30;
                elseif ($benefice >= 0) $score += 15;
                else $score += 0;
                $score += max(0, (1 - ($criticalStocks / max(1, $client->stocks->count())))) * 30;
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'location' => $client->location ?? 'Non spécifié',
                    'rendement' => round($rendement, 2),
                    'benefice' => round($benefice, 0),
                    'surface' => $surface,
                    'score' => $score,
                    'critical_stocks' => $criticalStocks,
                ];
            })
            ->sortByDesc('score')
            ->take(5)
            ->values();

        $atRiskFarmers = \App\Models\User::where('role', 'client')
            ->where('is_active', true)
            ->with(['stocks', 'recoltes', 'visites'])
            ->get()
            ->filter(function ($client) {
                $criticalStocks = $client->stocks->where('quantite_actuelle', '<=', 'seuil_critique')->count();
                $totalBenefice = $client->recoltes->sum('benefice_net');
                $isLowProfitability = $totalBenefice < 0;
                $lastVisit = $client->visites->sortByDesc('date_visite')->first();
                $isInactive = $lastVisit ? $lastVisit->date_visite->lt(now()->subMonths(2)) : true;
                return $criticalStocks > 0 || $isLowProfitability || $isInactive;
            })
            ->map(function ($client) {
                $risks = [];
                $criticalStocks = $client->stocks->where('quantite_actuelle', '<=', 'seuil_critique')->count();
                if ($criticalStocks > 0) $risks[] = 'stock_critique';
                $totalBenefice = $client->recoltes->sum('benefice_net');
                if ($totalBenefice < 0) $risks[] = 'faible_rentabilite';
                $lastVisit = $client->visites->sortByDesc('date_visite')->first();
                if (!$lastVisit || $lastVisit->date_visite->lt(now()->subMonths(2))) $risks[] = 'faible_activite';
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'location' => $client->location ?? 'Non spécifié',
                    'risks' => $risks,
                    'last_visit' => $lastVisit ? $lastVisit->date_visite->format('d/m/Y') : 'Jamais',
                ];
            })
            ->values();

        $recommendations = [];
        if ($stats['alertes_critiques'] > 0) {
            $recommendations[] = ['type' => 'danger', 'message' => "{$stats['alertes_critiques']} stock(s) critique(s) détecté(s) chez les agriculteurs"];
        }
        if ($atRiskFarmers->count() > 0) {
            $recommendations[] = ['type' => 'danger', 'message' => "{$atRiskFarmers->count()} agriculteur(s) nécessitent une attention particulière"];
        }
        $maizePerformance = \App\Models\Recolte::where('culture', 'Maïs')->avg('quantite') ?? 0;
        $rizPerformance = \App\Models\Recolte::where('culture', 'Riz')->avg('quantite') ?? 0;
        if ($maizePerformance < $rizPerformance) {
            $recommendations[] = ['type' => 'warning', 'message' => "Les rendements du maïs sont en baisse comparés au riz"];
        }

        $productionByMonthQuery = \App\Models\Recolte::selectRaw('MONTH(date_recolte) as mois, SUM(quantite) as total')
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        \Log::info('BI productionByMonth', ['count' => $productionByMonthQuery->count()]);

        $cumulativeTotal = 0;
        $cumulativeHarvests = $productionByMonthQuery->map(function ($row) use (&$cumulativeTotal) {
            $cumulativeTotal += (float) $row->total;
            return (object)[
                'mois' => $row->mois,
                'total' => (float) $row->total,
                'cumul' => $cumulativeTotal,
            ];
        });

        $productionByMonth = $cumulativeHarvests->map(fn($row) => (object)[
            'mois' => $row->mois,
            'total' => $row->total,
        ]);

        $revenusByMonth = \App\Models\Recolte::selectRaw('MONTH(date_recolte) as mois, SUM(revenu_total) as total')
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->map(fn($row) => (object)[
                'mois' => $row->mois,
                'total' => (float) $row->total,
            ]);

        $performanceByCulture = \App\Models\Recolte::selectRaw('culture, SUM(quantite) as total_qte, AVG(benefice_net) as avg_benefice')
            ->groupBy('culture')
            ->get()
            ->map(fn($row) => (object)[
                'culture' => $row->culture,
                'total_qte' => (float) $row->total_qte,
                'avg_benefice' => (float) $row->avg_benefice,
            ]);

        \Log::info('BI performanceByCulture', ['count' => $performanceByCulture->count()]);

        $surfacesParCulture = \App\Models\Parcelle::selectRaw('culture, SUM(surface) as surface_totale')
            ->groupBy('culture')
            ->get()
            ->mapWithKeys(function ($row) {
                $key = strtolower(trim((string) $row->culture));
                $key = preg_replace('/\s+/', '', $key);
                if (class_exists('Normalizer')) {
                    $key = \Normalizer::normalize($key, \Normalizer::FORM_KD);
                    $key = preg_replace('/\p{Mn}/u', '', $key);
                }
                return [$key => (float) $row->surface_totale];
            });

        $performanceMetrics = [
            'best_culture' => null,
            'best_rendement' => 0,
            'worst_culture' => null,
            'worst_rendement' => null,
        ];
        if ($performanceByCulture->isNotEmpty()) {
            $culturesAvecRendement = $performanceByCulture->map(function ($row) use ($surfacesParCulture) {
                $key = strtolower(trim((string) $row->culture));
                $key = preg_replace('/\s+/', '', $key);
                if (class_exists('Normalizer')) {
                    $key = \Normalizer::normalize($key, \Normalizer::FORM_KD);
                    $key = preg_replace('/\p{Mn}/u', '', $key);
                }
                $surface = $surfacesParCulture->get($key) ?? 0;
                $rendement = $surface > 0 ? ($row->total_qte / $surface) : 0;
                return ['culture' => $row->culture, 'rendement' => $rendement];
            });
            $sorted = $culturesAvecRendement->sortByDesc('rendement')->values();
            $performanceMetrics['best_culture'] = $sorted->first()['culture'];
            $performanceMetrics['best_rendement'] = round($sorted->first()['rendement'], 2);
            $sortedAsc = $culturesAvecRendement->sortBy('rendement')->values();
            $performanceMetrics['worst_culture'] = $sortedAsc->first()['culture'];
            $performanceMetrics['worst_rendement'] = round($sortedAsc->first()['rendement'], 2);
        }

        $farmersByRegion = \App\Models\User::where('role', 'client')
            ->where('is_active', true)
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->selectRaw('location, COUNT(*) as total')
            ->groupBy('location')
            ->get()
            ->map(fn($row) => (object)[
                'location' => $row->location,
                'total' => (int) $row->total,
            ]);

        \Log::info('BI farmersByRegion', ['count' => $farmersByRegion->count()]);

        $regionMetrics = [
            'total_farmers' => $farmersByRegion->sum('total'),
            'region_count' => $farmersByRegion->count(),
            'dominant_region' => $farmersByRegion->sortByDesc('total')->first()?->location ?? 'N/A',
        ];

        $rendementByMonth = \App\Models\Recolte::selectRaw('MONTH(recoltes.date_recolte) as mois, CAST(SUM(recoltes.quantite) / NULLIF(SUM(parcelles.surface), 0) AS DECIMAL(10,2)) as avg_rendement')
            ->join('parcelles', 'recoltes.parcelle_id', '=', 'parcelles.id')
            ->where('parcelles.surface', '>', 0)
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->map(fn($row) => (object)[
                'mois' => $row->mois,
                'avg_rendement' => $row->avg_rendement !== null ? (float) $row->avg_rendement : 0,
            ]);

        \Log::info('BI rendementByMonth', ['count' => $rendementByMonth->count()]);

        $yieldMetrics = [
            'national_average' => $rendementByMonth->avg('avg_rendement'),
            'best_month' => $rendementByMonth->sortByDesc('avg_rendement')->first()?->mois ?? null,
            'worst_month' => $rendementByMonth->sortBy('avg_rendement')->first()?->mois ?? null,
            'trend' => 'stable',
        ];
        if ($rendementByMonth->count() >= 2) {
            $values = $rendementByMonth->pluck('avg_rendement')->values();
            $first = $values->first();
            $last = $values->last();
            if ($first > 0) {
                $pctChange = (($last - $first) / $first) * 100;
                $yieldMetrics['trend'] = $pctChange > 5 ? 'hausse' : ($pctChange < -5 ? 'baisse' : 'stable');
            }
        }

        $coutTotal = \App\Models\Recolte::sum('couts_totaux') ?? 0;
        $margeGlobale = $stats['revenus_totaux'] - $coutTotal;
        $revenuTotal = $stats['revenus_totaux'] ?? 0;
        $beneficeNet = $revenuTotal - $coutTotal;

        \Log::info('BI Final data', [
            'productionByMonth_count' => $productionByMonth->count(),
            'performanceByCulture_count' => $performanceByCulture->count(),
            'farmersByRegion_count' => $farmersByRegion->count(),
            'rendementByMonth_count' => $rendementByMonth->count(),
        ]);

        return view('analyses-bi', compact(
            'stats',
            'topFarmers',
            'atRiskFarmers',
            'recommendations',
            'cumulativeHarvests',
            'productionByMonth',
            'revenusByMonth',
            'performanceByCulture',
            'performanceMetrics',
            'surfacesParCulture',
            'farmersByRegion',
            'regionMetrics',
            'rendementByMonth',
            'yieldMetrics',
            'coutTotal',
            'margeGlobale',
            'revenuTotal',
            'beneficeNet'
        ));
    }

    // NOUVEAU : Affiche la supervision des agriculteurs (Fichier: supervision.blade.php)
    public function supervision() {
        $pendingClients = collect();

        if (Schema::hasColumn('users', 'is_active') && Schema::hasColumn('users', 'status')) {
            $pendingClients = User::where('role', 'client')
                ->where('status', 'pending')
                ->where('is_active', false)
                ->orderBy('created_at', 'asc')
                ->get();
        } elseif (Schema::hasColumn('users', 'is_active')) {
            $pendingClients = User::where('role', 'client')
                ->where('is_active', false)
                ->orderBy('created_at', 'asc')
                ->get();
        }

        $activityLogs = collect();
        if (Schema::hasTable('activity_logs')) {
            $activityLogs = ActivityLog::with(['actor', 'targetUser'])
                ->latest()
                ->take(25)
                ->get();
        }

        // Liste des clients approuvés pour le répertoire des agriculteurs
        $activeClients = User::where('role', 'client')
            ->where('is_active', true)
            ->where('status', 'approved')
            ->orderBy('name')
            ->get();

        return view('supervision', compact('pendingClients', 'activityLogs', 'activeClients'));
    }

    public function approveClient(User $user)
    {
        if ($user->role !== 'client') {
            return redirect()->back();
        }

        if (! Schema::hasColumn('users', 'is_active')) {
            return redirect()->route('manager.supervision')->with('status', 'Le champ is_active est manquant. Veuillez exécuter la migration.');
        }

        $payload = [
            'is_active' => true,
            'status' => 'approved',
            'rejection_reason' => null,
            'rejected_at' => null,
        ];

        if (Schema::hasColumn('users', 'approved_at')) {
            $payload['approved_at'] = now();
        }
        if (Schema::hasColumn('users', 'approved_by')) {
            $payload['approved_by'] = auth()->id();
        }

        $user->update($payload);

        ActivityLog::record(
            'client.approved',
            $user->id,
            'Compte approuvé par ' . (auth()->user()->name ?? 'manager')
        );

        \App\Models\Notification::notifyUser(
            $user->id,
            'inscription',
            'Compte approuvé',
            'Votre compte SeneBI a été approuvé. Vous pouvez maintenant vous connecter.',
            'success',
            url('/client/dashboard')
        );

        return redirect()->route('manager.supervision')->with(
            'status',
            'Le compte de ' . $user->name . ' a bien été approuvé. Il peut se connecter avec son email '
            . $user->email . ' et le mot de passe choisi lors de l\'inscription.'
        );
    }

    public function rejectClient(Request $request, User $user)
    {
        if ($user->role !== 'client') {
            return redirect()->back();
        }

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $reason = $request->reason ?? 'Compte rejeté par l\'administrateur.';

        $payload = [
            'is_active' => false,
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ];

        if (Schema::hasColumn('users', 'rejected_at')) {
            $payload['rejected_at'] = now();
        }
        if (Schema::hasColumn('users', 'approved_at')) {
            $payload['approved_at'] = null;
        }
        if (Schema::hasColumn('users', 'approved_by')) {
            $payload['approved_by'] = null;
        }

        $user->update($payload);

        ActivityLog::record(
            'client.rejected',
            $user->id,
            $reason
        );

        \App\Models\Notification::notifyUser(
            $user->id,
            'inscription',
            'Compte non approuvé',
            'Votre demande d\'inscription n\'a pas été approuvée. Raison : ' . $reason,
            'danger',
            url('/contact')
        );

        return redirect()->route('manager.supervision')->with('status', 'Le compte de ' . $user->name . ' a bien été rejeté.');
    }

    public function destroyUser(User $user)
    {
        if ($user->role === 'manager' && $user->email === 'mimi.manager@senebi.ml') {
            return response()->json(['error' => 'Le compte manager principal ne peut pas être supprimé.'], 403);
        }

        $user->forceDelete();

        return response()->json(['success' => true, 'message' => 'Utilisateur supprimé définitivement.']);
    }

    // NOUVEAU : Affiche le compte manager (Fichier: compte.blade.php)
    public function compte() {
        $user = Auth::user();
        
        return view('compte', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password'
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire',
            'new_password.required' => 'Le nouveau mot de passe est obligatoire',
            'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 6 caractères',
            'confirm_password.required' => 'La confirmation est obligatoire',
            'confirm_password.same' => 'La confirmation ne correspond pas au nouveau mot de passe'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'error' => 'Mot de passe actuel incorrect'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe mis à jour avec succès'
        ]);
    }

    // Notification Manager
    public function notifications()
    {
        return view('manager.notifications');
    }

    public function notificationsIndexApi()
    {
        $notifications = \App\Models\Notification::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return response()->json(['data' => $notifications]);
    }

    public function notificationsReadAllApi()
    {
        \App\Models\Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }

    public function notificationsDestroyApi(\App\Models\Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return response()->json(['ok' => true]);
    }

    public function farmerDetailApi(User $user)
    {
        if ($user->role !== 'client') {
            abort(404);
        }

        $user->load(['parcelles', 'stocks', 'recoltes', 'visites', 'intrantsConsommes']);

        $surfaceTotal = $user->parcelles->sum('surface');
        $totalRecolte = $user->recoltes->sum('quantite');
        $rendementMoyen = $surfaceTotal > 0 ? ($totalRecolte / $surfaceTotal) : 0;
        $totalCA = $user->recoltes->sum('revenu_total');
        $totalCouts = $user->intrantsConsommes->sum(fn($ic) => $ic->quantite_consommee * ($ic->stock->cout_unitaire ?? 0));
        $beneficeNet = $totalCA - $totalCouts;
        $rentabiliteMoyenne = $user->recoltes->avg('benefice_net') ?? 0;

        $stocksData = $user->stocks->map(function ($s) {
            $quantite = (float) $s->quantite_actuelle;
            $seuil = (float) $s->seuil_critique;
            $rawMinimum = (float) $s->stock_minimum;
            $minimum = $rawMinimum > 0 ? $rawMinimum : max($seuil * 1.5, 1);

            $pourcentage = $minimum > 0 ? min(100, round(($quantite / $minimum) * 100)) : 0;

            if ($quantite <= $seuil) {
                $statut = 'Critique';
            } elseif ($quantite <= $minimum) {
                $statut = 'Faible';
            } else {
                $statut = 'OK';
            }

            return [
                'nom' => $s->nom,
                'quantite' => $quantite,
                'seuil' => $seuil,
                'minimum' => $minimum,
                'est_critique' => $quantite <= $seuil,
                'statut' => $statut,
                'pourcentage' => $pourcentage,
            ];
        });

        $visitesData = $user->visites->sortByDesc('date_visite')->take(10)->map(fn($v) => [
            'date' => $v->date_visite->format('d/m/Y H:i'),
            'action' => $v->action_effectuee,
            'recommandation' => $v->recommandation,
            'statut' => $v->date_visite->lt(now()) ? 'Effectuée' : 'Planifiée',
        ]);

        $parcellesAvecCulture = $user->parcelles()
            ->whereNotNull('culture')
            ->where('culture', '!=', '')
            ->get();

        $culturesData = $parcellesAvecCulture->groupBy('culture')->map(fn($group) => [
            'culture' => $group->first()->culture,
            'surface' => $group->sum('surface'),
            'parcelles_count' => $group->count(),
        ])->values();

        $alertes = [];
        foreach ($user->stocks as $stock) {
            if ($stock->quantite_actuelle <= $stock->seuil_critique) {
                $alertes[] = "Stock critique: {$stock->nom} ({$stock->quantite_actuelle} kg restant)";
            }
        }
        if ($beneficeNet < 0) {
            $alertes[] = "Rentabilité négative: " . number_format($beneficeNet, 0, ',', ' ') . " FCFA";
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'location' => $user->location ?? 'Non spécifié',
            'date_inscription' => $user->created_at->format('d/m/Y'),
            'parcelles_count' => $user->parcelles->count(),
            'surface_totale' => $surfaceTotal,
            'rendement_moyen' => round($rendementMoyen, 2),
            'rentabilite_moyenne' => round($rentabiliteMoyenne, 2),
            'production_totale' => $totalRecolte,
            'chiffre_affaires' => $totalCA,
            'benefice_net' => $beneficeNet,
            'stocks' => $stocksData,
            'visites' => $visitesData,
            'cultures' => $culturesData,
            'alertes' => $alertes,
            'last_activity' => $user->visites->sortByDesc('date_visite')->first()?->date_visite->format('d/m/Y H:i') ?? 'Aucune',
        ]);
    }

    public function supervisionStatsApi()
    {
        $activeClients = User::where('role', 'client')
            ->where('is_active', true)
            ->where('status', 'approved')
            ->count();

        $todayActivities = 0;
        if (Schema::hasTable('activity_logs')) {
            $todayActivities = ActivityLog::whereDate('created_at', today())->count();
        }

        $criticalStocks = Stock::whereColumn('quantite_actuelle', '<=', 'seuil_critique')->count();

        $pendingClients = 0;
        if (Schema::hasColumn('users', 'is_active') && Schema::hasColumn('users', 'status')) {
            $pendingClients = User::where('role', 'client')
                ->where('status', 'pending')
                ->where('is_active', false)
                ->count();
        } elseif (Schema::hasColumn('users', 'is_active')) {
            $pendingClients = User::where('role', 'client')
                ->where('is_active', false)
                ->count();
        }
        $systemAlerts = $criticalStocks + $pendingClients;

        $performanceScore = $activeClients > 0
            ? min(100, round(($activeClients / max(1, $activeClients + $pendingClients)) * 100))
            : 100;

        return response()->json([
            'activeUsers' => $activeClients,
            'dailyActivities' => $todayActivities,
            'systemAlerts' => $systemAlerts,
            'performanceScore' => $performanceScore,
        ]);
    }
}
