<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 15mm;
            size: A4;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #0f172a;
            line-height: 1.6;
        }
        
        .page {
            page-break-after: always;
            position: relative;
            padding-bottom: 20mm;
        }
        
        .page:last-child {
            page-break-after: auto;
        }
        
        /* Header Styles */
        .header {
            margin-bottom: 10mm;
            padding-bottom: 5mm;
            border-bottom: 2px solid #059669;
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5mm;
        }
        
        .logo {
            height: 20mm;
        }
        
        .header-title {
            text-align: right;
        }
        
        .header-title h1 {
            margin: 0;
            font-size: 18pt;
            font-weight: bold;
            color: #059669;
        }
        
        .header-title .subtitle {
            margin: 0;
            font-size: 10pt;
            color: #64748b;
        }
        
        .header-info {
            display: flex;
            justify-content: flex-end;
            gap: 10mm;
            font-size: 9pt;
            color: #64748b;
        }
        
        /* Cover Page */
        .cover {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 240mm;
            text-align: center;
        }
        
        .cover .logo {
            margin-bottom: 15mm;
        }
        
        .cover h1 {
            font-size: 28pt;
            color: #059669;
            margin-bottom: 10mm;
        }
        
        .cover .module-badge {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            padding: 8px 24px;
            border-radius: 24px;
            font-weight: bold;
            color: #047857;
            margin-bottom: 15mm;
        }
        
        .cover .report-info {
            background: #f8fafc;
            padding: 15px;
            border-radius: 12px;
            border-left: 4px solid #059669;
        }
        
        /* KPI Cards */
        .kpi-grid {
            display: flex;
            gap: 5mm;
            margin-bottom: 10mm;
            flex-wrap: wrap;
        }
        
        .kpi-card {
            flex: 1;
            min-width: 40mm;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px;
            text-align: center;
        }
        
        .kpi-label {
            font-size: 8pt;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 5px;
        }
        
        .kpi-value {
            font-size: 14pt;
            font-weight: bold;
            color: #0f172a;
        }
        
        .kpi-value.positive {
            color: #16a34a;
        }
        
        .kpi-value.negative {
            color: #ef4444;
        }
        
        /* Section Titles */
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #059669;
            margin: 10mm 0 5mm 0;
            padding-bottom: 3px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5mm;
            font-size: 9pt;
        }
        
        th {
            background: #059669;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        tr:nth-child(even) td {
            background: #f8fafc;
        }
        
        /* Charts Placeholder */
        .chart-container {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            padding: 15mm;
            text-align: center;
            margin-bottom: 5mm;
            min-height: 80mm;
        }
        
        .chart-placeholder {
            color: #64748b;
            font-style: italic;
        }
        
        /* Recommendations */
        .recommendation {
            background: #ffffff;
            border-left: 3px solid #059669;
            padding: 10px;
            margin-bottom: 5mm;
            border-radius: 0 8px 8px 0;
        }
        
        .recommendation-icon {
            display: inline-block;
            width: 20px;
            text-align: center;
            margin-right: 8px;
            color: #059669;
        }
        
        /* Footer */
        .footer {
            position: fixed;
            bottom: 10mm;
            left: 15mm;
            right: 15mm;
            text-align: center;
            font-size: 8pt;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }
        
        .footer .page-number:after {
            content: "Page " counter(page);
        }
        
        /* Badge */
        .performance-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 999px;
            font-weight: bold;
            font-size: 10pt;
        }
        
        .badge-excellente {
            background: #d1fae5;
            color: #047857;
        }
        
        .badge-bonne {
            background: #fef3c7;
            color: #d97706;
        }
        
        .badge-moyenne {
            background: #dbeafe;
            color: #1d4ed8;
        }
        
        .badge-faible {
            background: #fee2e2;
            color: #dc2626;
        }
        
        .badge-perte {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <!-- Cover Page -->
    <div class="page cover">
        @if($logoBase64)
        <img src="{{ $logoBase64 }}" alt="SeneBI" class="logo">
        @endif
        <h1>Rapport d'Analyse Financière</h1>
        <div class="module-badge">Module Rentabilité</div>
        <div class="report-info">
            <p><strong>Date de génération :</strong> {{ $generatedAt }}</p>
            <p><strong>Généré par :</strong> {{ $userName }}</p>
            <p><strong>Document confidentiel SeneBI</strong></p>
        </div>
    </div>

    <!-- Page 2 - KPI Summary -->
    <div class="page">
        <div class="header">
            <div class="header-top">
                @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="SeneBI" class="logo">
                @endif
                <div class="header-title">
                    <h1>Résumé Exécutif</h1>
                    <p class="subtitle">SeneBI Business Intelligence Agricole</p>
                </div>
            </div>
            <div class="header-info">
                <span><strong>Date :</strong> {{ $generatedAt }}</span>
                <span><strong>Agriculteur :</strong> {{ $userName }}</span>
            </div>
        </div>

        <h2 class="section-title">Indicateurs Clés de Performance</h2>
        
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-label">Chiffre d'Affaires</div>
                <div class="kpi-value positive">{{ number_format($totalCA, 0, ',', ' ') }} FCFA</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Coûts Intrants</div>
                <div class="kpi-value negative">{{ number_format($totalCouts, 0, ',', ' ') }} FCFA</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Bénéfice Net</div>
                <div class="kpi-value {{ $totalBenefice >= 0 ? 'positive' : 'negative' }}">{{ number_format($totalBenefice, 0, ',', ' ') }} FCFA</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Marge</div>
                <div class="kpi-value {{ $margeMoyenne >= 0 ? 'positive' : 'negative' }}">{{ number_format($margeMoyenne, 1, ',', ' ') }}%</div>
            </div>
        </div>

        <div style="text-align: center; margin: 10mm 0;">
            <span class="performance-badge badge-{{ $badgePerformance['class'] }}">
                <i class="fas {{ $badgePerformance['icon'] }}"></i> {{ $badgePerformance['label'] }}
            </span>
        </div>

        <h2 class="section-title">Prévisions Financières</h2>
        <table>
            <thead>
                <tr>
                    <th>Mois</th>
                    <th>Revenu Estimé</th>
                    <th>Bénéfice Estimé</th>
                    <th>Tendance</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Moyenne mensuelle</td>
                    <td>{{ number_format($moyenneMensuelleCA, 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($moyenneMensuelleBenefice, 0, ',', ' ') }} FCFA</td>
                    <td>{{ $tendanceFinanciere >= 0 ? 'Hausse' : 'Baisse' }} ({{ number_format(abs($tendanceFinanciere), 1) }}%)</td>
                </tr>
                @foreach($projections as $proj)
                <tr>
                    <td>{{ $proj['mois'] }}</td>
                    <td>{{ number_format($proj['revenu'], 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($proj['benefice'], 0, ',', ' ') }} FCFA</td>
                    <td>{{ $tendanceFinanciere >= 0 ? 'Positif' : 'Négatif' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Page 3 - Historical Comparison -->
    <div class="page">
        <div class="header">
            <div class="header-top">
                @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="SeneBI" class="logo">
                @endif
                <div class="header-title">
                    <h1>Comparaison Historique</h1>
                    <p class="subtitle">SeneBI Business Intelligence Agricole</p>
                </div>
            </div>
        </div>

        <h2 class="section-title">Comparaison Saisonnière</h2>
        <table>
            <thead>
                <tr>
                    <th>Période</th>
                    <th>CA</th>
                    <th>Variation</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Saison {{ $saisonActuelle ?? 'actuelle' }}</td>
                    <td>{{ number_format($caSaisonActuelle, 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($varSaisonCA, 1, ',', ' ') }}%</td>
                </tr>
                <tr>
                    <td>Saison {{ $saisonPrecedente ?? 'précédente' }}</td>
                    <td>{{ number_format($caSaisonPrecedente, 0, ',', ' ') }} FCFA</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>

        <h2 class="section-title">Comparaison Annuelle</h2>
        <table>
            <thead>
                <tr>
                    <th>Année</th>
                    <th>CA</th>
                    <th>Variation</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $anneeActuelle }}</td>
                    <td>{{ number_format($caAnneeActuelle, 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($varAnneeCA, 1, ',', ' ') }}%</td>
                </tr>
                <tr>
                    <td>{{ $anneePrecedente }}</td>
                    <td>{{ number_format($caAnneePrecedente, 0, ',', ' ') }} FCFA</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>

        <h2 class="section-title">Répartition des Coûts</h2>
        <table>
            <thead>
                <tr>
                    <th>Catégorie</th>
                    <th>Montant FCFA</th>
                    <th>% du Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Engrais</td>
                    <td>{{ number_format($coutsEngrais, 0, ',', ' ') }}</td>
                    <td>{{ $totalCouts > 0 ? number_format(($coutsEngrais / $totalCouts) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Semences</td>
                    <td>{{ number_format($coutsSemences, 0, ',', ' ') }}</td>
                    <td>{{ $totalCouts > 0 ? number_format(($coutsSemences / $totalCouts) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Herbicides</td>
                    <td>{{ number_format($coutsHerbicides, 0, ',', ' ') }}</td>
                    <td>{{ $totalCouts > 0 ? number_format(($coutsHerbicides / $totalCouts) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Main-d'œuvre</td>
                    <td>{{ number_format($coutsMainOeuvre, 0, ',', ' ') }}</td>
                    <td>{{ $totalCouts > 0 ? number_format(($coutsMainOeuvre / $totalCouts) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Transport</td>
                    <td>{{ number_format($coutsTransport, 0, ',', ' ') }}</td>
                    <td>{{ $totalCouts > 0 ? number_format(($coutsTransport / $totalCouts) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Autres</td>
                    <td>{{ number_format($coutsAutres, 0, ',', ' ') }}</td>
                    <td>{{ $totalCouts > 0 ? number_format(($coutsAutres / $totalCouts) * 100, 1) : 0 }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Page 4 - Top Cultures -->
    <div class="page">
        <div class="header">
            <div class="header-top">
                @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="SeneBI" class="logo">
                @endif
                <div class="header-title">
                    <h1>Top Cultures Rentables</h1>
                    <p class="subtitle">SeneBI Business Intelligence Agricole</p>
                </div>
            </div>
        </div>

        <h2 class="section-title">Top 3 des Cultures les Plus Rentables</h2>
        <table>
            <thead>
                <tr>
                    <th>Rang</th>
                    <th>Culture</th>
                    <th>Bénéfice Total</th>
                    <th>CA Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topCultures as $index => $culture)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $culture->culture ?? $culture['culture'] }}</td>
                    <td>{{ number_format($culture->benefice_total ?? $culture['benefice_total'], 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($culture->chiffre_affaires ?? $culture['chiffre_affaires'], 0, ',', ' ') }} FCFA</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h2 class="section-title">Détails des Récoltes Récentes</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Parcelle</th>
                    <th>Culture</th>
                    <th>Surface</th>
                    <th>Quantité</th>
                    <th>Revenu</th>
                    <th>Bénéfice</th>
                </tr>
            </thead>
            <tbody>
                @foreach($harvestsDetail as $harvest)
                <tr>
                    <td>{{ $harvest['date'] }}</td>
                    <td>{{ $harvest['parcelle'] }}</td>
                    <td>{{ $harvest['culture'] }}</td>
                    <td>{{ $harvest['surface'] }}</td>
                    <td>{{ $harvest['quantite'] }}</td>
                    <td>{{ $harvest['revenu'] }}</td>
                    <td>{{ $harvest['benefice'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Page 5 - AI Recommendations -->
    <div class="page">
        <div class="header">
            <div class="header-top">
                @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="SeneBI" class="logo">
                @endif
                <div class="header-title">
                    <h1>Recommandations IA</h1>
                    <p class="subtitle">SeneBI Business Intelligence Agricole</p>
                </div>
            </div>
        </div>

        <h2 class="section-title">Analyses et Recommandations</h2>
        
        @foreach($recommendations as $rec)
        <div class="recommendation">
            <span class="recommendation-icon"><i class="fas {{ $rec['icon'] }}"></i></span>
            {{ $rec['text'] }}
        </div>
        @endforeach

        <h2 class="section-title">Conclusion</h2>
        <p>
            Ce rapport a été généré automatiquement par SeneBI à partir des données réelles de votre exploitation.
            Les indicateurs présentés permettent d'évaluer la performance financière actuelle et de planifier
            les actions à venir pour améliorer votre rentabilité.
        </p>
        <p>
            Pour plus d'analyses détaillées et des graphiques interactifs, consultez votre 
            tableau de bord en ligne sur la plateforme SeneBI.
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <span>Généré automatiquement par SeneBI - Business Intelligence Agricole | {{ $generatedAt }}</span>
    </div>
</body>
</html>