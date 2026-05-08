<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SeneBI — Mon compte</title>
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/compte.css') }}" />
  </head>
  <body data-page="compte">
    <div class="app">
      

      <main class="container compte-page">
        <header class="compte-hero">
          <div>
            <h1>Mon compte</h1>
            <p>Profil, sécurité et préférences de notification.</p>
          </div>
          <a class="compte-back-btn" href="#" id="compteBackLink">Retour</a>
        </header>

        <div class="compte-grid">
          <section class="card compte-card compte-card--profile">
            <div class="compte-card-head">
              <div class="compte-avatar" id="profileAvatar" aria-hidden="true">?</div>
              <div>
                <h2>Profil</h2>
                <p class="compte-card-sub">Vos informations et votre rôle dans SeneBI.</p>
              </div>
            </div>
            <dl class="compte-dl">
              <div><dt>Nom</dt><dd id="fieldName">—</dd></div>
              <div><dt>Email</dt><dd id="fieldEmail">—</dd></div>
              <div><dt>Entreprise</dt><dd id="fieldCompany">—</dd></div>
              <div><dt>Rôle</dt><dd><span class="role-pill" id="fieldRole">—</span></dd></div>
            </dl>
          </section>

          <section class="card compte-card">
            <h2>Sécurité</h2>
            <p class="compte-card-sub">Changer votre mot de passe (stockage local de démo).</p>
            <form class="compte-form" id="passwordForm" novalidate>
              <div class="compte-field">
                <label for="currentPassword">Mot de passe actuel</label>
                <input id="currentPassword" type="password" autocomplete="current-password" required />
              </div>
              <div class="compte-field">
                <label for="newPassword">Nouveau mot de passe</label>
                <input id="newPassword" type="password" autocomplete="new-password" required minlength="6" />
              </div>
              <div class="compte-field">
                <label for="confirmPassword">Confirmer le nouveau</label>
                <input id="confirmPassword" type="password" autocomplete="new-password" required minlength="6" />
              </div>
              <button class="compte-submit-btn" type="submit">Mettre à jour le mot de passe</button>
              <p class="form-feedback" id="passwordFeedback" aria-live="polite"></p>
            </form>
          </section>

          <section class="card compte-card compte-card--wide">
            <h2>Paramètres du compte</h2>
            <p class="compte-card-sub">Préférences de notification (simulation — données enregistrées sur cet appareil).</p>
            <form class="compte-prefs" id="prefsForm">
              <label class="pref-row">
                <input type="checkbox" id="prefEmail" name="emailAlerts" />
                <span class="pref-body">
                  <strong>Notifications par e-mail</strong>
                  <span class="pref-desc">Résumés et alertes envoyés à votre adresse.</span>
                </span>
              </label>
              <label class="pref-row">
                <input type="checkbox" id="prefStock" name="stockAlerts" />
                <span class="pref-body">
                  <strong>Alertes stock</strong>
                  <span class="pref-desc">Seuils critiques sur les intrants.</span>
                </span>
              </label>
              <label class="pref-row">
                <input type="checkbox" id="prefParcel" name="parcelReminders" />
                <span class="pref-body">
                  <strong>Rappels parcelles &amp; récoltes</strong>
                  <span class="pref-desc">Échéances et activités terrain.</span>
                </span>
              </label>
              <label class="pref-row">
                <input type="checkbox" id="prefDigest" name="weeklyDigest" />
                <span class="pref-body">
                  <strong>Résumé hebdomadaire</strong>
                  <span class="pref-desc">Synthèse KPI une fois par semaine.</span>
                </span>
              </label>
              <nav class="nav manager-nav">
                <a href="{{ route('manager.dashboard') }}">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 13h8V3H3v10z"/>
                    <path d="M13 21h8V11h-8v10z"/>
                    <path d="M13 3h8v6h-8V3z"/>
                    <path d="M3 17h8v4H3v-4z"/>
                  </svg>
                  <span>Dashboard</span>
                </a>
                <a href="{{ route('manager.supervision') }}">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                  </svg>
                  <span>Supervision</span>
                </a>
                <a href="{{ route('manager.visites') }}">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                  </svg>
                  <span>Visites</span>
                </a>
                <a href="{{ route('manager.catalogue') }}">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                    <path d="M12 22V8"/>
                    <path d="M4.93 4.93L12 12l7.07-7.07"/>
                  </svg>
                  <span>Catalogue</span>
                </a>
              </nav>
              <label class="pref-row">
                <input type="checkbox" id="prefSms" name="smsAlerts" />
                <span class="pref-body">
                  <strong>SMS (démo)</strong>
                  <span class="pref-desc">Simulation — non connecté à un opérateur.</span>
                </span>
              </label>
              <div class="compte-form-actions">
                <button class="compte-submit-btn compte-submit-btn--secondary" type="submit">Enregistrer les préférences</button>
              </div>
              <p class="form-feedback" id="prefsFeedback" aria-live="polite"></p>
            </form>
          </section>

          <section class="card compte-card compte-card--wide">
            <h2>Sauvegarde des données</h2>
            <p class="compte-card-sub">Exportez vos données en fichier JSON, puis restaurez-les si besoin.</p>
            <div class="backup-actions">
              <button class="compte-submit-btn compte-submit-btn--secondary" type="button" id="exportDataBtn">Exporter mes donnees</button>
              <label class="compte-backup-import">
                <input type="file" id="importDataFile" accept=".json,application/json" />
                <span>Importer un fichier de sauvegarde</span>
              </label>
            </div>
            <p class="form-feedback" id="backupFeedback" aria-live="polite"></p>
          </section>
        </div>
      </main>
      <div data-layout="footer"></div>
    </div>

    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <script src="{{ asset('assets/js/core.js') }}"></script>
    <script src="{{ asset('assets/js/compte.js') }}"></script>
  </body>
</html>
