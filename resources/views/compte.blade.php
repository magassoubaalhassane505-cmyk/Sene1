<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SeneBI — Mon compte</title>
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/compte.css') }}" />
  </head>
  <body data-page="compte" data-server-side="1">
    <div class="app">
      @include('header-manager')
      

      <main class="container compte-page">
        <header class="compte-hero">
          <div>
            <h1>Mon compte</h1>
            <p>Profil, sécurité et préférences de notification.</p>
          </div>
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
            <h2>Sauvegarde des données</h2>
            <p class="compte-card-sub">Exportez vos données en fichier JSON, puis restaurez-les si besoin.</p>
            <div class="backup-actions">
              <button class="compte-submit-btn compte-submit-btn--secondary" type="button" id="exportDataBtn">Exporter mes données</button>
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