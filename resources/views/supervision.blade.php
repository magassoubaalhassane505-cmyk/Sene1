<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Supervision - SeneBI</title>
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/visual-harmony.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  </head>
  <body data-page="supervision">
    <div class="app">
      @include('header-manager')

      <main class="container">
        <div class="page-title senebi-page-transition">
          <div>
            <h1>Supervision</h1>
            <p>Surveillance des parcelles et alertes en temps réel</p>
          </div>
        </div>

        <section class="grid kpis">
          <article class="card">
            <div class="card-header">
              <p class="card-title">Utilisateurs Actifs</p>
              <div class="card-icon" aria-hidden="true">
                <i class="fas fa-users"></i>
              </div>
            </div>
            <div class="kpi-value"><span id="activeUsers">0</span></div>
            <div class="kpi-sub">
              <span>En ligne maintenant</span>
              <span class="muted">Dernières 24 heures</span>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <p class="card-title">Activités du Jour</p>
              <div class="card-icon" aria-hidden="true">
                <i class="fas fa-calendar-day"></i>
              </div>
            </div>
            <div class="kpi-value"><span id="dailyActivities">0</span></div>
            <div class="kpi-sub">
              <span>Actions enregistrées</span>
              <span class="muted">Toutes catégories</span>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <p class="card-title">Alertes Système</p>
              <div class="card-icon" aria-hidden="true">
                <i class="fas fa-bell"></i>
              </div>
            </div>
            <div class="kpi-value"><span id="systemAlerts">0</span></div>
            <div class="kpi-sub">
              <span>Alertes actives</span>
              <span class="muted">Nécessitent attention</span>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <p class="card-title">Performance</p>
              <div class="card-icon" aria-hidden="true">
                <i class="fas fa-chart-line"></i>
              </div>
            </div>
            <div class="kpi-value"><span id="performanceScore">98</span>%</div>
            <div class="kpi-sub">
              <span>Score système</span>
              <span class="muted">Basé sur metrics</span>
            </div>
          </article>
        </section>

        @if (session('status'))
          <div class="alert-banner" style="margin-bottom: 24px;">
            <div id="stockAlertText">{{ session('status') }}</div>
          </div>
        @endif

        <section class="pending-approvals" style="margin-top: 24px;">
          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px; color: #1f2937; font-weight: 600;"><i class="fas fa-user-plus"></i> Nouvelles demandes d'inscription</h3>
                <div class="small muted" style="font-size: 13px; margin-top: 4px;">Clients en attente de validation</div>
              </div>
              @if ($pendingClients->count() > 0)
                <span class="tag" style="background: #fef3c7; color: #92400e; font-weight: 600; padding: 4px 12px; border-radius: 12px; font-size: 12px;">{{ $pendingClients->count() }} à valider</span>
              @else
                <span class="tag" style="background: #d1fae5; color: #065f46; font-weight: 600; padding: 4px 12px; border-radius: 12px; font-size: 12px;">✓ À jour</span>
              @endif
            </div>

            @if ($pendingClients->count() > 0)
              <div class="table-container" style="margin-top: 0;">
                <table class="farmers-table" style="font-size: 14px;">
                  <thead>
                    <tr style="background: #f9fafb;">
                      <th style="width: 18%; color: #6b7280; font-weight: 600; padding: 12px;">Nom</th>
                      <th style="width: 18%; color: #6b7280; font-weight: 600; padding: 12px;">Email</th>
                      <th style="width: 15%; color: #6b7280; font-weight: 600; padding: 12px;">Téléphone</th>
                      <th style="width: 18%; color: #6b7280; font-weight: 600; padding: 12px;">Entreprise</th>
                      <th style="width: 13%; color: #6b7280; font-weight: 600; padding: 12px;">Date</th>
                      <th style="width: 18%; color: #6b7280; font-weight: 600; padding: 12px; text-align: center;">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($pendingClients as $client)
                      <tr style="border-bottom: 1px solid #e5e7eb; transition: background 0.2s;">
                        <td style="padding: 12px; color: #1f2937; font-weight: 500;">{{ $client->name }}</td>
                        <td style="padding: 12px; color: #4b5563;">{{ $client->email }}</td>
                        <td style="padding: 12px; color: #4b5563;">{{ $client->phone ?? '-' }}</td>
                        <td style="padding: 12px; color: #4b5563;">{{ $client->company ?? '-' }}</td>
                        <td style="padding: 12px; color: #6b7280; font-size: 13px;">{{ $client->created_at->format('d/m/Y') }}</td>
                        <td style="padding: 12px; display: flex; justify-content: center; gap: 8px;">
                          <form method="POST" action="{{ route('manager.clients.approve', $client) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn good" style="font-size: 12px; padding: 6px 14px; border-radius: 6px; cursor: pointer; transition: all 0.2s;">✓ Approuver</button>
                          </form>
                          <button type="button" class="btn warn" style="font-size: 12px; padding: 6px 14px; border-radius: 6px; cursor: pointer; transition: all 0.2s; background: #fee2e2; color: #991b1b; border: none;" onclick="openRejectModal({{ $client->id }}, '{{ $client->name }}')">✕ Rejeter</button>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div style="text-align: center; padding: 48px 24px; color: #10b981;">
                <svg style="width: 48px; height: 48px; margin: 0 auto 16px; opacity: 0.8;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/>
                </svg>
                <p style="margin: 0; font-size: 15px; font-weight: 500;">Aucune demande en attente</p>
                <p style="margin: 8px 0 0 0; font-size: 13px; color: #6b7280;">Tous les clients sont validés ✓</p>
              </div>
            @endif
          </article>
        </section>

        <section class="activity-logs" style="margin-top: 24px;">
          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Historique des actions</h3>
                <div class="small muted">Approbations, rejets et événements récents</div>
              </div>
              <span class="tag muted">Journal</span>
            </div>
            @if ($activityLogs->count() > 0)
              <div class="table-container">
                <table class="farmers-table" style="font-size: 14px;">
                  <thead>
                    <tr style="background: #f9fafb;">
                      <th style="padding: 12px;">Date</th>
                      <th style="padding: 12px;">Action</th>
                      <th style="padding: 12px;">Manager</th>
                      <th style="padding: 12px;">Client concerné</th>
                      <th style="padding: 12px;">Détail</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($activityLogs as $log)
                      <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px; color: #6b7280;">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td style="padding: 12px;">
                          @if ($log->action === 'client.approved')
                            <span class="tag" style="background:#d1fae5;color:#065f46;">Approuvé</span>
                          @elseif ($log->action === 'client.rejected')
                            <span class="tag" style="background:#fee2e2;color:#991b1b;">Rejeté</span>
                          @else
                            <span class="tag">{{ $log->action }}</span>
                          @endif
                        </td>
                        <td style="padding: 12px;">{{ optional($log->actor)->name ?? '—' }}</td>
                        <td style="padding: 12px;">{{ optional($log->targetUser)->name ?? '—' }}</td>
                        <td style="padding: 12px; color: #4b5563;">{{ $log->details ?? '—' }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <p class="small muted" style="padding: 24px; text-align: center; margin: 0;">Aucune action enregistrée pour le moment.</p>
            @endif
          </article>
        </section>

        <section class="farmers-directory" style="margin-top: 24px;">
          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Répertoire des Agriculteurs</h3>
                <div class="small muted">Vue d'ensemble de tous les agriculteurs et leur statut</div>
              </div>
              <span class="tag muted">Agriculteurs</span>
            </div>
            
            <div class="table-container">
              <table class="farmers-table">
                <thead>
                  <tr>
                    <th class="th-name">Nom de l'Agriculteur</th>
                    <th class="th-location">Localisation</th>
                    <th class="th-stock-status">Statut des Stocks</th>
                    <th class="th-risk">Risque</th>
                    <th class="th-last-activity">Dernière Activité</th>
                    <th class="th-actions">Actions</th>
                  </tr>
                </thead>
                <tbody id="farmersTableBody">
                  <!-- Les données des agriculteurs seront injectées ici par JavaScript -->
                </tbody>
              </table>
            </div>
          </article>
        </section>

        <div class="footer-note">Source : Données MySQL — Dernière mise à jour : {{ now()->format('d/m/Y H:i') }}</div>
      </main>
      @include('partials.footer-manager')
    </div>

    <!-- Modal premium pour les détails de l'agriculteur -->
    <div id="farmerModal" class="modal-overlay" hidden>
      <div class="modal-content premium-modal" style="max-width: 1100px; width: 96%; max-height: 92vh; overflow-y: auto; border-radius: 20px; padding: 0; animation: modalSlideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);">
        
        <!-- En-tête moderne avec avatar -->
        <div class="modal-header-modern" style="padding: 24px 28px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: #fff; z-index: 10; border-radius: 20px 20px 0 0;">
          <div class="modal-profile" style="display: flex; align-items: center; gap: 16px;">
            <div class="modal-avatar" id="modalAvatar" style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #10b981, #059669); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; font-weight: 800; box-shadow: 0 8px 20px rgba(16,185,129,0.25);">MD</div>
            <div class="modal-profile-info">
              <h2 id="modalFarmerName" style="margin: 0; font-size: 20px; font-weight: 800; color: #111827; line-height: 1.2;">Mamadou Diallo</h2>
              <span id="modalFarmerLocation" style="font-size: 13px; color: #6b7280; display: inline-flex; align-items: center; gap: 6px; margin-top: 2px;"><i class="fas fa-map-marker-alt" style="color: #ef4444;"></i> Bamako</span>
              <span id="modalStatusBadge" class="status-badge success" style="display: inline-flex; align-items: center; gap: 4px; margin-top: 6px; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; background: #dcfce7; color: #166534;"><i class="fas fa-check-circle"></i> Actif</span>
            </div>
          </div>
          <button class="modal-close" onclick="closeFarmerModal()" style="background: #f8fafc; border: 1px solid #e5e7eb; width: 36px; height: 36px; border-radius: 10px; cursor: pointer; color: #64748b; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
          </button>
        </div>

        <!-- Corps de la modale premium -->
        <div class="modal-body-modern" style="padding: 24px 28px;">
          
          <!-- Section KPI alignés -->
          <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px;">
            <div class="kpi-card-modern" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7); border: 1px solid #bbf7d0; border-radius: 14px; padding: 16px; text-align: center; transition: transform 0.2s ease;">
              <div style="font-size: 11px; font-weight: 700; color: #166534; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;"><i class="fas fa-chart-line" style="margin-right: 4px;"></i>Rendement</div>
              <div id="modalRendement" style="font-size: 22px; font-weight: 800; color: #14532d;">2.4 t/ha</div>
            </div>
            <div class="kpi-card-modern" style="background: linear-gradient(135deg, #eff6ff, #dbeafe); border: 1px solid #bfdbfe; border-radius: 14px; padding: 16px; text-align: center; transition: transform 0.2s ease;">
              <div style="font-size: 11px; font-weight: 700; color: #1e40af; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;"><i class="fas fa-weight-hanging" style="margin-right: 4px;"></i>Production</div>
              <div id="modalProduction" style="font-size: 22px; font-weight: 800; color: #1e3a8a;">12,450 kg</div>
            </div>
            <div class="kpi-card-modern" style="background: linear-gradient(135deg, #fffbeb, #fef3c7); border: 1px solid #fef08a; border-radius: 14px; padding: 16px; text-align: center; transition: transform 0.2s ease;">
              <div style="font-size: 11px; font-weight: 700; color: #854d0e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;"><i class="fas fa-coins" style="margin-right: 4px;"></i>Chiffre d'affaires</div>
              <div id="modalCA" style="font-size: 22px; font-weight: 800; color: #713f12;">1.8M FCFA</div>
            </div>
            <div class="kpi-card-modern" style="background: linear-gradient(135deg, #fef2f2, #fee2e2); border: 1px solid #fecaca; border-radius: 14px; padding: 16px; text-align: center; transition: transform 0.2s ease;">
              <div style="font-size: 11px; font-weight: 700; color: #991b1b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;"><i class="fas fa-piggy-bank" style="margin-right: 4px;"></i>Bénéfice net</div>
              <div id="modalBenefice" style="font-size: 22px; font-weight: 800; color: #7f1d1d;">+450K FCFA</div>
            </div>
          </div>

          <!-- Section Analyse SeneBI -->
          <div id="modalAnalysisSection" style="margin-bottom: 20px; padding: 14px 18px; background: linear-gradient(90deg, #f8fafc, #f1f5f9); border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: flex-start; gap: 12px;">
            <div class="icon-box-sm icon-box green" style="display: inline-flex;">
              <i class="fas fa-robot"></i>
            </div>
            <div>
              <div style="font-size: 12px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Analyse SeneBI</div>
              <div id="modalAnalysisText" style="font-size: 13px; color: #475569; line-height: 1.5;">Analyse en cours...</div>
            </div>
          </div>

          <!-- Onglets modernes -->
          <div style="margin-bottom: 20px;">
            <div class="tabs-nav-modern" style="display: flex; gap: 8px; border-bottom: 2px solid #e5e7eb; margin-bottom: 16px;">
              <button class="tab-btn-modern active" data-tab="stocks" style="display: flex; align-items: center; gap: 8px; padding: 10px 18px; border: none; background: transparent; color: #64748b; font-size: 13px; font-weight: 700; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all 0.2s ease;">
                <i class="fas fa-boxes" style="font-size: 12px;"></i> Stocks
              </button>
              <button class="tab-btn-modern" data-tab="cultures" style="display: flex; align-items: center; gap: 8px; padding: 10px 18px; border: none; background: transparent; color: #64748b; font-size: 13px; font-weight: 700; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all 0.2s ease;">
                <i class="fas fa-seedling" style="font-size: 12px;"></i> Cultures
              </button>
              <button class="tab-btn-modern" data-tab="visites" style="display: flex; align-items: center; gap: 8px; padding: 10px 18px; border: none; background: transparent; color: #64748b; font-size: 13px; font-weight: 700; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all 0.2s ease;">
                <i class="fas fa-calendar-check" style="font-size: 12px;"></i> Visites
              </button>
            </div>

            <div class="tab-panels-modern">
              <!-- Panel Stocks -->
              <div class="tab-panel-modern active" id="panelStocks" style="animation: fadeInSoft 0.3s ease;">
                <div id="modalStocksList" style="display: flex; flex-direction: column; gap: 8px;"></div>
              </div>
              <!-- Panel Cultures -->
              <div class="tab-panel-modern" id="panelCultures" style="display: none; animation: fadeInSoft 0.3s ease;">
                <div id="modalCulturesList" style="display: flex; flex-wrap: wrap; gap: 10px;"></div>
              </div>
              <!-- Panel Visites -->
              <div class="tab-panel-modern" id="panelVisites" style="display: none; animation: fadeInSoft 0.3s ease;">
                <div id="modalVisitesList" style="display: flex; flex-direction: column; gap: 8px;"></div>
              </div>
            </div>
          </div>

          <!-- Alertes actives -->
          <div id="modalAlertesSection" style="margin-bottom: 20px; display: none;">
            <h3 style="margin: 0 0 10px; font-size: 14px; font-weight: 700; color: #111827;"><i class="fas fa-exclamation-triangle" style="color: #ef4444; margin-right: 6px;"></i>Alertes actives</h3>
            <div id="modalAlertesList" style="display: flex; flex-direction: column; gap: 6px;"></div>
          </div>

          <!-- Mini graphiques -->
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px;">
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px;">
              <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;"><i class="fas fa-chart-bar" style="margin-right: 4px;"></i>Niveaux de stocks</div>
              <div style="height: 160px; position: relative;">
                <canvas id="stockMiniChart"></canvas>
                <div id="stockMiniChartEmpty" style="display: none; position: absolute; inset: 0; align-items: center; justify-content: center; color: #9ca3af; font-size: 12px; font-weight: 500;">Aucune donnée disponible pour cet agriculteur.</div>
              </div>
            </div>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px;">
              <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;"><i class="fas fa-chart-pie" style="margin-right: 4px;"></i>Répartition cultures</div>
              <div style="height: 160px; position: relative;">
                <canvas id="cultureMiniChart"></canvas>
                <div id="cultureMiniChartEmpty" style="display: none; position: absolute; inset: 0; align-items: center; justify-content: center; color: #9ca3af; font-size: 12px; font-weight: 500;">Aucune donnée disponible pour cet agriculteur.</div>
              </div>
            </div>
          </div>

          <!-- Actions rapides -->
          <div style="display: flex; gap: 10px; flex-wrap: wrap; padding-top: 16px; border-top: 1px solid #e5e7eb;">
            <a href="/manager/visites" class="btn" style="font-size: 13px; padding: 10px 18px; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s ease;"><i class="fas fa-calendar-plus"></i> Planifier une visite</a>
            <a href="/manager/supervision" class="btn secondary" style="font-size: 13px; padding: 10px 18px; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; background: #f8fafc; color: #1e293b; border: 1px solid #e2e8f0; transition: all 0.2s ease;"><i class="fas fa-map-marked-alt"></i> Consulter les parcelles</a>
            <button onclick="closeFarmerModal()" class="btn secondary" style="font-size: 13px; padding: 10px 18px; border-radius: 10px; background: #f8fafc; color: #1e293b; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; transition: all 0.2s ease;"><i class="fas fa-history"></i> Historique activités</button>
          </div>
        </div>
      </div>
    </div>

    <style>
      @keyframes modalSlideIn {
        from { opacity: 0; transform: translateY(30px) scale(0.96); }
        to { opacity: 1; transform: translateY(0) scale(1); }
      }
      @keyframes fadeInSoft {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
      }
      .premium-modal .kpi-card-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.06);
      }
      .tabs-nav-modern .tab-btn-modern.active {
        color: #059669;
        border-bottom-color: #10b981;
        background: linear-gradient(180deg, transparent, rgba(16,185,129,0.05));
      }
      .tabs-nav-modern .tab-btn-modern:hover {
        background: #f8fafc;
        border-radius: 8px 8px 0 0;
      }
      .status-badge.success { background: #dcfce7; color: #166534; }
      .status-badge.warning { background: #fef3c7; color: #92400e; }
      .status-badge.danger { background: #fef2f2; color: #991b1b; }
      .status-badge.info { background: #eff6ff; color: #1e40af; }
      .premium-modal .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      }
      .premium-modal .btn.secondary:hover {
        background: #e2e8f0 !important;
        border-color: #cbd5e1 !important;
      }
      .modal-close:hover {
        background: #fef2f2 !important;
        color: #dc2626 !important;
        border-color: #fecaca !important;
      }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <script src="{{ asset('assets/js/core.js') }}"></script>
    <script src="{{ asset('assets/js/auth.js') }}"></script>
    <script>
      window.SeneBI = window.SeneBI || {};
      window.SeneBI.activeClients = {{ \Illuminate\Support\Js::from(
        $activeClients->map(fn($client) => [
          'id' => $client->id,
          'name' => $client->name,
          'location' => $client->location ?? 'Non spécifié',
          'email' => $client->email,
          'stockStatus' => 'ok',
          'stockLevel' => 'Actif',
          'riskLevel' => 'Faible',
          'riskClass' => 'risk-low',
          'lastActivity' => 'Actif',
        ])->values()
      ) }};
    </script>
    <script src="{{ asset('assets/js/supervision.js') }}?v={{ time() }}"></script>

    <!-- Modal de rejet de demande d'inscription -->
    <div id="rejectModal" class="modal-overlay" hidden style="display: none;">
      <div class="modal-content" style="background: #fff; border-radius: 16px; padding: 24px; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
        <div class="modal-header-modern" style="padding: 0 0 16px 0; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
          <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #111827;">Rejeter la demande d'inscription</h3>
          <button onclick="closeRejectModal()" style="background: #f8fafc; border: 1px solid #e5e7eb; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; color: #64748b; display: flex; align-items: center; justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;"><line x1="18" y1="6" x2="6" y="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
          </button>
        </div>
        <form id="rejectForm" method="POST" style="margin-top: 20px;">
          @csrf
          <input type="hidden" name="user_id" id="rejectUserId">
          <div class="form-group">
            <label for="rejectReason" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Raison du rejet (optionnel)</label>
            <textarea id="rejectReason" name="reason" rows="4" placeholder="Indiquez la raison du rejet..." style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 14px; resize: vertical;"></textarea>
          </div>
          <div class="form-footer" style="display: flex; gap: 12px; margin-top: 20px; padding: 0; border: none; justify-content: flex-end;">
            <button type="button" onclick="closeRejectModal()" class="btn secondary" style="background: #f8fafc; color: #374151; border: 1px solid #e5e7eb;">Annuler</button>
            <button type="submit" class="btn" style="background: #ef4444; color: #fff;">Rejeter la demande</button>
          </div>
        </form>
      </div>
    </div>

    <script>
      window.openRejectModal = function(userId, userName) {
        const modal = document.getElementById('rejectModal');
        document.getElementById('rejectUserId').value = userId;
        document.getElementById('rejectForm').action = '/manager/clients/reject/' + userId;
        modal.hidden = false;
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
        document.body.style.overflow = 'hidden';
      };

      window.closeRejectModal = function() {
        const modal = document.getElementById('rejectModal');
        if (modal) {
          modal.hidden = true;
          modal.style.display = 'none';
        }
        document.body.style.overflow = '';
        document.getElementById('rejectReason').value = '';
      };

      document.addEventListener('click', function(e) {
        const modal = document.getElementById('rejectModal');
        if (e.target === modal) {
          closeRejectModal();
        }
      });

      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          const modal = document.getElementById('rejectModal');
          if (modal && modal.style.display === 'flex') {
            closeRejectModal();
          }
        }
      });
    </script>
    
    <style>
      #rejectModal { z-index: 1100; }
    </style>
  </body>
</html>