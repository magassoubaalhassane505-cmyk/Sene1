<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SeneBI — Dashboard</title>
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/visual-harmony.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/risk-farmers-premium.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/region-filter.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <!-- Styles pour les sections restaurées du portail manager -->
    <style>
      /* Statistiques Nationales */
      .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 16px;
      }
      
      .stat-item {
        text-align: center;
        padding: 16px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
      }
      
      .stat-number {
        font-size: 24px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 4px;
      }
      
      .stat-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
      }
      
      /* Alertes Critiques */
      .alerts-list {
        margin-top: 16px;
      }
      
      .alert-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 8px;
        border-left: 4px solid;
      }
      
      .alert-item.critical {
        background: #fef2f2;
        border-left-color: #ef4444;
      }
      
      .alert-item.warning {
        background: #fffbeb;
        border-left-color: #f59e0b;
      }
      
      .alert-item.info {
        background: #f0f9ff;
        border-left-color: #3b82f6;
      }
      
      .alert-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 12px;
        flex-shrink: 0;
      }
      
      .alert-item.critical .alert-icon {
        background: #ef4444;
        color: white;
      }
      
      .alert-item.warning .alert-icon {
        background: #f59e0b;
        color: white;
      }
      
      .alert-item.info .alert-icon {
        background: #3b82f6;
        color: white;
      }
      
      .alert-content {
        flex: 1;
      }
      
      .alert-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 2px;
      }
      
      .alert-desc {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 2px;
      }
      
      .alert-time {
        font-size: 11px;
        color: #94a3b8;
      }
      
      /* Tendances et Projections */
      .trends-section {
        margin-top: 24px;
      }
      
      .trends-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-top: 16px;
      }
      
      .trend-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
      }
      
      .trend-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
      }
      
      .trend-header h4 {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
      }
      
      .trend-value {
        font-size: 12px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 12px;
      }
      
      .trend-value.positive {
        background: #dcfce7;
        color: #166534;
      }
      
      .trend-chart {
        display: flex;
        align-items: end;
        gap: 4px;
        height: 60px;
        margin-bottom: 8px;
      }
      
      .trend-bar {
        flex: 1;
        background: linear-gradient(to top, #10b981, #34d399);
        border-radius: 2px 2px 0 0;
        min-height: 4px;
      }
      
      .trend-labels {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        color: #64748b;
      }
      
      /* Responsive */
      @media (max-width: 768px) {
        .stats-grid {
          grid-template-columns: 1fr;
        }
        
        .trends-grid {
          grid-template-columns: 1fr;
        }
}
    </style>
  </head>
  <body data-page="dashboard">
    <div class="app">
       
    @include('header-manager')

      <main class="container">
        <div class="page-title senebi-page-transition">
          <div>
            <h1>Tableau de Bord Analytique</h1>
            <p>Vue d'ensemble des performances agricoles, avec analyse des tendances et alertes opérationnelles.</p>
          </div>
          <div class="region-selector">
            <label for="regionSelect" class="region-label">Région</label>
            <select id="regionSelect" class="region-dropdown">
              <option value="all">Toutes les régions</option>
              <option value="bko">Bamako</option>
              <option value="kay">Kayes</option>
              <option value="kou">Koulikoro</option>
              <option value="seg">Ségou</option>
              <option value="sik">Sikasso</option>
              <option value="mop">Mopti</option>
              <option value="tom">Tombouctou</option>
              <option value="gao">Gao</option>
              <option value="kid">Kidal</option>
            </select>
          </div>
        </div>

        <div id="stockAlert" class="alert-banner">
          <div id="stockAlertText">Alerte Stock</div>
          <a class="btn danger" href="{{ route('manager.stocks') }}">Voir le stock</a>
        </div>

        <section class="grid kpis">
          <article class="card">
            <div class="card-header">
              <p class="card-title">Total Récolté</p>
              <div class="card-icon" aria-hidden="true">
                <i class="fas fa-seedling"></i>
              </div>
            </div>
            <div class="kpi-value"><span id="total-production">0</span> <span class="muted" style="font-size:14px;font-weight:700;">kg</span></div>
            <div class="kpi-sub">
              <span>+12.5% vs. période précédente</span>
              <span class="muted">Basé sur les saisies de récoltes</span>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <p class="card-title">Chiffre d'Affaires estimé</p>
              <div class="card-icon" aria-hidden="true">
                <i class="fas fa-chart-line"></i>
              </div>
            </div>
            <div class="kpi-value"><span id="ca-estime">0</span> <span class="muted" style="font-size:14px;font-weight:700;">M FCFA</span></div>
            <div class="kpi-sub">
              <span>+8.3% vs. période précédente</span>
              <span class="muted">Prix moyen × quantité récoltée</span>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <p class="card-title">Hectares Actifs</p>
              <div class="card-icon" aria-hidden="true">
                <i class="fas fa-map"></i>
              </div>
            </div>
            <div class="kpi-value"><span id="kpiHa">0</span> <span class="muted" style="font-size:14px;font-weight:700;">ha</span></div>
            <div class="kpi-sub">
              <span>+5.2% vs. période précédente</span>
              <span class="muted">Parcelles hors jachère</span>
            </div>
          </article>

          
          <article class="card">
            <div class="card-header">
              <p class="card-title">Agriculteurs Actifs</p>
              <div class="card-icon" aria-hidden="true">
                <i class="fas fa-users"></i>
              </div>
            </div>
            <div class="kpi-value"><span id="nombre-agriculteurs">0</span> <span class="muted" style="font-size:14px;font-weight:700;">actifs</span></div>
            <div class="kpi-sub">
              <span>+15.2% vs. période précédente</span>
              <span class="muted">Clients ayant saisi une récolte</span>
            </div>
          </article>
        </section>

        <div class="weather-widget">
            <div class="weather-content">
                <div class="weather-icon"><i class="fas fa-sun"></i></div>
                <div class="weather-info">
                    <div class="weather-location">Bamako</div>
                    <div class="weather-temp">35°C</div>
                    <div class="weather-condition">Temps sec</div>
                </div>
            </div>
        </div>

        <p id="dashboardInsight" class="dashboard-insight" role="status"></p>

        <!-- Section 1 : Cartographie -->
        <section class="dashboard-layout">
          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;"><i class="fas fa-map-marked-alt"></i> Cartographie des Récoltes</h3>
                <div class="small muted">Vue stratégique des zones agricoles</div>
              </div>
            </div>
            <div class="map-container">
              <div class="mali-map">
                <!-- Carte simplifiée du Mali avec zones -->
                <div class="map-region" data-region="bamako" style="top: 45%; left: 30%;">
                  <div class="map-point green"></div>
                  <span class="map-label">Bamako</span>
                </div>
                <div class="map-region" data-region="sikasso" style="top: 70%; left: 55%;">
                  <div class="map-point green"></div>
                  <span class="map-label">Sikasso</span>
                </div>
                <div class="map-region" data-region="segou" style="top: 50%; left: 45%;">
                  <div class="map-point red"></div>
                  <span class="map-label">Ségou</span>
                </div>
                <div class="map-region" data-region="kayes" style="top: 25%; left: 15%;">
                  <div class="map-point red"></div>
                  <span class="map-label">Kayes</span>
                </div>
                <div class="map-region" data-region="mopti" style="top: 35%; left: 60%;">
                  <div class="map-point green"></div>
                  <span class="map-label">Mopti</span>
                </div>
                <div class="map-region" data-region="tombouctou" style="top: 15%; left: 40%;">
                  <div class="map-point green"></div>
                  <span class="map-label">Tombouctou</span>
                </div>
                <div class="map-region" data-region="gao" style="top: 20%; left: 75%;">
                  <div class="map-point red"></div>
                  <span class="map-label">Gao</span>
                </div>
              </div>
              <div class="map-legend">
                <div class="legend-item">
                  <div class="legend-dot green"></div>
                  <span>Bonne récolte</span>
                </div>
                <div class="legend-item">
                  <div class="legend-dot red"></div>
                  <span>Zone à risque</span>
                </div>
              </div>
            </div>
          </article>

          <!-- Section 2 : Graphiques Comparatifs -->
          <div class="charts-section">
            <article class="card">
              <div class="card-header">
                <div>
                  <h3 style="margin:0; font-size:16px;"><i class="fas fa-chart-bar"></i> Production Totale par Culture</h3>
                  <div class="small muted">Riz vs Maïs vs Coton</div>
                </div>
                <span class="tag good">Tonnes</span>
              </div>
              <div style="height: 240px;">
                <canvas id="productionChart"></canvas>
              </div>
            </article>

            <article class="card">
              <div class="card-header">
                <div>
                  <h3 style="margin:0; font-size:16px;"><i class="fas fa-exclamation-triangle"></i> Répartition des Alertes</h3>
                  <div class="small muted">Causes principales</div>
                </div>
                <span class="tag danger">Alertes</span>
              </div>
              <div style="height: 240px;">
                <canvas id="alertsChart"></canvas>
              </div>
            </article>
          </div>
        </section>

        <!-- Section Top Agriculteurs -->
        <section class="top-farmers-section">
          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;"><i class="fas fa-trophy"></i> Top Performance</h3>
                <div class="small muted">Les 3 meilleurs rendements ce mois-ci</div>
              </div>
              <span class="tag good">Elite</span>
            </div>
            <div class="top-farmers-table">
              <div class="table-header">
                <div class="table-rank">Rang</div>
                <div class="table-name">Agriculteur</div>
                <div class="table-location">Localisation</div>
                <div class="table-performance">Rendement</div>
                <div class="table-culture">Culture</div>
              </div>
              <div class="table-body" id="topFarmersBody">
                <!-- Sera rempli dynamiquement -->
              </div>
            </div>
          </article>
        </section>

        <section style="margin-top: 24px;">
          
          <!-- Première ligne : Activités Récentes + Conseils SeneBI -->
          <div class="dashboard-row-2" style="display: grid; grid-template-columns: 65fr 35fr; gap: 16px; margin-bottom: 16px; align-items: stretch;">
            
            <!-- Activités Récentes -->
            <article class="card" style="display: flex; flex-direction: column; border-top: 3px solid #3b82f6; transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.08)';"
                     onmouseout="this.style.transform=''; this.style.boxShadow='';">
              @php
                $activities = \App\Models\Notification::latest()->limit(8)->get();
              @endphp
              <div class="card-header">
                <div style="display: flex; align-items: center; gap: 12px;">
                  <div class="icon-box-sm icon-box blue" style="display: inline-flex;">
                    <i class="fas fa-list-check"></i>
                  </div>
                  <div>
                    <h3 style="margin:0; font-size:16px; font-weight: 700; color: #111827;">Activites Recentes</h3>
                    <div class="small muted">Dernieres actions des agriculteurs</div>
                  </div>
                </div>
                <span class="badge" style="background: #eff6ff; color: #1e40af; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 700;">{{ $activities->count() }}</span>
              </div>
              <div style="flex: 1; padding: 16px;">
                @if($activities->count() > 0)
                  <div class="timeline-list" style="max-height: 340px; overflow-y: auto; padding-left: 22px; position: relative;">
                    <div style="position: absolute; left: 6px; top: 6px; bottom: 6px; width: 2px; background: #e5e7eb; border-radius: 1px;"></div>
                    @foreach($activities as $activity)
                      <div class="timeline-item" style="position: relative; padding-bottom: 14px; display: flex; gap: 12px; align-items: flex-start;">
                        <div style="position: absolute; left: -22px; top: 4px; width: 14px; height: 14px; border-radius: 50%; background: {{ $activity->level === 'danger' ? '#ef4444' : ($activity->level === 'warning' ? '#f59e0b' : '#10b981') }}; border: 3px solid #fff; box-shadow: 0 0 0 1px {{ $activity->level === 'danger' ? '#ef4444' : ($activity->level === 'warning' ? '#f59e0b' : '#10b981') }}; flex-shrink: 0;"></div>
                        <div style="flex: 1; min-width: 0;">
                          <div style="font-size: 13px; font-weight: 600; color: #111827; margin-bottom: 2px;">{{ $activity->title }}</div>
                          <div style="font-size: 12px; color: #6b7280; line-height: 1.4;">{{ $activity->message }}</div>
                          <div style="font-size: 11px; color: #9ca3af; margin-top: 2px;"><i class="fas fa-clock" style="margin-right: 4px;"></i>{{ $activity->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div style="text-align: center; color: #9ca3af; padding: 24px; font-size: 14px;">Aucune activite recente.</div>
                @endif
              </div>
            </article>

            <!-- Conseils SeneBI -->
            <article class="card" style="display: flex; flex-direction: column; border-top: 3px solid #f59e0b; transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.08)';"
                     onmouseout="this.style.transform=''; this.style.boxShadow='';">
              <div class="card-header">
                <div style="display: flex; align-items: center; gap: 12px;">
                  <div class="icon-box-sm icon-box amber" style="display: inline-flex;">
                    <i class="fas fa-robot"></i>
                  </div>
                  <div>
                    <h3 style="margin:0; font-size:16px; font-weight: 700; color: #111827;">Conseils SeneBI</h3>
                    <div class="small muted">Recommandations automatiques</div>
                  </div>
                </div>
                <span class="badge" style="background: #fffbeb; color: #92400e; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 700;">{{ !empty($recommendations) ? count($recommendations) : 0 }}</span>
              </div>
              <div style="flex: 1; padding: 16px;">
                @if(!empty($recommendations) && count($recommendations) > 0)
                  <div style="display: flex; flex-direction: column; gap: 10px; max-height: 380px; overflow-y: auto;">
                    @foreach($recommendations as $rec)
                      <div class="advice-chip" style="display: flex; gap: 10px; align-items: flex-start; padding: 12px; border-radius: 10px; border: 1px solid {{ $rec['type'] === 'danger' ? '#fecaca' : ($rec['type'] === 'warning' ? '#fef3c7' : '#bbf7d0') }}; background: {{ $rec['type'] === 'danger' ? '#fef2f2' : ($rec['type'] === 'warning' ? '#fffbeb' : '#f0fdf4') }}; transition: all 0.2s ease; cursor: default;"
                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.06)';"
                           onmouseout="this.style.transform=''; this.style.boxShadow='';">
                        <i class="fas fa-robot" style="color: {{ $rec['type'] === 'danger' ? '#ef4444' : ($rec['type'] === 'warning' ? '#f59e0b' : '#10b981') }}; margin-top: 2px; font-size: 13px;"></i>
                        <span style="font-size: 13px; color: #374151; line-height: 1.5;">{{ $rec['message'] }}</span>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div style="text-align: center; color: #9ca3af; padding: 24px; font-size: 14px;">
                    <i class="fas fa-check-circle" style="font-size: 32px; color: #10b981; margin-bottom: 8px; display: block;"></i>
Tous les indicateurs sont bons.
                   </div>
                 @endif
               </div>
             </article>
           </div>
         </section>

         <!-- Section Agriculteurs à Risque - pleine largeur -->
<!-- ============================================
              SECTION : AGRICULTEURS À RISQUE (PREMIUM)
              ============================================ -->
          <section class="risk-section--premium">
            <article class="card">
              <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
                <div class="card-header--premium">
                  <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="icon-box red" aria-hidden="true" style="width: 48px; height: 48px; font-size: 20px;">
                      <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                      <h3 class="section-title--premium">Agriculteurs à Risque</h3>
                      <p class="section-subtitle">Nécessitent une attention particulière</p>
                    </div>
                  </div>
                  <span class="risk-counter--premium">
                    <i class="fas fa-user-exclamation"></i> {{ $atRiskFarmers->count() }}
                  </span>
                </div>
              </div>
              
              <!-- Risk Filter Tabs -->
              <div class="risk-filter-tabs">
                <button class="risk-filter-tab active" data-filter="all">Tous <span class="count">{{ $atRiskFarmers->count() }}</span></button>
                <button class="risk-filter-tab" data-filter="critical">Critique <span class="count" id="count-critical">0</span></button>
                <button class="risk-filter-tab" data-filter="high">Élevé <span class="count" id="count-high">0</span></button>
                <button class="risk-filter-tab" data-filter="medium">Moyen <span class="count" id="count-medium">0</span></button>
                <button class="risk-filter-tab" data-filter="low">Faible <span class="count" id="count-low">0</span></button>
              </div>
              
              <div style="flex: 1; padding: 0 24px 24px 24px;">
                @if($atRiskFarmers->count() > 0)
                  <div class="risk-farmers-grid--premium">
                    @foreach($atRiskFarmers as $farmer)
                      @php
                        $riskCount = count($farmer['risks']);
                        if ($riskCount >= 3) {
                          $riskLevel = 'Critique';
                          $riskLevelClass = 'critical';
                          $riskProgress = 90;
                        } elseif ($riskCount >= 2) {
                          $riskLevel = 'Élevé';
                          $riskLevelClass = 'high';
                          $riskProgress = 65;
                        } elseif ($riskCount == 1) {
                          $riskLevel = 'Moyen';
                          $riskLevelClass = 'medium';
                          $riskProgress = 40;
                        } else {
                          $riskLevel = 'Faible';
                          $riskLevelClass = 'low';
                          $riskProgress = 20;
                        }
                      @endphp
                      <div class="risk-card--premium {{ $riskLevelClass }}" data-risk-level="{{ $riskLevelClass }}">
                        <!-- Card Header -->
                        <div class="risk-card-header">
                          <div class="risk-avatar--premium {{ $riskLevelClass }}">
                            {{ substr($farmer['name'], 0, 2) }}
                          </div>
                          <div class="risk-farmer-info">
                            <h4 class="risk-farmer-name">{{ $farmer['name'] }}</h4>
                            <div class="risk-farmer-location">
                              <i class="fas fa-map-marker-alt"></i> {{ $farmer['location'] ?? 'Non spécifié' }}
                            </div>
                          </div>
                        </div>
                        
                        <!-- Risk Badge -->
                        <span class="risk-badge--premium {{ $riskLevelClass }}">
                          <i class="fas fa-shield-alt"></i> {{ $riskLevel }}
                        </span>
                        
                        <!-- Risk Progress Bar -->
                        <div class="risk-progress-wrapper">
                          <div class="risk-progress-label">
                            <span>Niveau de risque</span>
                            <span>{{ $riskLevel }}</span>
                          </div>
                          <div class="risk-progress-bar">
                            <div class="risk-progress-fill {{ $riskLevelClass }}" style="width: {{ $riskProgress }}%"></div>
                          </div>
                        </div>
                        
                        <!-- Risk Causes -->
                        <div>
                          <div class="risk-causes-title">Causes principales :</div>
                          <div class="risk-causes-list">
                            @foreach($farmer['risks'] as $risk)
                              @php
                                $riskLabels = [
                                  'stock_critique' => 'Stock critique',
                                  'faible_rentabilite' => 'Faible rentabilité',
                                  'faible_activite' => 'Faible activité',
                                  'negative_profit' => 'Perte financière',
                                ];
                              @endphp
                              <span class="risk-cause-item">
                                <i class="fas fa-exclamation-circle"></i> {{ $riskLabels[$risk] ?? $risk }}
                              </span>
                            @endforeach
                          </div>
                        </div>
                        
                        <!-- Last Activity -->
                        <div class="risk-last-activity">
                          <i class="fas fa-clock"></i>
                          Dernière activité : {{ $farmer['last_visit'] ?? 'N/A' }}
                        </div>
                        
                        <!-- Actions -->
                        <div class="risk-card-actions">
                          <a href="#" class="btn-details">
                            <i class="fas fa-eye"></i> Détails
                          </a>
                          <a href="{{ url('/manager/visites') }}" class="btn-visit">
                            <i class="fas fa-calendar-check"></i> Planifier visite
                          </a>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div class="risk-empty-state">
                    <i class="fas fa-check-circle"></i>
                    <p>Aucun agriculteur à risque détecté.</p>
                  </div>
                @endif
              </div>
            </article>
          </section>

         <style>
           @media (max-width: 1024px) {
             .risk-farmers-grid { grid-template-columns: repeat(2, 1fr) !important; }
           }
           @media (max-width: 768px) {
             .risk-farmers-grid { grid-template-columns: 1fr !important; }
           }
           @media (max-width: 768px) {
             .risk-farmers-grid { grid-template-columns: 1fr !important; }
           }
          @media (max-width: 768px) {
            .dashboard-row-2 { grid-template-columns: 1fr !important; }
            .risk-farmers-grid { grid-template-columns: 1fr !important; }
          }
          @keyframes fadeUpSoft {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
          }
          .timeline-list::-webkit-scrollbar { width: 6px; }
          .timeline-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
          .btn-sm { font-size: 12px; padding: 6px 10px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; background: #f8fafc; color: #1e293b; border: 1px solid #e2e8f0; }
.btn-sm:hover { background: #e2e8f0 !important; border-color: #cbd5e1 !important; }
         </style>
 
         <div class="footer-note">Source : Données MySQL — Dernière mise à jour : {{ now()->format('d/m/Y H:i') }}</div>
       </main>
       @include('partials.footer-manager')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <script src="{{ asset('assets/js/core.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard-global.js') }}"></script>
    <script src="{{ asset('assets/js/region-filter.js') }}"></script>
    <script src="{{ asset('assets/js/notifications-simple.js') }}"></script>

    <script>
      window.SeneBI = window.SeneBI || {};
      window.SeneBI.topClients = {{ \Illuminate\Support\Js::from($topClients) }};
      window.SeneBI_DASHBOARD = {
        totalHarvestKg: {{ $totalQuantiteRecoltee ?? 0 }},
        hectaresActifs: {{ round($totalSurface ?? 0, 1) }},
        rendementMoyen: {{ $totalSurface > 0 ? round(($totalQuantiteRecoltee ?? 0) / $totalSurface, 2) : 0 }},
        totalCA: {{ $totalCA ?? 0 }},
        activeClients: {{ $activeClients ?? 0 }},
        culturesLabels: {{ \Illuminate\Support\Js::from($productionParCulture->pluck('culture')) }},
        culturesData: {{ \Illuminate\Support\Js::from($productionParCulture->pluck('total_quantite')) }},
        alertesParType: {{ \Illuminate\Support\Js::from($alertesParType) }},
      };
    </script>
    
    <!-- Script d'automatisation du Dashboard Manager -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const data = window.SeneBI_DASHBOARD || {};

        function updateKPIDisplay(elementId, value) {
          const element = document.getElementById(elementId);
          if (element) {
            element.textContent = typeof value === 'number' ? value.toLocaleString('fr-FR') : value;
          }
        }

        updateKPIDisplay('total-production', data.totalHarvestKg || 0);
        updateKPIDisplay('ca-estime', (data.totalCA || 0) / 1000000);
        updateKPIDisplay('kpiHa', data.hectaresActifs || 0);
        updateKPIDisplay('nombre-agriculteurs', data.activeClients || 0);

        const insightEl = document.getElementById('dashboardInsight');
        if (insightEl && data.totalHarvestKg > 0) {
          insightEl.textContent = `Rendement moyen de ${data.rendementMoyen} t/ha sur ${data.totalHarvestKg.toLocaleString('fr-FR')} kg récoltés et ${data.hectaresActifs} ha actifs.`;
        } else if (insightEl) {
          insightEl.textContent = "Les indicateurs sont cohérents : surveillez l'évolution des prix par culture et la répartition des surfaces.";
        }

        const productionCtx = document.getElementById('productionChart');
        if (productionCtx && window.Chart) {
          const labels = data.culturesLabels || [];
          const values = data.culturesData || [];
          new Chart(productionCtx, {
            type: 'bar',
            data: {
              labels,
              datasets: [{
                label: 'Production (kg)',
                data: values,
                backgroundColor: '#059669',
                borderRadius: 6,
                barPercentage: 0.6,
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: { legend: { display: false } },
              scales: { x: { grid: { display: false } }, y: { beginAtZero: true, ticks: { font: { size: 10 } } } }
            }
          });
        }

        const alertsCtx = document.getElementById('alertsChart');
        if (alertsCtx && window.Chart) {
          const labels = [];
          const values = [];
          const colors = [];
          const map = data.alertesParType || {};
          if (map.stock_critique) { labels.push('Stock critique'); values.push(map.stock_critique); colors.push('#ef4444'); }
          if (map.faible_rentabilite) { labels.push('Faible rentabilité'); values.push(map.faible_rentabilite); colors.push('#f59e0b'); }
          if (map.faible_activite) { labels.push('Faible activité'); values.push(map.faible_activite); colors.push('#3b82f6'); }
          new Chart(alertsCtx, {
            type: 'doughnut',
            data: {
              labels,
              datasets: [{ data: values, backgroundColor: colors, borderWidth: 0, hoverOffset: 6 }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              cutout: '60%',
              plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 }, padding: 12 } } }
            }
          });
        }
      });
    </script>
    
    <!-- Fonctions JavaScript pour les sections restaurées -->
    <script>
      // Initialisation des données dynamiques au chargement
      window.addEventListener('load', function() {
        console.log("🚀 Initialisation du dashboard manager avec données complètes");
        
        // Remplir le tableau Top Performance avec les clients réels
        const topFarmersBody = document.getElementById('topFarmersBody');
        if (topFarmersBody) {
          const topClients = window.SeneBI?.topClients || [];

          if (topClients.length === 0) {
            topFarmersBody.innerHTML = `
              <div class="table-row" style="text-align: center; padding: 24px; color: #6b7280;">
                Aucun agriculteur avec des données de performance
              </div>
            `;
          } else {
            topFarmersBody.innerHTML = topClients.map((client, index) => `
              <div class="table-row">
                <div class="table-rank">${index + 1}</div>
                <div class="table-name">${client.name}</div>
                <div class="table-location">${client.location}</div>
                <div class="table-performance">${client.rendement.toFixed(1)} t/ha</div>
                <div class="table-culture">${client.culture}</div>
              </div>
            `).join('');
          }
          console.log("✅ Top Performance rempli avec les clients réels");
        }
        
        // Animation des barres de tendances
        const trendBars = document.querySelectorAll('.trend-bar');
        trendBars.forEach((bar, index) => {
          const height = bar.style.height;
          bar.style.height = '0';
          setTimeout(() => {
            bar.style.transition = 'height 0.8s ease';
            bar.style.height = height;
          }, 100 * (index + 1));
        });
        
        // Risk Farmers Filter
        const filterTabs = document.querySelectorAll('.risk-filter-tab');
        const riskCards = document.querySelectorAll('.risk-card--premium');
        
        // Calculate risk counts
        const counts = { critical: 0, high: 0, medium: 0, low: 0 };
        riskCards.forEach(card => {
          const level = card.dataset.riskLevel;
          if (counts.hasOwnProperty(level)) counts[level]++;
        });
        document.getElementById('count-critical').textContent = counts.critical;
        document.getElementById('count-high').textContent = counts.high;
        document.getElementById('count-medium').textContent = counts.medium;
        document.getElementById('count-low').textContent = counts.low;
        
        // Animate risk cards on load
        riskCards.forEach((card, index) => {
          card.style.opacity = '0';
          card.style.transform = 'translateY(16px)';
          setTimeout(() => {
            card.style.transition = 'all 0.4s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
          }, 50 * index);
        });
        
        filterTabs.forEach(tab => {
          tab.addEventListener('click', function() {
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            riskCards.forEach(card => {
              if (filter === 'all') {
                card.style.display = '';
              } else {
                const cardLevel = card.dataset.riskLevel;
                card.style.display = (cardLevel === filter) ? '' : 'none';
              }
            });
          });
        });
        
        console.log("✅ Dashboard manager entièrement initialisé");
      });
    </script>
  </body>
</html>