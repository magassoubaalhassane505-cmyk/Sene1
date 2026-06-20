<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Business Intelligence - SeneBI</title>
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" />
  </head>
  <body data-page="public-bi">
    <div class="app">
      @include('public.header')
      
      <main class="container">
        <div class="page-title">
          <div>
            <h1>Business Intelligence</h1>
            <p>Découvrez comment SeneBI utilise la Business Intelligence pour optimiser vos décisions agricoles</p>
          </div>
        </div>

        <!-- BI Overview Section -->
        <section class="grid cards-2">
          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Indicateurs clés de performance</h3>
                <div class="small muted">KPI en temps réel</div>
              </div>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M3 13h8V3H3v10z"/>
                  <path d="M13 21h8V11h-8v10z"/>
                  <path d="M13 3h8v6h-8V3z"/>
                  <path d="M3 17h8v4H3v-4z"/>
                </svg>
              </div>
            </div>
            <div style="margin-top:12px;">
              <p style="font-size:13px; color:var(--muted); margin:0;">Suivez en temps réel les KPI essentiels de votre exploitation : rendements, coûts, marges, et plus encore.</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Tableaux de bord analytiques</h3>
                <div class="small muted">Visualisations interactives</div>
              </div>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                  <path d="M12 7v10"/>
                  <path d="M9.5 9.5c.6-1 4.4-1 5 0"/>
                  <path d="M9.5 14.5c.6 1 4.4-1 5 0"/>
                </svg>
              </div>
            </div>
            <div style="margin-top:12px;">
              <p style="font-size:13px; color:var(--muted); margin:0;">Visualisations interactives pour comprendre rapidement l'état de votre exploitation et identifier les tendances.</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Aide à la prise de décision</h3>
                <div class="small muted">Recommandations intelligentes</div>
              </div>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"/>
                  <path d="M12 16v-4"/>
                  <path d="M12 8h.01"/>
                </svg>
              </div>
            </div>
            <div style="margin-top:12px;">
              <p style="font-size:13px; color:var(--muted); margin:0;">Recommandations intelligentes basées sur l'analyse de vos données historiques et actuelles.</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Alertes intelligentes</h3>
                <div class="small muted">Notifications proactives</div>
              </div>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 2L3 7v5c0 5 3.5 9.4 9 10 5.5-.6 9-5 9-10V7l-9-5z"/>
                  <path d="M12 22V8"/>
                  <path d="M4.93 4.93L12 12l7.07-7.07"/>
                </svg>
              </div>
            </div>
            <div style="margin-top:12px;">
              <p style="font-size:13px; color:var(--muted); margin:0;">Notifications proactives pour les stocks critiques, les rendements anormaux et les opportunités d'optimisation.</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Suivi des performances</h3>
                <div class="small muted">Analyse comparative</div>
              </div>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 2v20"/>
                  <path d="M7 6c2 2 2 4 0 6"/>
                  <path d="M17 6c-2 2-2 4 0 6"/>
                  <path d="M7 12c2 2 2 4 0 6"/>
                  <path d="M17 12c-2 2-2 4 0 6"/>
                </svg>
              </div>
            </div>
            <div style="margin-top:12px;">
              <p style="font-size:13px; color:var(--muted); margin:0;">Analyse comparative de vos performances sur plusieurs saisons pour mesurer votre progression.</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Analyse prédictive</h3>
                <div class="small muted">Prévisions basées sur les données</div>
              </div>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 21s7-4.5 7-11a7 7 0 0 0-14 0c0 6.5 7 11 7 11z"/>
                  <path d="M12 10a2 2 0 1 0 0-4 2 2 0 0 0 4z"/>
                </svg>
              </div>
            </div>
            <div style="margin-top:12px;">
              <p style="font-size:13px; color:var(--muted); margin:0;">Prévisions basées sur vos données pour anticiper les besoins et optimiser la planification.</p>
            </div>
          </article>
        </section>

        <!-- Demo Statistics Section -->
        <section class="grid kpis">
          <article class="card">
            <div class="card-header">
              <p class="card-title">Rendement moyen par hectare</p>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 21s7-4.5 7-11a7 7 0 0 0-14 0c0 6.5 7 11 7 11z"/>
                  <path d="M12 10a2 2 0 1 0 0-4 2 2 0 0 0 4z"/>
                </svg>
              </div>
            </div>
            <div class="kpi-value">4.2 t/ha</div>
            <div class="kpi-sub">
              <span style="color:var(--good);">+12% vs saison dernière</span>
              <span class="muted">Performance</span>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <p class="card-title">Marge bénéficiaire moyenne</p>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                  <path d="M12 7v10"/>
                  <path d="M9.5 9.5c.6-1 4.4-1 5 0"/>
                  <path d="M9.5 14.5c.6 1 4.4-1 5 0"/>
                </svg>
              </div>
            </div>
            <div class="kpi-value">34%</div>
            <div class="kpi-sub">
              <span style="color:var(--good);">+8% vs saison dernière</span>
              <span class="muted">Rentabilité</span>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <p class="card-title">Efficacité des intrants</p>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                  <circle cx="12" cy="7" r="4"/>
                </svg>
              </div>
            </div>
            <div class="kpi-value">89%</div>
            <div class="kpi-sub">
              <span style="color:var(--good);">+5% vs saison dernière</span>
              <span class="muted">Optimisation</span>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <p class="card-title">Taux de satisfaction clients</p>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                  <circle cx="9" cy="7" r="4"/>
                  <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                  <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
              </div>
            </div>
            <div class="kpi-value">96%</div>
            <div class="kpi-sub">
              <span style="color:var(--good);">+3% vs saison dernière</span>
              <span class="muted">Satisfaction</span>
            </div>
          </article>
        </section>

        <!-- Features Detail Section -->
        <section class="grid cards-2">
          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Fonctionnalités BI avancées</h3>
                <div class="small muted">Des outils d'analyse professionnelle adaptés aux besoins des agriculteurs</div>
              </div>
              <span class="tag good">Premium</span>
            </div>
            <div style="display:flex; flex-direction:column; gap:12px; margin-top:16px;">
              <div style="display:flex; gap:12px; align-items:center;">
                <div style="width:32px; height:32px; background:var(--accent-soft); color:var(--accent); border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:14px; flex-shrink:0;">1</div>
                <div style="flex:1;"><div style="font-weight:700; color:var(--text); font-size:14px;">Tableau de bord en temps réel</div><div class="small muted">Accédez à toutes vos métriques importantes en un coup d'œil</div></div>
              </div>
              <div style="display:flex; gap:12px; align-items:center;">
                <div style="width:32px; height:32px; background:var(--accent-soft); color:var(--accent); border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:14px; flex-shrink:0;">2</div>
                <div style="flex:1;"><div style="font-weight:700; color:var(--text); font-size:14px;">Rapports détaillés</div><div class="small muted">Générez des rapports complets sur vos performances</div></div>
              </div>
              <div style="display:flex; gap:12px; align-items:center;">
                <div style="width:32px; height:32px; background:var(--accent-soft); color:var(--accent); border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:14px; flex-shrink:0;">3</div>
                <div style="flex:1;"><div style="font-weight:700; color:var(--text); font-size:14px;">Comparaison multi-saisons</div><div class="small muted">Analysez l'évolution de vos performances</div></div>
              </div>
              <div style="display:flex; gap:12px; align-items:center;">
                <div style="width:32px; height:32px; background:var(--accent-soft); color:var(--accent); border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:14px; flex-shrink:0;">4</div>
                <div style="flex:1;"><div style="font-weight:700; color:var(--text); font-size:14px;">Alertes personnalisées</div><div class="small muted">Configurez des seuils personnalisés pour recevoir des alertes</div></div>
              </div>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Prêt à exploiter la puissance de la BI ?</h3>
                <div class="small muted">Créez votre compte maintenant et commencez à prendre des décisions basées sur les données</div>
              </div>
              <span class="tag muted">Action</span>
            </div>
            <div style="display:flex; gap:12px; margin-top:16px;">
              <a href="/inscription" class="btn" style="background:var(--accent); color:#fff;">Créer un compte</a>
              <a href="/contact" class="btn" style="background:transparent; color:var(--text); border:1px solid rgba(15,23,42,0.08);">Demander une démo</a>
            </div>
          </article>
        </section>
      </main>

      @include('public.footer')
    </div>
  </body>
</html>
