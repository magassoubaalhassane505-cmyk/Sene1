<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Parcelle;
use App\Models\Stock;
use App\Models\Recolte;
use App\Models\Visite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    // Affiche la page d'accueil principale du site (Fichier: index.blade.php)
    public function index() {
        return view('index');
    }

    // Affiche le dashboard manager avec des statistiques réelles
    public function managerDashboard() {
        // Statistiques utilisateurs
        $totalUsers = User::count();
        $activeClients = User::where('role', 'client')
            ->where('is_active', true)
            ->where('status', 'approved')
            ->count();
        $pendingClients = User::where('role', 'client')
            ->where('is_active', false)
            ->count();
        
        // Statistiques parcelles
        $totalParcelles = Parcelle::count();
        $totalSurface = Parcelle::sum('surface');
        
        // Statistiques stocks
        $totalStocks = Stock::count();
        $criticalStocks = Stock::whereColumn('quantite_actuelle', '<=', 'seuil_critique')->count();
        
        // Statistiques récoltes
        $totalRecoltes = Recolte::count();
        
        // Dernières visites
        $recentVisits = Visite::with('user')->latest()->take(5)->get();
        
        // Alertes stocks critiques
        $stockAlerts = Stock::with('user')
            ->whereColumn('quantite_actuelle', '<=', 'seuil_critique')
            ->latest()
            ->take(5)
            ->get();

        // Top Performance - Clients approuvés avec leurs récoltes
        $topClients = User::where('role', 'client')
            ->where('is_active', true)
            ->where('status', 'approved')
            ->with('recoltes')
            ->get()
            ->map(function ($client) {
                $recoltes = $client->recoltes;
                $totalQuantite = $recoltes->sum('quantite');
                $totalSurface = $recoltes->sum(function ($recolte) {
                    return $recolte->parcelle ? $recolte->parcelle->surface : 0;
                });

                // Calculer le rendement (quantité / surface)
                $rendement = $totalSurface > 0 ? ($totalQuantite / $totalSurface) : 0;

                // Trouver la culture principale
                $culturePrincipale = $recoltes->groupBy('culture')
                    ->map(fn($group) => $group->sum('quantite'))
                    ->sortDesc()
                    ->keys()
                    ->first() ?? 'N/A';

                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'location' => $client->location ?? 'Non spécifié',
                    'rendement' => $rendement,
                    'culture' => $culturePrincipale,
                ];
            })
            ->sortByDesc('rendement')
            ->take(3)
            ->values();

        return view('dashboard', compact(
            'totalUsers',
            'activeClients',
            'pendingClients',
            'totalParcelles',
            'totalSurface',
            'totalStocks',
            'criticalStocks',
            'totalRecoltes',
            'recentVisits',
            'stockAlerts',
            'topClients'
        ));
    }
}