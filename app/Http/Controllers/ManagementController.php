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

        return view('visits-control', compact('visites', 'totalVisites', 'visitesCeMois', 'clients', 'urgentClients'));
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

    // NOUVEAU : Affiche le catalogue des prix (Fichier: catalogue.blade.php)
    public function catalogue() {
        // Récupérer les intrants depuis la base de données
        $intrants = Intrant::orderBy('nom')->get();
        
        // Si la table est vide, créer les intrants par défaut
        if ($intrants->isEmpty()) {
            $defaults = [
                [
                    'nom' => 'Urée',
                    'description' => 'Engrais azoté 46%',
                    'unite' => 'Sac de 50kg',
                    'prix' => 25000,
                    'statut' => 'Disponible',
                    'type' => 'fertilizer',
                    'derniere_maj' => now()
                ],
                [
                    'nom' => 'NPK 15-15-15',
                    'description' => 'Engrais complet',
                    'unite' => 'Sac de 50kg',
                    'prix' => 35000,
                    'statut' => 'Disponible',
                    'type' => 'fertilizer',
                    'derniere_maj' => now()
                ],
                [
                    'nom' => 'Semences Maïs',
                    'description' => 'Variété améliorée',
                    'unite' => 'kg',
                    'prix' => 1200,
                    'statut' => 'Stock limité',
                    'type' => 'seed',
                    'derniere_maj' => now()
                ],
                [
                    'nom' => 'Semences Riz',
                    'description' => 'Variété IR841',
                    'unite' => 'kg',
                    'prix' => 1500,
                    'statut' => 'Disponible',
                    'type' => 'seed',
                    'derniere_maj' => now()
                ],
                [
                    'nom' => 'Herbicide',
                    'description' => 'Désherbage sélectif',
                    'unite' => 'Litre',
                    'prix' => 8500,
                    'statut' => 'Disponible',
                    'type' => 'chemical',
                    'derniere_maj' => now()
                ],
            ];
            
            foreach ($defaults as $default) {
                Intrant::create($default);
            }
            
            $intrants = Intrant::orderBy('nom')->get();
        }
        
        return view('catalogue', compact('intrants'));
    }

    // NOUVEAU : Met à jour les prix du catalogue
    public function updateCatalogue(Request $request) {
        $request->validate([
            'prix' => 'required|array',
            'prix.*' => 'required|numeric|min:0',
            'statut' => 'required|array',
            'statut.*' => 'required|string',
        ]);

        foreach ($request->prix as $id => $prix) {
            $intrant = Intrant::find($id);
            if ($intrant) {
                $intrant->prix = $prix;
                $intrant->statut = $request->statut[$id] ?? 'Disponible';
                $intrant->derniere_maj = now();
                $intrant->save();
            }
        }

        return redirect()->route('manager.catalogue')->with('status', 'Les tarifs ont été mis à jour avec succès.');
    }

    // NOUVEAU : Affiche la supervision des agriculteurs (Fichier: supervision.blade.php)
    public function supervision() {
        $pendingClients = collect();

        if (Schema::hasColumn('users', 'is_active')) {
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

    // Dans app/Http/Controllers/ManagementController.php
}