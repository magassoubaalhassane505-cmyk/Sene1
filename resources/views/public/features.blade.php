<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Fonctionnalités - SeneBI</title>
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" />
  </head>
  <body data-page="public-features">
    <div class="app">
      @include('public.header')
      
      <main class="container">
        <div class="page-title">
          <div>
            <h1>Fonctionnalités</h1>
            <p>Découvrez toutes les fonctionnalités de SeneBI pour optimiser votre exploitation agricole</p>
          </div>
        </div>

        <!-- Agriculteurs Features -->
        <section class="grid cards-2">
          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Gestion des parcelles</h3>
                <div class="small muted">Suivi des champs et cultures</div>
              </div>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 21s7-4.5 7-11a7 7 0 0 0-14 0c0 6.5 7 11 7 11z"/>
                  <path d="M12 10a2 2 0 1 0 0-4 2 2 0 0 0 4z"/>
                </svg>
              </div>
            </div>
            <div style="margin-top:12px;">
              <p style="font-size:13px; color:var(--muted); margin:0;">Suivez vos champs, cultures et surfaces en temps réel avec une carte interactive.</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Gestion des stocks</h3>
                <div class="small muted">Contrôle des intrants</div>
              </div>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                  <circle cx="12" cy="7" r="4"/>
                </svg>
              </div>
            </div>
            <div style="margin-top:12px;">
              <p style="font-size:13px; color:var(--muted); margin:0;">Contrôlez vos intrants (engrais, semences) avec alertes automatiques de seuil critique.</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Calculateur de rentabilité</h3>
                <div class="small muted">Analyse financière</div>
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
              <p style="font-size:13px; color:var(--muted); margin:0;">Analysez vos coûts et revenus pour optimiser la rentabilité de vos récoltes.</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Historique des visites</h3>
                <div class="small muted">Suivi terrain</div>
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
              <p style="font-size:13px; color:var(--muted); margin:0;">Consultez l'historique complet des visites terrain et recommandations.</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Gestion du profil</h3>
                <div class="small muted">Personnalisation</div>
              </div>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                  <circle cx="12" cy="7" r="4"/>
                </svg>
              </div>
            </div>
            <div style="margin-top:12px;">
              <p style="font-size:13px; color:var(--muted); margin:0;">Personnalisez vos informations et sécurisez votre compte.</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Alertes de seuil critique</h3>
                <div class="small muted">Notifications</div>
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
              <p style="font-size:13px; color:var(--muted); margin:0;">Recevez des notifications automatiques quand vos stocks atteignent le seuil critique.</p>
            </div>
          </article>
        </section>

        <!-- Managers Features -->
        <section class="grid cards-2">
          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Dashboard BI</h3>
                <div class="small muted">Analytique avancée</div>
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
              <p style="font-size:13px; color:var(--muted); margin:0;">Tableau de bord analytique avec indicateurs clés de performance en temps réel.</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Gestion des clients</h3>
                <div class="small muted">Supervision</div>
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
              <p style="font-size:13px; color:var(--muted); margin:0;">Supervisez, approuvez et gérez les comptes des agriculteurs.</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Gestion des stocks</h3>
                <div class="small muted">Surveillance globale</div>
              </div>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                  <circle cx="12" cy="7" r="4"/>
                </svg>
              </div>
            </div>
            <div style="margin-top:12px;">
              <p style="font-size:13px; color:var(--muted); margin:0;">Surveillance globale des stocks avec alertes urgentes et planification.</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Planification des visites</h3>
                <div class="small muted">Organisation</div>
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
              <p style="font-size:13px; color:var(--muted); margin:0;">Organisez et suivez les visites terrain avec système d'alertes intelligentes.</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Catalogue des intrants</h3>
                <div class="small muted">Gestion produits</div>
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
              <p style="font-size:13px; color:var(--muted); margin:0;">Gérez les prix et types de produits agricoles (engrais, semences).</p>
            </div>
          </article>

          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Supervision des exploitations</h3>
                <div class="small muted">Vue d'ensemble</div>
              </div>
              <div class="card-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 21s7-4.5 7-11a7 7 0 0 0-14 0c0 6.5 7 11 7 11z"/>
                  <path d="M12 10a2 2 0 1 0 0-4 2 2 0 0 0 4z"/>
                </svg>
              </div>
            </div>
            <div style="margin-top:12px;">
              <p style="font-size:13px; color:var(--muted); margin:0;">Vue d'ensemble de toutes les exploitations avec statistiques détaillées.</p>
            </div>
          </article>
        </section>

        <!-- CTA Section -->
        <section class="grid cards-2">
          <article class="card" style="grid-column: 1 / -1;">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Prêt à découvrir SeneBI ?</h3>
                <div class="small muted">Créez votre compte maintenant et commencez à optimiser votre exploitation</div>
              </div>
              <span class="tag good">Gratuit</span>
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
