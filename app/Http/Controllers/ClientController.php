<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Recolte;
use App\Models\Intrant;
use App\Models\Visite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    // Affiche le tableau de bord de l'agriculteur (Sidi)
    public function clientDashboard() {
        $derniereVisite = Visite::where('user_id', Auth::id())->latest()->first();

        return view('client-dashboard', compact('derniereVisite'));
    }

    // Affiche le calculateur de rentabilité
    public function rentabilite() {
        $user = Auth::user();
        
        // Récupérer les récoltes du client
        $recoltes = $user->recoltes()->with('parcelle')->latest()->get();
        
        // Calculer les statistiques de rentabilité
        $totalCA = $recoltes->sum('revenu_total');
        $totalCouts = $recoltes->sum('couts_totaux');
        $totalBenefice = $recoltes->sum('benefice_net');
        
        // Calculer la marge moyenne
        $margeMoyenne = $totalCA > 0 ? ($totalBenefice / $totalCA) * 100 : 0;
        
        // Récupérer les parcelles pour le sélecteur
        $parcelles = $user->parcelles()->orderBy('nom')->get();
        
        return view('rentabilite', compact(
            'recoltes',
            'totalCA',
            'totalCouts',
            'totalBenefice',
            'margeMoyenne',
            'parcelles'
        ));
    }

    // Affiche la gestion des parcelles
    public function parcelles() {
        $parcelles = Auth::user()->parcelles()->orderBy('nom')->get();

        return view('parcelles', compact('parcelles'));
    }

    // Affiche la gestion des stocks du client
    public function stocks() {
        app(\App\Http\Controllers\ClientApiController::class)->stocksIndex();
        $stocks = Stock::where('user_id', Auth::id())->orderBy('nom')->get();

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

        return view('stocks', compact('stocks', 'intrants'));
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