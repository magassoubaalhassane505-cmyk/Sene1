<header class="topbar">
  <div class="topbar-inner">
    <!-- Partie Gauche : Logo SeneBI -->
    <a class="brand" href="/manager/dashboard">
      <img class="logo-img" src="{{ asset('assets/img/logo.png') }}" alt="Logo SeneBI" />
      <div class="brand-title">
        <strong>SeneBI</strong>
        <span>Business Intelligence Agricole</span>
      </div>
    </a>

    <div class="topbar-right">
      <!-- Partie Centrale : Navigation -->
      <nav class="nav manager-nav">
        <a href="/manager/dashboard" class="{{ request()->path() == '/manager/dashboard' ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 13h8V3H3v10z"/>
            <path d="M13 21h8V11h-8v10z"/>
            <path d="M13 3h8v6h-8V3z"/>
            <path d="M3 17h8v4H3v-4z"/>
          </svg>
          <span>Dashboard</span>
        </a>
        <a href="/manager/supervision" class="{{ request()->path() == '/manager/supervision' ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
          </svg>
          <span>Supervision</span>
        </a>
        <a href="/manager/visites" class="{{ request()->path() == '/manager/visites' ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
          <span>Visites</span>
        </a>
        <a href="/manager/catalogue" class="{{ request()->path() == '/manager/catalogue' ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
            <path d="M12 22V8"/>
            <path d="M4.93 4.93L12 12l7.07-7.07"/>
          </svg>
          <span>Catalogue</span>
        </a>
      </nav>

      <!-- Partie Droite : Actions -->
      <div class="topbar-actions">
        <!-- Bouton "Retour au portail" -->
        <a class="btn secondary" href="{{ route('secure.portal') }}">🔧 Retour au portail</a>
        
        <div class="auth-pills">
          <a class="pill user-pill" href="/manager/compte">Mimi</a>
          <form action="/logout" method="POST" style="display:inline-flex; margin:0;">
            @csrf
            <button type="submit" class="pill auth-logout">Déconnexion</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- Styles pour le bouton "Retour au portail" -->
<style>
.btn.secondary {
  background: #f8fafc !important;
  color: #1e293b !important;
  border: 1px solid #e2e8f0 !important;
  padding: 8px 16px !important;
  border-radius: 8px !important;
  text-decoration: none !important;
  font-size: 13px !important;
  font-weight: 500 !important;
  transition: all 0.2s ease !important;
  display: inline-flex !important;
  align-items: center !important;
  gap: 6px !important;
}

.btn.secondary:hover {
  background: #e2e8f0 !important;
  color: #1e293b !important;
  border-color: #cbd5e1 !important;
}

/* Styles pour la navigation active */
.manager-nav a.active {
  background: #dcfce7;
  color: #14532d;
  font-weight: 600;
  border-left: 3px solid #10b981;
  border-radius: 0 8px 8px 0;
  transition: all 0.2s ease;
}

.manager-nav a.active:hover {
  background: #bbf7d0;
  border-left-color: #059669;
}
</style>