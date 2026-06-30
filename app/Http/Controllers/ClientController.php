<?php

namespace App\Http\Controllers;

use App\Models\Parcelle;
use App\Models\Stock;
use App\Models\Recolte;
use App\Models\Intrant;
use App\Models\IntrantConsomme;
use App\Models\Visite;
use App\Models\Objectif;
use App\Models\PdfExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function clientDashboard() {
        $user = Auth::user();
        $derniereVisite = Visite::where('user_id', $user->id)->latest()->first();
        
        $advisor = new \App\Services\AgriculturalAdvisorService();
        $widgets = $advisor->getDashboardWidgets();
        $alerts = $advisor->generateAlerts();
        $health = $advisor->getExploitationHealth();
        $recommendations = $advisor->getRecommendations();

        $totalRecolteQte = Recolte::where('user_id', $user->id)->sum('quantite') ?? 0;
        $totalCA = Recolte::where('user_id', $user->id)->sum('revenu_total') ?? 0;
        $totalCouts = IntrantConsomme::where('user_id', $user->id)
            ->with('stock')
            ->get()
            ->sum(fn($ic) => $ic->quantite_consommee * ($ic->stock->cout_unitaire ?? 0));
        $beneficeNet = $totalCA - $totalCouts;

        $hectaresActifs = Parcelle::where('user_id', $user->id)->sum('surface') ?? 0;
        $surfaceRecoltee = Recolte::where('user_id', $user->id)
            ->with('parcelle')
            ->get()
            ->sum(fn($r) => $r->parcelle->surface ?? 0);
        $rendementMoyen = $surfaceRecoltee > 0 ? ($totalRecolteQte / $surfaceRecoltee) : 0;

        $now = now();
        $debutMoisActuel = $now->copy()->startOfMonth();
        $debutMoisDernier = $now->copy()->subMonth()->startOfMonth();
        $finMoisDernier = $now->copy()->subMonth()->endOfMonth();

        $caMoisActuel = Recolte::where('user_id', $user->id)
            ->where('date_recolte', '>=', $debutMoisActuel)
            ->sum('revenu_total') ?? 0;
        $caMoisDernier = Recolte::where('user_id', $user->id)
            ->whereBetween('date_recolte', [$debutMoisDernier, $finMoisDernier])
            ->sum('revenu_total') ?? 0;
        $caEvolution = $caMoisDernier > 0 ? (($caMoisActuel - $caMoisDernier) / $caMoisDernier) * 100 : 0;

        $haMoisActuel = Parcelle::where('user_id', $user->id)
            ->where('created_at', '>=', $debutMoisActuel)
            ->sum('surface') ?? 0;
        $haMoisDernier = Parcelle::where('user_id', $user->id)
            ->whereBetween('created_at', [$debutMoisDernier, $finMoisDernier])
            ->sum('surface') ?? 0;
        $haEvolution = $haMoisDernier > 0 ? (($haMoisActuel - $haMoisDernier) / $haMoisDernier) * 100 : 0;

        $rendementMoisActuel = $surfaceRecoltee > 0 ? ($totalRecolteQte / $surfaceRecoltee) : 0;
        $rendementMoisDernier = 0;
        $recoltesMoisDernier = Recolte::where('user_id', $user->id)
            ->whereBetween('date_recolte', [$debutMoisDernier, $finMoisDernier])
            ->get();
        $surfaceMoisDernier = $recoltesMoisDernier->sum(fn($r) => $r->parcelle->surface ?? 0);
        $qteMoisDernier = $recoltesMoisDernier->sum('quantite');
        if ($surfaceMoisDernier > 0) {
            $rendementMoisDernier = $qteMoisDernier / $surfaceMoisDernier;
        }
        $rendementEvolution = $rendementMoisDernier > 0 ? (($rendementMoisActuel - $rendementMoisDernier) / $rendementMoisDernier) * 100 : 0;

        // Graphiques réels
        $prixCultures = \App\Models\Intrant::selectRaw('LOWER(nom) as culture_lower, prix')
            ->whereIn('nom', ['Riz', 'Maïs', 'Coton', 'Urée', 'NPK 15-15-15', 'Semences Maïs', 'Semences Riz'])
            ->get()
            ->groupBy('culture_lower')
            ->map(fn($items) => $items->avg('prix'));

        if (!$prixCultures->has('riz')) {
            $prixCultures['riz'] = 0;
        }
        if (!$prixCultures->has('maïs') && !$prixCultures->has('mais')) {
            $prixCultures['maïs'] = 0;
        }
        if (!$prixCultures->has('coton')) {
            $prixCultures['coton'] = 0;
        }

        $culturesLabels = [];
        $culturesData = [];
        $userRecoltes = Recolte::where('user_id', $user->id)
            ->selectRaw('culture, SUM(quantite) as total_qte')
            ->groupBy('culture')
            ->get();
        foreach ($userRecoltes as $rc) {
            $culturesLabels[] = $rc->culture;
            $culturesData[] = $rc->total_qte;
        }
        if (empty($culturesLabels)) {
            $culturesLabels = ['Riz', 'Maïs', 'Coton'];
            $culturesData = [0, 0, 0];
        }

        $stockCritical = \App\Models\Stock::where('user_id', $user->id)
            ->whereColumn('quantite_actuelle', '<=', 'seuil_critique')
            ->get(['nom', 'quantite_actuelle', 'seuil_critique']);

        // Nouvelles données - Objectifs de saison
        $objectif = Objectif::where('user_id', $user->id)
            ->where('saison', $user->saison ?? '2026')
            ->first();

        // Données pour les KPI secondaires
        $parcellesActives = Parcelle::where('user_id', $user->id)->count();
        $intrantsCritiques = $stockCritical->count();
        $visitesRealisees = Visite::where('user_id', $user->id)->count();
        $culturesExploitees = Recolte::where('user_id', $user->id)->distinct('culture')->count('culture');

        // Prévision de production (basé sur tendance)
        $productionEstimee = $totalRecolteQte > 0 ? $totalRecolteQte * 1.1 : 0;
        $productionTendance = $caEvolution >= 5 ? 'hausse' : ($caEvolution <= -5 ? 'baisse' : 'stable');
        $productionConfiance = min(95, max(50, 80 + abs($caEvolution)));

        // Comparaison régionale
        $region = $user->location ?? 'Sénégal';
        $rendementRegional = Parcelle::where('region', $region)
            ->join('recoltes', 'parcelles.id', '=', 'recoltes.parcelle_id')
            ->avg('quantite') ?? 0;
        $rendementRegional = $rendementRegional > 0 ? ($rendementRegional / $hectaresActifs) : 0;
        $ecartRegional = $rendementMoyen > 0 && $rendementRegional > 0 
            ? (($rendementMoyen - $rendementRegional) / $rendementRegional) * 100 
            : 0;

        // Meteo (données simulées - API externe à intégrer plus tard)
        $meteoData = [
            'temperature' => rand(25, 38),
            'humidity' => rand(40, 85),
            'rainProb' => rand(5, 60),
            'windSpeed' => rand(5, 25),
            'forecast' => [
                ['day' => 'Lun', 'temp' => rand(28, 36), 'icon' => 'sun'],
                ['day' => 'Mar', 'temp' => rand(28, 36), 'icon' => 'cloud-sun'],
                ['day' => 'Mer', 'temp' => rand(28, 36), 'icon' => 'cloud'],
                ['day' => 'Jeu', 'temp' => rand(28, 36), 'icon' => 'cloud-rain'],
                ['day' => 'Ven', 'temp' => rand(28, 36), 'icon' => 'sun'],
            ],
            'alerts' => $this->getMeteoAlerts($region),
        ];

        // Alertes intelligentes supplémentaires
        $additionalAlerts = $this->getAdditionalAlerts($user, $totalRecolteQte, $rendementMoyen, $derniereVisite);

        return view('client-dashboard', compact(
            'derniereVisite',
            'widgets',
            'alerts',
            'health',
            'recommendations',
            'totalRecolteQte',
            'totalCA',
            'totalCouts',
            'beneficeNet',
            'hectaresActifs',
            'rendementMoyen',
            'caEvolution',
            'haEvolution',
            'rendementEvolution',
            'prixCultures',
            'culturesLabels',
            'culturesData',
            'stockCritical',
            'objectif',
            'parcellesActives',
            'intrantsCritiques',
            'visitesRealisees',
            'culturesExploitees',
            'productionEstimee',
            'productionTendance',
            'productionConfiance',
            'region',
            'rendementRegional',
            'ecartRegional',
            'meteoData',
            'additionalAlerts'
        ));
    }

    protected function getMeteoAlerts($region)
    {
        $alerts = [];
        if (rand(0, 1)) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'Risque de sécheresse cette semaine. Arrosez vos parcelles.',
                'icon' => 'exclamation-triangle'
            ];
        }
        return $alerts;
    }

    protected function getAdditionalAlerts($user, $totalRecolteQte, $rendementMoyen, $derniereVisite)
    {
        $alerts = [];
        
        // Stock critique
        $criticalStocks = Stock::where('user_id', $user->id)
            ->whereColumn('quantite_actuelle', '<=', 'seuil_critique')
            ->count();
        if ($criticalStocks > 0) {
            $alerts[] = [
                'type' => 'danger',
                'title' => 'Stock critique',
                'message' => "{$criticalStocks} intrant(s) en dessous du seuil critique",
                'icon' => 'box'
            ];
        }

        // Absence d'activité
        $joursSansActivite = $user->updated_at 
            ? now()->diffInDays($user->updated_at) 
            : 0;
        if ($joursSansActivite > 3 && $totalRecolteQte > 0) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Activité faible',
                'message' => "Aucune activité depuis {$joursSansActivite} jours",
                'icon' => 'clock'
            ];
        }

        // Rendement inférieur à la moyenne
        if ($rendementMoyen > 0 && $rendementMoyen < 1.0) {
            $alerts[] = [
                'type' => 'info',
                'title' => 'Rendement à améliorer',
                'message' => 'Votre rendement est inférieur à la moyenne attendue',
                'icon' => 'seedling'
            ];
        }

        // Visite recommandée
        if (!$derniereVisite || now()->diffInDays($derniereVisite->date_visite) > 14) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Visite conseillée',
                'message' => 'Prévoyez une visite technique prochainement',
                'icon' => 'stethoscope'
            ];
        }

        return $alerts;
    }

    // Affiche le calculateur de rentabilité
    public function rentabilite() {
        $user = Auth::user();
        
        // Récupérer les récoltes du client
        $recoltes = $user->recoltes()->with('parcelle')->latest()->get();
        
        // Calculer les statistiques de rentabilité
        $totalCA = $recoltes->sum('revenu_total');
        $totalCouts = $recoltes->sum('couts_totaux');
        $totalBenefice = $totalCA - $totalCouts;
        
        // Calculer la marge moyenne
        $margeMoyenne = $totalCA > 0 ? ($totalBenefice / $totalCA) * 100 : 0;
        
        // Récupérer les parcelles pour le sélecteur
        $parcelles = $user->parcelles()->orderBy('nom')->get();
        $parcellesData = $parcelles->map(fn($p) => [
            'id' => $p->id,
            'nom' => $p->nom,
            'surface' => (float) $p->surface,
            'culture' => $p->culture,
        ])->values();

        $rentabiliteHarvests = $recoltes->map(fn($r) => [
            'date' => $r->date_recolte->format('d/m/Y'),
            'parcel' => $r->parcelle->nom ?? 'N/A',
            'qtyKg' => $r->quantite,
            'unitPrice' => $r->prix_unitaire,
            'costs' => $r->couts_totaux,
            'revenue' => $r->revenu_total,
            'profit' => $r->benefice_net,
            'culture' => $r->culture,
        ]);
        
        // ===================== NOUVELLES DONNÉES FINANCIÈRES =====================
        
        // Prévisions financières (basées sur tendance des 3 derniers mois)
        $now = now();
        $dateTroisMois = $now->copy()->subMonths(3)->startOfMonth();
        
        $caTroisDerniersMois = Recolte::where('user_id', $user->id)
            ->where('date_recolte', '>=', $dateTroisMois)
            ->sum('revenu_total');
        $beneficeTroisDerniersMois = Recolte::where('user_id', $user->id)
            ->where('date_recolte', '>=', $dateTroisMois)
            ->sum('benefice_net');
        
        $moyenneMensuelleCA = $caTroisDerniersMois > 0 ? $caTroisDerniersMois / 3 : 0;
        $moyenneMensuelleBenefice = $beneficeTroisDerniersMois > 0 ? $beneficeTroisDerniersMois / 3 : 0;
        
        // Tendance financière
        $tendanceFinanciere = $moyenneMensuelleCA > 0 && $totalCA > 0 
            ? (($totalCA - $moyenneMensuelleCA) / $moyenneMensuelleCA) * 100 
            : 0;
        
        // Projections sur les prochains mois
        $projections = [];
        $growth = 1 + ($tendanceFinanciere / 100) * 0.5;
        for ($i = 1; $i <= 3; $i++) {
            $projections[] = [
                'mois' => $now->copy()->addMonths($i)->format('M Y'),
                'revenu' => round($moyenneMensuelleCA * $growth, 0),
                'benefice' => round($moyenneMensuelleBenefice * $growth, 0),
            ];
        }
        
        // Comparaisons historiques
        $anneeActuelle = $now->year;
        $anneePrecedente = $anneeActuelle - 1;
        $saisonActuelle = $user->saison ?? (string)$anneeActuelle;
        $saisonPrecedente = (string)($saisonActuelle - 1);
        
        // Comparaison saison actuelle vs précédente
        $caSaisonActuelle = Recolte::where('user_id', $user->id)
            ->where('saison', $saisonActuelle)
            ->sum('revenu_total');
        $caSaisonPrecedente = Recolte::where('user_id', $user->id)
            ->where('saison', $saisonPrecedente)
            ->sum('revenu_total');
        $varSaisonCA = $caSaisonPrecedente > 0 
            ? (($caSaisonActuelle - $caSaisonPrecedente) / $caSaisonPrecedente) * 100 
            : 0;
        
        // Comparaison année actuelle vs précédente
        $caAnneeActuelle = Recolte::where('user_id', $user->id)
            ->whereYear('date_recolte', $anneeActuelle)
            ->sum('revenu_total');
        $caAnneePrecedente = Recolte::where('user_id', $user->id)
            ->whereYear('date_recolte', $anneePrecedente)
            ->sum('revenu_total');
        $varAnneeCA = $caAnneePrecedente > 0 
            ? (($caAnneeActuelle - $caAnneePrecedente) / $caAnneePrecedente) * 100 
            : 0;
        
        // Répartition des coûts par type
        $coutsEngrais = IntrantConsomme::where('intrant_consommes.user_id', $user->id)
            ->join('stocks', 'intrant_consommes.stock_id', '=', 'stocks.id')
            ->where('stocks.type', 'Engrais')
            ->sum(\DB::raw('quantite_consommee * cout_unitaire'));
        $coutsSemences = IntrantConsomme::where('intrant_consommes.user_id', $user->id)
            ->join('stocks', 'intrant_consommes.stock_id', '=', 'stocks.id')
            ->where('stocks.type', 'Semence')
            ->sum(\DB::raw('quantite_consommee * cout_unitaire'));
        $coutsMainOeuvre = IntrantConsomme::where('intrant_consommes.user_id', $user->id)
            ->join('stocks', 'intrant_consommes.stock_id', '=', 'stocks.id')
            ->where('stocks.type', 'like', '%main%')
            ->sum(\DB::raw('quantite_consommee * cout_unitaire'));
        $coutsTransport = IntrantConsomme::where('intrant_consommes.user_id', $user->id)
            ->join('stocks', 'intrant_consommes.stock_id', '=', 'stocks.id')
            ->where('stocks.type', 'like', '%transport%')
            ->sum(\DB::raw('quantite_consommee * cout_unitaire'));
        $coutsHerbicides = IntrantConsomme::where('intrant_consommes.user_id', $user->id)
            ->join('stocks', 'intrant_consommes.stock_id', '=', 'stocks.id')
            ->where('stocks.type', 'like', '%herbicide%')
            ->sum(\DB::raw('quantite_consommee * cout_unitaire'));
        $coutsAutres = max(0, $totalCouts - ($coutsEngrais + $coutsSemences + $coutsMainOeuvre + $coutsTransport + $coutsHerbicides));
        
        // Top 3 cultures les plus rentables
        $topCultures = Recolte::where('user_id', $user->id)
            ->selectRaw('culture, SUM(benefice_net) as benefice_total, SUM(revenu_total) as chiffre_affaires, AVG(prix_unitaire) as prix_moyen')
            ->where('benefice_net', '>', 0)
            ->groupBy('culture')
            ->orderByDesc('benefice_total')
            ->limit(3)
            ->get();

        // Calcul des rendements par culture a partir des donnees reelles
        $cultureYields = [];
        $parcellesParCulture = $user->parcelles()->get()->groupBy('culture');
        $recoltesParCulture = $user->recoltes()->get()->groupBy('culture');
        $toutesCultures = $parcellesParCulture->keys()->merge($recoltesParCulture->keys())->unique();

        foreach ($toutesCultures as $culture) {
            $surface = $parcellesParCulture->get($culture, collect())->sum('surface');
            $quantite = $recoltesParCulture->get($culture, collect())->sum('quantite');
            $rendement = $surface > 0 ? round($quantite / $surface, 2) : 0;
            $cultureYields[] = [
                'culture' => $culture,
                'surface' => $surface,
                'quantite' => $quantite,
                'rendement' => $rendement,
            ];
        }

        // Trier par rendement decroissant
        usort($cultureYields, fn($a, $b) => $b['rendement'] <=> $a['rendement']);
        
        // Badge de performance automatique
        $badgePerformance = $this->calculatePerformanceBadge($totalCA, $totalBenefice, $margeMoyenne);
        
        // Historique des exports PDF
        $pdfHistory = PdfExport::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(fn($p) => [
                'date' => $p->created_at->format('d/m/Y H:i'),
                'type' => ucfirst($p->type),
                'file_path' => $p->file_path,
            ]);
        
        return view('rentabilite', compact(
            'recoltes',
            'totalCA',
            'totalCouts',
            'totalBenefice',
            'margeMoyenne',
            'parcelles',
            'parcellesData',
            'rentabiliteHarvests',
            'moyenneMensuelleCA',
            'moyenneMensuelleBenefice',
            'tendanceFinanciere',
            'projections',
            'caSaisonActuelle',
            'caSaisonPrecedente',
            'varSaisonCA',
            'caAnneeActuelle',
            'caAnneePrecedente',
            'varAnneeCA',
            'coutsEngrais',
            'coutsSemences',
            'coutsHerbicides',
            'coutsMainOeuvre',
            'coutsTransport',
            'coutsAutres',
            'topCultures',
            'cultureYields',
            'badgePerformance',
            'pdfHistory'
        ));
    }
    
    protected function calculatePerformanceBadge($ca, $benefice, $marge)
    {
        if ($benefice < 0) {
            return ['label' => 'Exploitation en perte', 'class' => 'perf-badge perte', 'icon' => 'fa-frown'];
        } elseif ($marge >= 30) {
            return ['label' => 'Excellente rentabilité', 'class' => 'perf-badge excellente', 'icon' => 'fa-trophy'];
        } elseif ($marge >= 15) {
            return ['label' => 'Bonne rentabilité', 'class' => 'perf-badge bonne', 'icon' => 'fa-thumbs-up'];
        } elseif ($marge >= 5) {
            return ['label' => 'Rentabilité moyenne', 'class' => 'perf-badge moyenne', 'icon' => 'fa-chart-line'];
        } else {
            return ['label' => 'Faible rentabilité', 'class' => 'perf-badge faible', 'icon' => 'fa-exclamation-triangle'];
        }
    }

    // Affiche la page de notifications client
    public function notifications()
    {
        return view('client.notifications');
    }

    // Affiche la gestion des parcelles
    public function parcelles() {
        $user = Auth::user();
        $parcelles = $user->parcelles()->with(['recoltes', 'visites', 'intrantsConsommes', 'photos'])->orderBy('nom')->get();

        $parcelleStats = $parcelles->map(function ($p) use ($user) {
            $recoltesCount = $p->recoltes()->count();
            $recoltesTotal = $p->recoltes()->sum('quantite');
            $surface = (float) $p->surface;
            $rendement = $surface > 0 ? ($recoltesTotal / $surface) : 0;
            $benefice = $p->recoltes()->sum('benefice_net');
            $visites = $p->visites()->count();
            $intrantsCount = $p->intrantsConsommes()->count();
            $stockScore = 0;

            $stocks = Stock::where('user_id', $user->id)->get();
            foreach ($stocks as $stock) {
                $ratio = $stock->seuil_critique > 0 ? ($stock->quantite_actuelle / $stock->seuil_critique) : 1;
                $stockScore += $ratio;
            }
            $stockScore = $stocks->count() > 0 ? $stockScore / $stocks->count() : 0.5;

            $score = 0;
            if ($rendement >= 1.0) $score += 40;
            elseif ($rendement >= 0.5) $score += 25;
            else $score += 10;

            if ($benefice >= 500000) $score += 30;
            elseif ($benefice >= 0) $score += 15;
            else $score += 0;

            $score += $stockScore * 30;

            if ($visites >= 2) $score += 10;

            if ($score >= 80) {
                $badge = 'Excellent';
                $badgeClass = 'perf-badge excellent';
            } elseif ($score >= 50) {
                $badge = 'Bon';
                $badgeClass = 'perf-badge moyen';
            } elseif ($score >= 30) {
                $badge = 'Moyen';
                $badgeClass = 'perf-badge moyen';
            } else {
                $badge = 'Critique';
                $badgeClass = 'perf-badge risque';
            }

            $cultureDuration = 0;
            $firstRecolte = $p->recoltes()->oldest()->first();
            if ($firstRecolte) {
                $cultureDuration = now()->diffInDays($firstRecolte->date_recolte);
            }

            $productionEstimee = $recoltesTotal > 0 ? $recoltesTotal * 1.1 : 0;

            return [
                'id' => $p->id,
                'nom' => $p->nom,
                'culture' => $p->culture,
                'surface' => $surface,
                'rendement' => round($rendement, 2),
                'benefice' => round($benefice, 0),
                'badge' => $badge,
                'badgeClass' => $badgeClass,
                'recoltesCount' => $recoltesCount,
                'intrantsCount' => $intrantsCount,
                'visitesCount' => $visites,
                'cultureDuration' => $cultureDuration,
                'productionEstimee' => $productionEstimee,
                'latitude' => $p->latitude,
                'longitude' => $p->longitude,
                'last_irrigation' => $p->last_irrigation,
                'last_traitement' => $p->last_traitement,
                'next_intervention' => $p->next_intervention,
                'photos' => $p->photos,
            ];
        });

        return view('parcelles', compact('parcelles', 'parcelleStats'));
    }

    // Affiche la gestion des stocks du client
    public function stocks() {
        app(\App\Http\Controllers\ClientApiController::class)->stocksIndex();
        $user = Auth::user();
        $stocks = Stock::where('user_id', $user->id)->orderBy('nom')->get();
        $mouvements = \App\Models\StockMouvement::whereHas('stock', fn($q) => $q->where('user_id', $user->id))
            ->with('stock')
            ->orderByDesc('date_mouvement')
            ->limit(50)
            ->get();
        
        $parcelles = $user->parcelles()->orderBy('nom')->get();

        // Récupérer les prix depuis la table intrants
        $intrants = Intrant::all()->keyBy('nom');

        // Créer des stocks par défaut si l'utilisateur n'en a pas ou ajouter les manquants
        $defaults = [
            ['nom' => 'Urée', 'type' => 'Engrais', 'quantite_actuelle' => 520, 'seuil_critique' => 500, 'cout_unitaire' => $intrants['Urée']->prix ?? 15000],
            ['nom' => 'NPK', 'type' => 'Engrais', 'quantite_actuelle' => 900, 'seuil_critique' => 450, 'cout_unitaire' => $intrants['NPK 15-15-15']->prix ?? 18000],
            ['nom' => 'Semence Maïs', 'type' => 'Semence', 'quantite_actuelle' => 240, 'seuil_critique' => 100, 'cout_unitaire' => $intrants['Semences Maïs']->prix ?? 800],
            ['nom' => 'Semence Coton', 'type' => 'Semence', 'quantite_actuelle' => 1250, 'seuil_critique' => 500, 'cout_unitaire' => 1200],
            ['nom' => 'Semence Riz', 'type' => 'Semence', 'quantite_actuelle' => 600, 'seuil_critique' => 300, 'cout_unitaire' => $intrants['Semences Riz']->prix ?? 1000],
        ];
        
        foreach ($defaults as $default) {
            $existingStock = $stocks->firstWhere('nom', $default['nom']);
            if (!$existingStock) {
                Stock::create([...$default, 'user_id' => Auth::id()]);
            }
        }
        
        // Recharger les stocks après création
        $stocks = Stock::where('user_id', Auth::id())->orderBy('nom')->get();

        // Calculer les prévisions d'épuisement basées sur les 30 derniers jours
        $consumptionByStock = \App\Models\IntrantConsomme::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->select('stock_id', \DB::raw('SUM(quantite_consommee) as total'))
            ->groupBy('stock_id')
            ->pluck('total', 'stock_id');
        
        $dailyConsumptionAvg = $consumptionByStock->sum() / 30;
        
        $stockForecasts = $stocks->map(function($s) use ($consumptionByStock, $dailyConsumptionAvg) {
            $dailyConsumption = $consumptionByStock->get($s->id, 0) > 0 
                ? $consumptionByStock->get($s->id, 0) / 30 
                : ($dailyConsumptionAvg > 0 ? $dailyConsumptionAvg : 1);
            
            $joursRestants = $dailyConsumption > 0 
                ? max(0, (int) floor($s->quantite_actuelle / $dailyConsumption)) 
                : 999;
            
            return [
                'nom' => $s->nom,
                'quantite' => $s->quantite_actuelle,
                'seuil_critique' => $s->seuil_critique,
                'jours_restants' => $joursRestants,
                'est_critique' => $s->quantite_actuelle <= $s->seuil_critique,
            ];
        });

        // Calculer les top consommateurs par parcelle
        $topConsumers = \App\Models\IntrantConsomme::where('user_id', $user->id)
            ->where('created_at', '>=', now()->startOfMonth())
            ->with('parcelle')
            ->select('parcelle_id', \DB::raw('SUM(quantite_consommee) as total'))
            ->groupBy('parcelle_id')
            ->orderByDesc('total')
            ->limit(3)
            ->get()
            ->map(function($ic) {
                return [
                    'parcelle' => $ic->parcelle->nom ?? 'Inconnue',
                    'volume' => $ic->total,
                ];
            });

        // Calculer la consommation mensuelle par intrant
        $monthlyConsumption = \App\Models\IntrantConsomme::where('user_id', $user->id)
            ->where('created_at', '>=', now()->startOfMonth())
            ->with('stock')
            ->select('stock_id', \DB::raw('SUM(quantite_consommee) as total'))
            ->groupBy('stock_id')
            ->orderByDesc('total')
            ->get()
            ->map(function($ic) {
                return [
                    'nom' => $ic->stock->nom ?? 'Inconnu',
                    'type' => $ic->stock->type ?? 'Inconnu',
                    'volume' => $ic->total,
                ];
            });

        return view('stocks', compact('stocks', 'intrants', 'mouvements', 'stockForecasts', 'topConsumers', 'monthlyConsumption', 'parcelles'));
    }

    // Affiche le profil et les informations du compte
    public function compte() {
        $user = Auth::user();
        
        return view('compte-client', compact('user'));
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

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'error' => 'Mot de passe actuel incorrect'
            ], 400);
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe mis à jour avec succès'
        ]);
    }
}