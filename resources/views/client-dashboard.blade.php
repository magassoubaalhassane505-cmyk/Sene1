<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SeneBI - Espace client</title>
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" />
    <style>
      /* Force les styles pour les KPIs du client */
      #kpiTotalHarvest, #kpiCA, #kpiHa, #kpiRend {
        font-weight: bold !important;
        color: inherit !important;
      }
      .kpi-sub span:first-child {
        color: #10b981 !important;
        font-weight: 600 !important;
      }
      
      /* Styles pour l'impression */
      @media print {
        .head-actions {
          display: none !important;
        }
        
        .modal-overlay {
          display: none !important;
        }
        
        .footer-note {
          display: none !important;
        }
        
        body {
          background: white !important;
        }
        
        .card {
          box-shadow: none !important;
          border: 1px solid #000 !important;
          break-inside: avoid;
        }
        
        .kpi-value {
          color: #000 !important;
        }
        
        .kpi-sub span {
          color: #000 !important;
        }
      }
    </style>
  </head>
  <body data-page="client-dashboard">
    <div class="app">
      @include('header-client')

      <main class="container">
        <div class="page-title">
          <div>
            <h1>Tableau de Bord Client</h1>
            <p>Suivi de vos performances agricoles et indicateurs clés.</p>
          </div>
          <div class="head-actions" style="display: flex; gap: 10px; align-items: center;">
            <button class="btn" id="clientExportBtn" type="button" style="background: #111827; color: white; border: none; border-radius: 15px; padding: 12px 20px; font-weight: 600; transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#1a1a1a'" onmouseout="this.style.backgroundColor='#111827'">Exporter le Rapport PDF</button>
          </div>
        </div>

        <div id="stockAlert" class="alert-banner">
          <div id="stockAlertText">Alerte Stock</div>
          <a class="btn danger" href="{{ route('manager.stocks') }}">Voir le stock</a>
        </div>

        <section class="grid kpis">
          <article class="card" style="cursor: pointer;" onclick="window.location.href='{{ route('client.parcelles') }}'">
            <div class="card-header">
              <p class="card-title">Total Récolté</p>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 2v20"/><path d="M7 6c2 2 2 4 0 6"/><path d="M17 6c-2 2-2 4 0 6"/><path d="M7 12c2 2 2 4 0 6"/><path d="M17 12c-2 2-2 4 0 6"/>
                </svg>
              </div>
            </div>
            <div class="kpi-value"><span id="kpiTotalHarvest" style="font-weight: bold !important;">2,450</span> <span class="muted" style="font-size:14px;font-weight:700;">kg</span></div>
            <div class="kpi-sub">
              <span style="color: #10b981 !important; font-weight: 600 !important;">+12.5% vs. période précédente</span>
              <span class="muted">Données certifiées SeneBI</span>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <p class="card-title">Chiffre d'Affaires estimé</p>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/><path d="M12 7v10"/><path d="M9.5 9.5c.6-1 4.4-1 5 0"/><path d="M9.5 14.5c.6 1 4.4-1 5 0"/>
                </svg>
              </div>
            </div>
            <div class="kpi-value"><span id="kpiCA" style="font-weight: bold !important;">1,225,000</span> <span class="muted" style="font-size:14px;font-weight:700;">FCFA</span></div>
            <div class="kpi-sub">
              <span style="color: #10b981 !important; font-weight: 600 !important;">+8.3% vs. période précédente</span>
              <span class="muted">Prix moyen × quantité récoltée</span>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <p class="card-title">Hectares Actifs</p>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 21s7-4.5 7-11a7 7 0 0 0-14 0c0 6.5 7 11 7 11z"/><path d="M12 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                </svg>
              </div>
            </div>
            <div class="kpi-value"><span id="kpiHa" style="font-weight: bold !important;">3</span> <span class="muted" style="font-size:14px;font-weight:700;">ha</span></div>
            <div class="kpi-sub">
              <span>+5.2% vs. période précédente</span>
              <span class="muted">Parcelles hors jachère</span>
            </div>
          </article>

          
          <article class="card">
            <div class="card-header">
              <p class="card-title">Rendement Moyen</p>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M3 17l6-6 4 4 7-7"/><path d="M14 8h6v6"/>
                </svg>
              </div>
            </div>
            <div class="kpi-value"><span id="kpiRend" style="font-weight: bold !important;">0.82</span> <span class="muted" style="font-size:14px;font-weight:700;">t/ha</span></div>
            <div class="kpi-sub">
              <span>+3.1% vs. période précédente</span>
              <span class="muted">Moyenne sur parcelles récoltées</span>
            </div>
          </article>
        </section>

        <div class="weather-widget">
            <div class="weather-content">
                <div class="weather-icon">☀️</div>
                <div class="weather-info">
                    <div class="weather-location">Sikasso</div>
                    <div class="weather-temp">32°C</div>
                    <div class="weather-condition">Temps sec</div>
                    <div style="font-weight: 600; margin-top: 4px; color: #10b981;">
                        @if($derniereVisite && $derniereVisite->recommandation)
                            {{ $derniereVisite->recommandation }}
                        @else
                            Conditions optimales observées sur vos parcelles. Suivez les indicateurs ci-dessous.
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <p id="dashboardInsight" class="dashboard-insight" role="status"></p>

        <section class="grid cards-2">
          <article class="card" style="min-height: 320px;">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Évolution du Prix des Céréales</h3>
                <div class="small muted">Courbe dynamique (Chart.js)</div>
              </div>
              <span class="tag muted">FCFA/kg</span>
            </div>
            <div class="cereal-price-toolbar">
              <label class="cereal-price-label" for="cerealPriceSelect">Culture affichée</label>
              <select id="cerealPriceSelect" class="cereal-price-select" aria-label="Choisir la culture pour la courbe de prix">
                <option value="Riz">Riz</option>
                <option value="Maïs">Maïs</option>
                <option value="Coton">Coton</option>
              </select>
            </div>
            <div style="height: 260px;">
              <canvas id="priceChart"></canvas>
            </div>
          </article>

          <article class="card" style="min-height: 320px;">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Distribution des Cultures</h3>
                <div class="small muted">Riz / Maïs / Coton</div>
              </div>
            </div>
            <div style="height: 260px;">
              <canvas id="cultureChart"></canvas>
            </div>
          </article>

        </section>

        <div class="footer-note">Astuce : Les prix affichés sont basés sur les cours actuels des marchés de Bamako et Sikasso.</div>
      </main>
      <div data-layout="footer"></div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <script src="{{ asset('assets/js/core.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    
    
    <!-- Script pour forcer les styles avec MutationObserver -->
    <script>
      console.log("🚀 Script KPI Styles chargé !");
      
      function applyKPIStyles() {
        console.log("⚡ Application des styles KPI...");
        
        // Forcer les styles des chiffres en gras
        const kpiElements = ['#kpiTotalHarvest', '#kpiCA', '#kpiHa', '#kpiRend'];
        kpiElements.forEach(id => {
          const el = document.querySelector(id);
          if (el) {
            el.style.cssText = 'font-weight: bold !important; color: inherit !important;';
            console.log(`✅ Style gras FORCÉ sur ${id}: ${el.textContent}`);
          } else {
            console.log(`❌ Élément ${id} non trouvé`);
          }
        });
        
        // Forcer les styles des badges verts
        const kpiSubElements = document.querySelectorAll('.kpi-sub span:first-child');
        kpiSubElements.forEach((el, index) => {
          el.style.cssText = 'color: #10b981 !important; font-weight: 600 !important;';
          console.log(`✅ Style vert FORCÉ sur badge ${index + 1}: ${el.textContent}`);
        });
        
        console.log("🎯 Styles KPI appliqués avec succès !");
      }
      
      // Attendre que la page soit complètement chargée
      window.addEventListener('load', function() {
        console.log("📄 Page complètement chargée !");
        setTimeout(applyKPIStyles, 100);
      });
      
      // Surveiller les changements sur les KPIs
      const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
          if (mutation.type === 'childList' || mutation.type === 'characterData') {
            const target = mutation.target;
            if (target.id && ['kpiTotalHarvest', 'kpiCA', 'kpiHa', 'kpiRend'].includes(target.id)) {
              console.log(`🔄 Changement détecté sur ${target.id}, réapplication des styles...`);
              setTimeout(applyKPIStyles, 50);
            }
          }
        });
      });
      
      // Démarrer l'observation après le chargement
      setTimeout(() => {
        const kpiContainer = document.querySelector('.kpis');
        if (kpiContainer) {
          observer.observe(kpiContainer, {
            childList: true,
            subtree: true,
            characterData: true
          });
          console.log("👁️ MutationObserver démarré sur les KPIs !");
        }
        
        // Application initiale
        applyKPIStyles();
      }, 200);
      
      // Fonction d'export PDF avec window.print()
      const clientExportBtn = document.getElementById('clientExportBtn');
      clientExportBtn.addEventListener('click', function() {
        window.print();
      });
    </script>
  </body>
</html>
