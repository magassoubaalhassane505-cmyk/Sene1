<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Services - SeneBI</title>
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" />
  </head>
  <body data-page="public-services">
    <div class="app">
      @include('public.header')
      
      <main class="container">
        <div class="page-title">
          <div>
            <h1>Nos Services</h1>
            <p>Découvrez les services proposés par SeneBI pour optimiser votre exploitation agricole</p>
          </div>
        </div>

        <!-- Services Section -->
        <section class="grid cards-2">
          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Suivi des exploitations agricoles</h3>
                <div class="small muted">Surveillance en temps réel</div>
              </div>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 21s7-4.5 7-11a7 7 0 0 0-14 0c0 6.5 7 11 7 11z"/>
                  <path d="M12 10a2 2 0 1 0 0-4 2 2 0 0 0 4z"/>
                </svg>
              </div>
            </div>
            <div style="margin-top:12px;">
              <p style="font-size:13px; color:var(--muted); margin:0;">Surveillance en temps réel de vos parcelles, cultures et rendements avec des tableaux de bord interactifs.</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Gestion des intrants</h3>
                <div class="small muted">Contrôle optimal</div>
              </div>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                  <circle cx="12" cy="7" r="4"/>
                </svg>
              </div>
            </div>
            <div style="margin-top:12px;">
              <p style="font-size:13px; color:var(--muted); margin:0;">Contrôle optimal de vos stocks d'engrais et semences avec alertes automatiques de réapprovisionnement.</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Analyse de rentabilité</h3>
                <div class="small muted">Calculateur intelligent</div>
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
              <p style="font-size:13px; color:var(--muted); margin:0;">Calculateur intelligent pour évaluer la rentabilité de vos cultures et optimiser vos décisions.</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Gestion des visites terrain</h3>
                <div class="small muted">Planification</div>
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
              <p style="font-size:13px; color:var(--muted); margin:0;">Planification et suivi des visites avec recommandations personnalisées pour chaque exploitation.</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Supervision des agriculteurs</h3>
                <div class="small muted">Vue d'ensemble</div>
              </div>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                  <circle cx="9" cy="7" r="4"/>
                  <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                  <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
              </div>
            </div>
            <div style="margin-top:12px;">
              <p style="font-size:13px; color:var(--muted); margin:0;">Vue d'ensemble complète de tous les agriculteurs avec statistiques détaillées et alertes.</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Gestion intelligente des stocks</h3>
                <div class="small muted">Alertes proactives</div>
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
              <p style="font-size:13px; color:var(--muted); margin:0;">Système d'alertes intelligentes basé sur les seuils critiques pour une gestion proactive.</p>
            </div>
          </article>
        </section>

        <!-- Why Choose Us Section -->
        <section class="grid cards-2">
          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Pourquoi choisir SeneBI ?</h3>
                <div class="small muted">Une solution complète adaptée aux besoins des agriculteurs modernes</div>
              </div>
              <span class="tag good">Avantages</span>
            </div>
            <div style="display:flex; flex-direction:column; gap:12px; margin-top:16px;">
              <div style="display:flex; gap:12px; align-items:center;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; color:var(--good);">
                  <path d="M20 6L9 17l-5-5"/>
                </svg>
                <div style="flex:1; font-weight:600; color:var(--text); font-size:14px;">Facile à utiliser</div>
              </div>
              <div style="display:flex; gap:12px; align-items:center;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; color:var(--good);">
                  <path d="M20 6L9 17l-5-5"/>
                </svg>
                <div style="flex:1; font-weight:600; color:var(--text); font-size:14px;">Accessible partout</div>
              </div>
              <div style="display:flex; gap:12px; align-items:center;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; color:var(--good);">
                  <path d="M20 6L9 17l-5-5"/>
                </svg>
                <div style="flex:1; font-weight:600; color:var(--text); font-size:14px;">Support dédié</div>
              </div>
              <div style="display:flex; gap:12px; align-items:center;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; color:var(--good);">
                  <path d="M20 6L9 17l-5-5"/>
                </svg>
                <div style="flex:1; font-weight:600; color:var(--text); font-size:14px;">Sécurisé</div>
              </div>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Prêt à bénéficier de nos services ?</h3>
                <div class="small muted">Contactez-nous pour découvrir comment SeneBI peut transformer votre exploitation</div>
              </div>
              <span class="tag muted">Contact</span>
            </div>
            <div style="display:flex; gap:12px; margin-top:16px;">
              <a href="/inscription" class="btn" style="background:var(--accent); color:#fff;">Créer un compte</a>
              <a href="/contact" class="btn" style="background:transparent; color:var(--text); border:1px solid rgba(15,23,42,0.08);">Nous contacter</a>
            </div>
          </article>
        </section>
      </main>

      @include('public.footer')
    </div>
  </body>
</html>
