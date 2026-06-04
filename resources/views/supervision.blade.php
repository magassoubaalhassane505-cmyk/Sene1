<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Supervision - SeneBI</title>
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" />
    
    <!-- Styles pour la navigation active -->
    <style>
      .manager-nav a.active {
        background: #dcfce7;
        color: #14532d;
        font-weight: 600;
        border-left: 3px solid #10b981;
        border-radius: 0 8px 8px 0;
        transition: all 0.2s ease;
      }
      
      .manager-nav a.active:hover {
        background: #bbf7d0;
        border-left-color: #059669;
      }
    </style>
  </head>
  <body data-page="supervision">
    <div class="app">
      @include('header-manager')

      <main class="container">
        <div class="page-title">
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
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
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
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
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
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4"/><path d="M12 17h.01"/>
                </svg>
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
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M3 17l6-6 4 4 7-7"/><path d="M14 8h6v6"/>
                </svg>
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
                <h3 style="margin:0; font-size:16px; color: #1f2937; font-weight: 600;">Nouvelles demandes d'inscription</h3>
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
      </main>
      <div data-layout="footer"></div>
    </div>

    <!-- Modal pour les détails de l'agriculteur -->
    <div id="farmerModal" class="modal-overlay" hidden>
      <div class="modal-content">
        <!-- En-tête de la modal -->
        <div class="modal-header">
          <div class="modal-title">
            <h2 id="modalFarmerName">Mamadou Diallo</h2>
            <span id="modalFarmerLocation" class="modal-location">Bamako</span>
          </div>
          <button class="modal-close" onclick="closeFarmerModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>

        <!-- Corps de la modal avec deux colonnes -->
        <div class="modal-body">
          <!-- Colonne gauche : Jauge de Stock -->
          <div class="modal-left">
            <div class="modal-section">
              <h3>Jauge de Stock</h3>
              <div class="stock-chart-container">
                <canvas id="stockChart" width="200" height="200"></canvas>
              </div>
              <div class="stock-details">
                <h4>État des stocks détaillés</h4>
                <div class="stock-list" id="stockList">
                  <!-- Sera rempli dynamiquement -->
                </div>
              </div>
              <div class="chart-description">
                <p class="chart-text">Analyse basée sur les dernières données saisies par l'agriculteur le <span id="stockDate">15 avril 2026</span></p>
              </div>
            </div>
          </div>

          <!-- Colonne droite : Performance de Récolte -->
          <div class="modal-right">
            <div class="modal-section">
              <h3>Performance de Récolte</h3>
              <div class="performance-chart-container">
                <canvas id="performanceChart" width="300" height="200"></canvas>
              </div>
              <div class="chart-description">
                <p class="chart-text">Comparaison : Récolte Réelle vs Prévisions pour la saison 2026</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <script src="{{ asset('assets/js/core.js') }}"></script>
    <script src="{{ asset('assets/js/auth.js') }}"></script>
    <script src="{{ asset('assets/js/supervision.js') }}"></script>

    <script>
      // Passer les clients actifs au JavaScript
      window.SeneBI = window.SeneBI || {};
      window.SeneBI.activeClients = {{ \Illuminate\Support\Js::from(
        $activeClients->map(fn($client) => [
          'id' => $client->id,
          'name' => $client->name,
          'location' => $client->location ?? 'Non spécifié',
          'email' => $client->email,
        ])->values()
      ) }};
    </script>
    
    <!-- Modal de rejet -->
    <div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
      <div style="background: white; border-radius: 8px; padding: 32px; max-width: 400px; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
        <h2 style="margin: 0 0 16px 0; font-size: 18px;">Rejeter le compte</h2>
        <p id="rejectClientName" style="color: #64748b; margin: 0 0 24px 0;"></p>
        
        <form id="rejectForm" method="POST" style="margin-bottom: 16px;">
          @csrf
          <label for="rejectReason" style="display: block; margin-bottom: 8px; font-weight: 500;">Raison du rejet (optionnel)</label>
          <textarea id="rejectReason" name="reason" style="width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 4px; font-family: inherit; font-size: 14px; resize: vertical; min-height: 80px;" placeholder="Exemple: Données incomplètes, doublon, etc."></textarea>
          
          <div style="display: flex; gap: 8px; justify-content: flex-end; margin-top: 24px;">
            <button type="button" onclick="closeRejectModal()" class="btn secondary" style="padding: 8px 16px;">Annuler</button>
            <button type="submit" class="btn warn" style="padding: 8px 16px;">Confirmer le rejet</button>
          </div>
        </form>
      </div>
    </div>
    
    <!-- JavaScript pour la navigation active et modale rejet -->
    <script>
      function openRejectModal(clientId, clientName) {
        document.getElementById('rejectClientName').textContent = 'Êtes-vous sûr de rejeter ' + clientName + ' ?';
        document.getElementById('rejectForm').action = '/manager/clients/reject/' + clientId;
        document.getElementById('rejectModal').style.display = 'flex';
      }

      function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
        document.getElementById('rejectReason').value = '';
      }

      // Fermer la modale si on clique en dehors
      document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
          closeRejectModal();
        }
      });

      document.addEventListener('DOMContentLoaded', function() {
        // Détecter la page active dans la navigation
        const navLinks = document.querySelectorAll('.manager-nav a');
        const currentPath = window.location.pathname;

        navLinks.forEach(link => {
          const linkPath = new URL(link.href, window.location.origin).pathname;
          if (currentPath === linkPath) {
            link.classList.add('active');
          }
        });
      });
    </script>
  </body>
</html>