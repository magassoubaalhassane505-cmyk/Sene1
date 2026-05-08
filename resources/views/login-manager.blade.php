<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SeneBI - Connexion Manager</title>
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}" />
  </head>
  <body data-page="auth-login">
    <main class="auth-shell">
      <section class="login-frame">
        <aside class="login-photo" aria-hidden="true"></aside>
        <div class="login-panel">
          <div class="login-brand">
            <img
              class="login-brand-image"
              src="{{ asset('assets/img/logo.png') }}"
              alt="Logo SeneBI"
            />
          </div>

          <h1>SeneBI: Business Intelligence Agricole Mali</h1>
          <p class="login-subtitle">Votre plateforme de gestion securisee</p>

          <form id="loginForm" method="POST" action="{{ url('/login-manager') }}" class="auth-form">
            @csrf
            
            @if ($errors->any())
              <div class="form-feedback error">
                @foreach ($errors->all() as $error)
                  <p>{{ $error }}</p>
                @endforeach
              </div>
            @endif
            
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="mimi.manager@senebi.ml" placeholder="Ex: adiaratou@sidi-agri.com" required />

            <label for="password">Mot de passe</label>
            <input id="password" name="password" type="password" value="manager123" placeholder="Votre mot de passe" required />

            <button class="btn login-submit" type="submit">Se connecter</button>
            <div id="loginFeedback" class="form-feedback" aria-live="polite"></div>
          </form>

          <div class="auth-switch">
            <p>Vous êtes un client ?</p>
            <a href="{{ route('login.client') }}" class="btn secondary">Accès Client</a>
          </div>
        </div>
      </section>
    </main>

    <script src="{{ asset('assets/js/auth.js') }}"></script>
  </body>
</html>