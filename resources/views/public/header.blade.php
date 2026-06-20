<header class="topbar">
  <div class="topbar-inner">
    <!-- Partie Gauche : Logo SeneBI -->
    <a class="brand" href="/">
      <img class="logo-img" src="{{ asset('assets/img/logo.png') }}" alt="Logo SeneBI" />
      <div class="brand-title">
        <strong>SeneBI</strong>
        <span>Business Intelligence Agricole</span>
      </div>
    </a>

    <div class="topbar-right">
      <!-- Partie Centrale : Navigation -->
      <nav class="nav public-nav">
        <a href="/" class="{{ request()->path() == '/' or request()->path() == '' ? 'active' : '' }}">
          <span>Accueil</span>
        </a>
        <a href="/solutions" class="{{ request()->is('solutions*') ? 'active' : '' }}">
          <span>Solutions</span>
        </a>
        <a href="/a-propos" class="{{ request()->is('a-propos*') ? 'active' : '' }}">
          <span>À propos</span>
        </a>
        <a href="/faq" class="{{ request()->is('faq*') ? 'active' : '' }}">
          <span>FAQ</span>
        </a>
        <a href="/contact" class="{{ request()->is('contact*') ? 'active' : '' }}">
          <span>Contact</span>
        </a>
      </nav>

      <!-- Partie Droite : Actions -->
      <div class="topbar-actions">
        <a class="btn" href="/connexion" style="background: transparent; color: var(--text); border: 1px solid rgba(15,23,42,0.08);">Se connecter</a>
        <a class="btn" href="/inscription" style="background: var(--accent); color: #fff;">Créer un compte</a>
      </div>
    </div>
  </div>
</header>

<style>
.public-nav a.active {
  background: #dcfce7;
  color: #14532d;
  font-weight: 600;
  border-left: 3px solid #10b981;
  border-radius: 0 8px 8px 0;
  transition: all 0.2s ease;
}

.public-nav a.active:hover {
  background: #bbf7d0;
  border-left-color: #059669;
}

@media (max-width: 768px) {
  .public-nav {
    display: none;
  }
  
  .topbar-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
}
</style>