<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Connexion - SeneBI</title>
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  </head>
  <body data-page="public-login">
    <div class="app">
      @include('public.header')
      
      <main class="container">
        <div class="page-title">
          <div>
            <h1>Connexion</h1>
            <p>Accédez à votre espace SeneBI</p>
          </div>
        </div>

        <!-- Role Selection -->
        <section class="role-selection">
          <div class="section-header">
            <h2>Choisissez votre profil</h2>
            <p>Sélectionnez le type de compte pour accéder à la plateforme</p>
          </div>
          <div class="role-grid">
            <article class="role-card" onclick="selectRole('farmer')" id="farmerCard">
              <div class="role-icon">
                <i class="fas fa-tractor"></i>
              </div>
              <h3>Agriculteur</h3>
              <p>Espace de gestion agricole pour suivre vos parcelles, stocks et rentabilité.</p>
            </article>

            <article class="role-card" onclick="selectRole('manager')" id="managerCard">
              <div class="role-icon">
                <i class="fas fa-user-tie"></i>
              </div>
              <h3>Manager</h3>
              <p>Tableau de bord de supervision pour gérer les agriculteurs et analyser les performances.</p>
            </article>
          </div>
        </section>

        <!-- Login Form -->
        <section class="login-form-section">
          <article class="card" id="loginForm">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:18px;" id="formTitle">Connexion</h3>
                <div class="small muted">Entrez vos identifiants pour vous connecter</div>
              </div>
              <span class="tag good">Sécurisé</span>
            </div>
            <form method="POST" action="/login" class="login-form">
              @csrf
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="votre@email.com" />
              </div>
              <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required placeholder="••••••••" />
              </div>
              <div class="form-options">
                <label class="checkbox-label">
                  <input type="checkbox" name="remember">
                  <span>Se souvenir de moi</span>
                </label>
                <a href="/password/forgot">Mot de passe oublié ?</a>
              </div>
              <button type="submit" class="btn btn-submit">Se connecter</button>
            </form>
            <div class="form-footer">
              <p>Vous n'avez pas de compte ? <a href="/inscription">Créer un compte</a></p>
              <a href="/">← Retour à l'accueil</a>
            </div>
          </article>
        </section>
      </main>

      @include('public.footer')
    </div>

    <style>
    .role-selection {
      margin: 40px 0;
    }
    
    .role-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
    }
    
    .role-card {
      background: var(--card);
      border: 1px solid rgba(15,23,42,0.08);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 32px;
      text-align: center;
      cursor: pointer;
      transition: transform var(--anim-fast) ease, box-shadow var(--anim-fast) ease, border-color var(--anim-fast) ease;
    }
    
    .role-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-lg);
    }
    
    .role-icon {
      width: 72px;
      height: 72px;
      margin: 0 auto 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--accent-soft);
      color: var(--accent);
      border-radius: 50%;
      font-size: 32px;
    }
    
    .role-card h3 {
      margin: 0 0 12px;
      font-size: 18px;
      font-weight: 700;
      color: var(--text);
    }
    
    .role-card p {
      margin: 0;
      font-size: 14px;
      color: var(--muted);
      line-height: 1.5;
    }
    
    .login-form-section {
      margin: 40px 0;
    }
    
    .login-form {
      padding: 24px;
      display: flex;
      flex-direction: column;
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
    
    .login-form input {
      padding: 12px 14px;
      border: 1px solid rgba(15,23,42,0.08);
      border-radius: 12px;
      font-size: 14px;
      font-family: inherit;
      transition: border-color var(--anim-fast) ease, box-shadow var(--anim-fast) ease;
    }
    
    .login-form input:focus {
      outline: none;
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
    }
    
    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .checkbox-label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      color: var(--text);
      cursor: pointer;
    }
    
    .form-options a {
      font-size: 13px;
      color: var(--accent);
      text-decoration: none;
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
    
    @media (max-width: 1100px) {
      .role-grid {
        grid-template-columns: 1fr;
      }
    }
    </style>

    <script>
    function selectRole(role) {
      const farmerCard = document.getElementById('farmerCard');
      const managerCard = document.getElementById('managerCard');
      const loginForm = document.getElementById('loginForm');
      const formTitle = document.getElementById('formTitle');

      farmerCard.style.background = '';
      farmerCard.style.borderColor = '';
      managerCard.style.background = '';
      managerCard.style.borderColor = '';

      if (role === 'farmer') {
        farmerCard.style.background = 'var(--accent-soft)';
        farmerCard.style.borderColor = 'var(--accent)';
        formTitle.textContent = 'Connexion Agriculteur';
      } else {
        managerCard.style.background = 'var(--accent-soft)';
        managerCard.style.borderColor = 'var(--accent)';
        formTitle.textContent = 'Connexion Manager';
      }

      loginForm.style.display = 'block';
      loginForm.scrollIntoView({ behavior: 'smooth' });
    }
    </script>
  </body>
</html>