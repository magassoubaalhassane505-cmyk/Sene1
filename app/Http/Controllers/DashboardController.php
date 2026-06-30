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
        $totalQuantiteRecoltee = Recolte::sum('quantite');
        $totalCA = Recolte::sum('revenu_total');

        // Dernières visites
        $recentVisits = Visite::with('user')->latest()->take(5)->get();

        // Alertes stocks critiques
        $stockAlerts = Stock::with('user')
            ->whereColumn('quantite_actuelle', '<=', 'seuil_critique')
            ->latest()
            ->take(5)
            ->get();

        // Production par culture
        $productionParCulture = Recolte::select('culture', \Illuminate\Support\Facades\DB::raw('SUM(quantite) as total_quantite'))
            ->groupBy('culture')
            ->orderByDesc('total_quantite')
            ->get();

        // Répartition des alertes
        $alertesParType = [
            'stock_critique' => Stock::whereColumn('quantite_actuelle', '<=', 'seuil_critique')->count(),
            'faible_rentabilite' => User::where('role', 'client')
                ->where('is_active', true)
                ->where('status', 'approved')
                ->whereHas('recoltes', function ($query) {
                    $query->where('benefice_net', '<', 0);
                })
                ->distinct()
                ->count(),
            'faible_activite' => User::where('role', 'client')
                ->where('is_active', true)
                ->where('status', 'approved')
                ->whereHas('visites', function ($query) {
                    $query->where('date_visite', '<', now()->subMonths(2));
                })
                ->distinct()
                ->count(),
        ];

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

                $rendement = $totalSurface > 0 ? ($totalQuantite / $totalSurface) : 0;

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

        // Agriculteurs à risque
        $atRiskFarmers = User::where('role', 'client')
            ->where('is_active', true)
            ->where('status', 'approved')
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
                if ($criticalStocks > 0) {
                    $risks[] = 'stock_critique';
                }
                $totalBenefice = $client->recoltes->sum('benefice_net');
                if ($totalBenefice < 0) {
                    $risks[] = 'faible_rentabilite';
                }
                $lastVisit = $client->visites->sortByDesc('date_visite')->first();
                if (!$lastVisit || $lastVisit->date_visite->lt(now()->subMonths(2))) {
                    $risks[] = 'faible_activite';
                }

                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'location' => $client->location ?? 'Non spécifié',
                    'risks' => $risks,
                    'critical_stocks' => $criticalStocks,
                    'last_visit' => $lastVisit ? $lastVisit->date_visite->format('d/m/Y') : 'Jamais',
                ];
            })
            ->values();

        // Activités récentes
        $recentActivities = \App\Models\Notification::where('user_id', 'like', '%')
            ->latest()
            ->limit(10)
            ->get();

        // Conseils intelligents
        $recommendations = [];
        if ($pendingClients > 0) {
            $recommendations[] = ['type' => 'warning', 'message' => "{$pendingClients} agriculteur(s) en attente d'approbation"];
        }
        if ($criticalStocks > 0) {
            $recommendations[] = ['type' => 'danger', 'message' => "{$criticalStocks} stock(s) critique(s) détecté(s) chez les agriculteurs"];
        }
        if ($atRiskFarmers->count() > 0) {
            $recommendations[] = ['type' => 'danger', 'message' => "{$atRiskFarmers->count()} agriculteur(s) nécessitent une attention particulière"];
        }
        if ($totalRecoltes === 0) {
            $recommendations[] = ['type' => 'info', 'message' => "Aucune récolte enregistrée cette saison"];
        }

        return view('dashboard', compact(
            'totalUsers',
            'activeClients',
            'pendingClients',
            'totalParcelles',
            'totalSurface',
            'totalStocks',
            'criticalStocks',
            'totalRecoltes',
            'totalQuantiteRecoltee',
            'totalCA',
            'recentVisits',
            'stockAlerts',
            'topClients',
            'atRiskFarmers',
            'recentActivities',
            'recommendations',
            'productionParCulture',
            'alertesParType'
        ));
    }
}