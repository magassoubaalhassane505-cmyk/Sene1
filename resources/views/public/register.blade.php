<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inscription - SeneBI</title>
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  </head>
  <body data-page="public-register">
    <div class="app">
      @include('public.header')
      
      <main class="container">
        <div class="page-title">
          <div>
            <h1>Créer un compte Agriculteur</h1>
            <p>Rejoignez SeneBI et optimisez votre exploitation agricole</p>
          </div>
        </div>

        <!-- Register Section -->
        <section class="register-section">
          <div class="register-grid">
            <!-- Info Card -->
            <article class="card register-info-card">
              <div class="card-header">
                <div>
                  <h3 style="margin:0; font-size:18px;">Pourquoi créer un compte ?</h3>
                  <div class="small muted">Avantages exclusifs pour les agriculteurs</div>
                </div>
                <span class="tag good">Gratuit</span>
              </div>
              <ul class="register-features">
                <li><i class="fas fa-map-marked-alt"></i> Gestion simplifiée</li>
                <li><i class="fas fa-chart-dashboard"></i> Business Intelligence</li>
                <li><i class="fas fa-exclamation-triangle"></i> Alertes intelligentes</li>
                <li><i class="fas fa-calculator"></i> Calculateur de rentabilité</li>
              </ul>
              <div class="register-note">
                <i class="fas fa-info-circle"></i>
                <p>Votre compte sera validé par un manager avant activation</p>
              </div>
            </article>

            <!-- Register Form -->
            <article class="card register-form-card">
              <div class="card-header">
                <div>
                  <h3 style="margin:0; font-size:18px;">Inscription Agriculteur</h3>
                  <div class="small muted">Remplissez le formulaire ci-dessous</div>
                </div>
                <span class="tag muted">Inscription</span>
              </div>
              <form method="POST" action="/register" class="register-form">
                @csrf
                <div class="form-row">
                  <div class="form-group">
                    <label for="name">Nom complet</label>
                    <input type="text" id="name" name="name" required placeholder="Votre nom complet" />
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="votre@email.com" />
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••" minlength="6" />
                  </div>
                  <div class="form-group">
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••" minlength="6" />
                  </div>
                </div>
                <div class="form-group">
                  <label for="company">Entreprise / Ferme (optionnel)</label>
                  <input type="text" id="company" name="company" placeholder="Nom de votre entreprise" />
                </div>
                <div class="form-group">
                  <label for="location">Localisation (optionnel)</label>
                  <input type="text" id="location" name="location" placeholder="Votre ville ou région" />
                </div>
                <div class="form-group">
                  <label for="phone">Téléphone (optionnel)</label>
                  <input type="tel" id="phone" name="phone" placeholder="+223 XX XX XX XX" />
                </div>
                <label class="checkbox-label">
                  <input type="checkbox" name="terms" required>
                  <span>J'accepte les conditions d'utilisation et la politique de confidentialité</span>
                </label>
                <button type="submit" class="btn btn-submit">Créer mon compte</button>
              </form>
              <div class="form-footer">
                <p>Vous avez déjà un compte ? <a href="/connexion">Se connecter</a></p>
                <a href="/">← Retour à l'accueil</a>
              </div>
            </article>
          </div>
        </section>

        <!-- Success Message Section (Hidden by default) -->
        @if(session('success'))
        <section class="success-section">
          <article class="card success-card">
            <div class="success-content">
              <i class="fas fa-check-circle success-icon"></i>
              <h3>Compte créé avec succès !</h3>
              <p>{{ session('success') }}</p>
              <a href="/connexion" class="btn btn-primary">Se connecter</a>
            </div>
          </article>
        </section>
        @endif
      </main>

      @include('public.footer')
    </div>

    <style>
    .register-section {
      margin: 40px 0;
    }
    
    .register-grid {
      display: grid;
      grid-template-columns: 1fr 1.5fr;
      gap: 24px;
    }
    
    .register-features {
      list-style: none;
      padding: 0;
      margin: 20px 0 0;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    
    .register-features li {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 14px;
      color: var(--text);
      font-weight: 500;
    }
    
    .register-features li i {
      color: var(--accent);
      font-size: 18px;
      width: 24px;
    }
    
    .register-note {
      margin-top: 24px;
      padding: 16px;
      background: var(--accent-soft);
      border-left: 3px solid var(--accent);
      border-radius: 0 12px 12px 0;
      display: flex;
      gap: 10px;
      align-items: flex-start;
    }
    
    .register-note i {
      color: var(--accent);
      font-size: 16px;
      margin-top: 2px;
    }
    
    .register-note p {
      margin: 0;
      font-size: 13px;
      color: var(--text);
    }
    
    .register-form-card .card-header {
      padding-bottom: 20px;
    }
    
    .register-form {
      padding: 0 24px 24px;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    
    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }
    
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }
    
    .form-group label {
      font-size: 13px;
      font-weight: 600;
      color: var(--text);
    }
    
    .register-form input {
      padding: 12px 14px;
      border: 1px solid rgba(15,23,42,0.08);
      border-radius: 12px;
      font-size: 14px;
      font-family: inherit;
      transition: border-color var(--anim-fast) ease, box-shadow var(--anim-fast) ease;
    }
    
    .register-form input:focus {
      outline: none;
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
    }
    
    .checkbox-label {
      display: flex;
      align-items: flex-start;
      gap: 8px;
      font-size: 13px;
      color: var(--text);
      cursor: pointer;
    }
    
    .btn-submit {
      background: var(--accent);
      color: #fff;
      border: none;
      padding: 14px 24px;
      border-radius: 12px;
      font-weight: 700;
      font-size: 15px;
      cursor: pointer;
      transition: all var(--anim-fast) ease;
      width: fit-content;
    }
    
    .btn-submit:hover {
      background: #047857;
      transform: translateY(-1px);
    }
    
    .form-footer {
      padding: 24px;
      text-align: center;
      display: flex;
      flex-direction: column;
      gap: 8px;
      border-top: 1px solid rgba(226, 232, 240, 0.5);
    }
    
    .form-footer p {
      margin: 0;
      font-size: 13px;
      color: var(--muted);
    }
    
    .form-footer a {
      font-size: 13px;
      color: var(--muted);
      text-decoration: none;
    }
    
    .success-section {
      margin: 40px 0;
    }
    
    .success-card {
      background: var(--accent-soft);
    }
    
    .success-content {
      text-align: center;
      padding: 40px 24px;
    }
    
    .success-icon {
      font-size: 48px;
      color: var(--accent);
      margin-bottom: 16px;
    }
    
    .success-content h3 {
      margin: 0 0 8px;
      font-size: 22px;
      color: var(--text);
    }
    
    .success-content p {
      margin: 0 0 24px;
      font-size: 14px;
      color: var(--muted);
    }
    
    @media (max-width: 1100px) {
      .register-grid {
        grid-template-columns: 1fr;
      }
    }
    
    @media (max-width: 768px) {
      .form-row {
        grid-template-columns: 1fr;
      }
    }
    </style>
  </body>
</html>