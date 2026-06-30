<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PdfExportService
{
    protected $user;

    public function __construct($user = null)
    {
        $this->user = $user ?? Auth::user();
    }

    /**
     * Generate premium PDF report for rentability page
     */
    public function generateRentabiliteReport()
    {
        $data = $this->getRentabiliteData();
        
        // Embed charts as base64
        $charts = $this->generateCharts($data);
        $data['charts'] = $charts;
        
        // Load logo as base64
        $data['logoBase64'] = $this->getLogoBase64();
        
        // Generate HTML content
        $html = view('pdf.rentabilite-premium', $data)->render();
        
        return $html;
    }

    protected function getRentabiliteData()
    {
        $recoltes = $this->user->recoltes()->with('parcelle')->latest()->get();

        // Main metrics
        $totalCA = $recoltes->sum('revenu_total') ?? 0;
        $totalCouts = $recoltes->sum('couts_totaux') ?? 0;
        $totalBenefice = $totalCA - $totalCouts;
        $margeMoyenne = $totalCA > 0 ? ($totalBenefice / $totalCA) * 100 : 0;

        // Monthly averages (last 3 months)
        $now = now();
        $dateTroisMois = $now->copy()->subMonths(3)->startOfMonth();
        $caTroisDerniersMois = DB::table('recoltes')
            ->where('user_id', $this->user->id)
            ->where('date_recolte', '>=', $dateTroisMois)
            ->sum('revenu_total') ?? 0;
        $beneficeTroisDerniersMois = DB::table('recoltes')
            ->where('user_id', $this->user->id)
            ->where('date_recolte', '>=', $dateTroisMois)
            ->sum('benefice_net') ?? 0;
        $moyenneMensuelleCA = $caTroisDerniersMois > 0 ? $caTroisDerniersMois / 3 : 0;
        $moyenneMensuelleBenefice = $beneficeTroisDerniersMois > 0 ? $beneficeTroisDerniersMois / 3 : 0;

        // Financial trend
        $tendanceFinanciere = $moyenneMensuelleCA > 0 && $totalCA > 0
            ? (($totalCA - $moyenneMensuelleCA) / $moyenneMensuelleCA) * 100
            : 0;

        // Projections
        $projections = [];
        $growth = 1 + ($tendanceFinanciere / 100) * 0.5;
        for ($i = 1; $i <= 3; $i++) {
            $projections[] = [
                'mois' => $now->copy()->addMonths($i)->format('M Y'),
                'revenu' => round($moyenneMensuelleCA * $growth),
                'benefice' => round($moyenneMensuelleBenefice * $growth),
            ];
        }

        // Historical comparisons
        $anneeActuelle = $now->year;
        $anneePrecedente = $anneeActuelle - 1;
        $saisonActuelle = $this->user->saison ?? (string)$anneeActuelle;
        $saisonPrecedente = (string)($saisonActuelle - 1);

        $caSaisonActuelle = DB::table('recoltes')
            ->where('user_id', $this->user->id)
            ->where('saison', $saisonActuelle)
            ->sum('revenu_total') ?? 0;
        $caSaisonPrecedente = DB::table('recoltes')
            ->where('user_id', $this->user->id)
            ->where('saison', $saisonPrecedente)
            ->sum('revenu_total') ?? 0;
        $varSaisonCA = $caSaisonPrecedente > 0
            ? (($caSaisonActuelle - $caSaisonPrecedente) / $caSaisonPrecedente) * 100
            : 0;

        $caAnneeActuelle = DB::table('recoltes')
            ->where('user_id', $this->user->id)
            ->whereYear('date_recolte', $anneeActuelle)
            ->sum('revenu_total') ?? 0;
        $caAnneePrecedente = DB::table('recoltes')
            ->where('user_id', $this->user->id)
            ->whereYear('date_recolte', $anneePrecedente)
            ->sum('revenu_total') ?? 0;
        $varAnneeCA = $caAnneePrecedente > 0
            ? (($caAnneeActuelle - $caAnneePrecedente) / $caAnneePrecedente) * 100
            : 0;

        // Cost breakdown
        $coutsEngrais = DB::table('intrant_consommes')
            ->join('stocks', 'intrant_consommes.stock_id', '=', 'stocks.id')
            ->where('intrant_consommes.user_id', $this->user->id)
            ->where('stocks.type', 'Engrais')
            ->sum(DB::raw('quantite_consommee * cout_unitaire')) ?? 0;
        $coutsSemences = DB::table('intrant_consommes')
            ->join('stocks', 'intrant_consommes.stock_id', '=', 'stocks.id')
            ->where('intrant_consommes.user_id', $this->user->id)
            ->where('stocks.type', 'Semence')
            ->sum(DB::raw('quantite_consommee * cout_unitaire')) ?? 0;
        $coutsMainOeuvre = DB::table('intrant_consommes')
            ->join('stocks', 'intrant_consommes.stock_id', '=', 'stocks.id')
            ->where('intrant_consommes.user_id', $this->user->id)
            ->where('stocks.type', 'like', '%main%')
            ->sum(DB::raw('quantite_consommee * cout_unitaire')) ?? 0;
        $coutsTransport = DB::table('intrant_consommes')
            ->join('stocks', 'intrant_consommes.stock_id', '=', 'stocks.id')
            ->where('intrant_consommes.user_id', $this->user->id)
            ->where('stocks.type', 'like', '%transport%')
            ->sum(DB::raw('quantite_consommee * cout_unitaire')) ?? 0;
        $coutsHerbicides = DB::table('intrant_consommes')
            ->join('stocks', 'intrant_consommes.stock_id', '=', 'stocks.id')
            ->where('intrant_consommes.user_id', $this->user->id)
            ->where('stocks.type', 'like', '%herbicide%')
            ->sum(DB::raw('quantite_consommee * cout_unitaire')) ?? 0;
        $coutsAutres = max(0, $totalCouts - ($coutsEngrais + $coutsSemences + $coutsMainOeuvre + $coutsTransport + $coutsHerbicides));

        // Top 3 cultures
        $topCultures = DB::table('recoltes')
            ->where('user_id', $this->user->id)
            ->selectRaw('culture, SUM(benefice_net) as benefice_total, SUM(revenu_total) as chiffre_affaires')
            ->where('benefice_net', '>', 0)
            ->groupBy('culture')
            ->orderByDesc('benefice_total')
            ->limit(3)
            ->get();

        // Harvest details
        $harvestsDetail = $recoltes->take(10)->map(function($r) {
            return [
                'date' => $r->date_recolte->format('d/m/Y'),
                'parcelle' => $r->parcelle->nom ?? 'N/A',
                'culture' => $r->culture,
                'surface' => number_format($r->parcelle->surface ?? 0, 2) . ' ha',
                'quantite' => number_format($r->quantite, 0, ',', ' ') . ' kg',
                'revenu' => number_format($r->revenu_total, 0, ',', ' ') . ' FCFA',
                'benefice' => number_format($r->benefice_net, 0, ',', ' ') . ' FCFA',
            ];
        });

        // Performance badge
        $badgePerformance = $this->calculatePerformanceBadge($totalCA, $totalBenefice, $margeMoyenne);

        // AI recommendations
        $recommendations = $this->generateAIRecommendations($margeMoyenne);

        return [
            'totalCA' => $totalCA,
            'totalCouts' => $totalCouts,
            'totalBenefice' => $totalBenefice,
            'margeMoyenne' => $margeMoyenne,
            'moyenneMensuelleCA' => $moyenneMensuelleCA,
            'moyenneMensuelleBenefice' => $moyenneMensuelleBenefice,
            'tendanceFinanciere' => $tendanceFinanciere,
            'projections' => $projections,
            'caSaisonActuelle' => $caSaisonActuelle,
            'caSaisonPrecedente' => $caSaisonPrecedente,
            'saisonActuelle' => $saisonActuelle ?? now()->year,
            'saisonPrecedente' => $saisonPrecedente ?? (now()->year - 1),
            'anneeActuelle' => $now->year,
            'anneePrecedente' => $anneePrecedente,
            'varSaisonCA' => $varSaisonCA,
            'caAnneeActuelle' => $caAnneeActuelle,
            'caAnneePrecedente' => $caAnneePrecedente,
            'varAnneeCA' => $varAnneeCA,
            'coutsEngrais' => $coutsEngrais,
            'coutsSemences' => $coutsSemences,
            'coutsHerbicides' => $coutsHerbicides,
            'coutsMainOeuvre' => $coutsMainOeuvre,
            'coutsTransport' => $coutsTransport,
            'coutsAutres' => $coutsAutres,
            'topCultures' => $topCultures,
            'harvestsDetail' => $harvestsDetail,
            'badgePerformance' => $badgePerformance,
            'recommendations' => $recommendations,
            'generatedAt' => now()->format('d/m/Y H:i'),
            'userName' => $this->user->name ?? 'Utilisateur SeneBI',
        ];
    }

    protected function generateCharts($data)
    {
        // We'll generate chart images via separate canvas rendering
        // For now return placeholder - actual charts would be rendered server-side
        return [
            'costsDonut' => null,
            'projectionTimeline' => null,
        ];
    }

    protected function getLogoBase64()
    {
        $logoPath = public_path('assets/img/logo_senebi.png');
        if (file_exists($logoPath)) {
            return 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }
        return null;
    }

    protected function calculatePerformanceBadge($ca, $benefice, $marge)
    {
        if ($benefice < 0) {
            return ['label' => 'Exploitation en perte', 'class' => 'perte', 'icon' => 'fa-frown'];
        } elseif ($marge >= 30) {
            return ['label' => 'Excellente rentabilité', 'class' => 'excellente', 'icon' => 'fa-trophy'];
        } elseif ($marge >= 15) {
            return ['label' => 'Bonne rentabilité', 'class' => 'bonne', 'icon' => 'fa-thumbs-up'];
        } elseif ($marge >= 5) {
            return ['label' => 'Rentabilité moyenne', 'class' => 'moyenne', 'icon' => 'fa-chart-line'];
        } else {
            return ['label' => 'Faible rentabilité', 'class' => 'faible', 'icon' => 'fa-exclamation-triangle'];
        }
    }

    protected function generateAIRecommendations($marge)
    {
        $recommendations = [];
        
        if ($marge < 0) {
            $recommendations = [
                ['icon' => 'fa-exclamation-triangle', 'text' => 'Votre exploitation est en perte. Réduisez les coûts d\'intrants ou augmentez les prix de vente.'],
                ['icon' => 'fa-lightbulb', 'text' => 'Analysez les parcelles les moins rentables et envisagez un reclassement.'],
                ['icon' => 'fa-hand-holding-seedling', 'text' => 'Contactez un conseiller agricole pour optimisation de vos pratiques.'],
            ];
        } elseif ($marge < 10) {
            $recommendations = [
                ['icon' => 'fa-chart-line', 'text' => 'Marge faible: optimisez les coûts d\'engrais et semences.'],
                ['icon' => 'fa-calendar-check', 'text' => 'Planifiez vos interventions pour réduire les coûts.'],
                ['icon' => 'fa-seedling', 'text' => 'Envisagez des cultures à plus forte valeur ajoutée.'],
            ];
        } elseif ($marge < 20) {
            $recommendations = [
                ['icon' => 'fa-thumbs-up', 'text' => 'Bonne rentabilité: surveillez vos coûts pour maintenir celle-ci.'],
                ['icon' => 'fa-piggy-bank', 'text' => 'Mettez de côté des excédents pour les investissements futurs.'],
                ['icon' => 'fa-chart-pie', 'text' => 'Diversifiez vos cultures pour réduire la dépendance.'],
            ];
        } else {
            $recommendations = [
                ['icon' => 'fa-trophy', 'text' => 'Excellente rentabilité! Votre stratégie est remarquable.'],
                ['icon' => 'fa-rocket', 'text' => 'Réinvestissez pour étendre votre exploitation.'],
                ['icon' => 'fa-graduation-cap', 'text' => 'Partagez votre expertise avec d\'autres agriculteurs.'],
            ];
        }

        return $recommendations;
    }
}