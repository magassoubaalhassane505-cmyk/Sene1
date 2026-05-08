<header class="topbar">
  <div class="topbar-inner">
    <!-- Partie Gauche : Logo SeneBI -->
    <a class="brand" href="{{ url('/client/dashboard') }}">
      <img class="logo-img" src="{{ asset('assets/img/logo.png') }}" alt="Logo SeneBI" />
      <div class="brand-title">
        <strong>SeneBI</strong>
        <span>Business Intelligence Agricole</span>
      </div>
    </a>

    <div class="topbar-right">
      <!-- Partie Centrale : Navigation -->
      <nav class="nav client-nav">
        <a href="{{ url('/client/dashboard') }}" class="{{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 13h8V3H3v10z"/>
            <path d="M13 21h8V11h-8v10z"/>
            <path d="M13 3h8v6h-8V3z"/>
            <path d="M3 17h8v4H3v-4z"/>
          </svg>
          <span>Dashboard</span>
        </a>
        <a href="{{ url('/client/parcelles') }}" class="{{ request()->routeIs('client.parcelles') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 21s7-4.5 7-11a7 7 0 0 0-14 0c0 6.5 7 11 7 11z"/>
            <path d="M12 10a2 2 0 1 0 0-4 2 2 0 0 0 4z"/>
          </svg>
          <span>Parcelles</span>
        </a>
        <a href="{{ url('/client/rentabilite') }}" class="{{ request()->routeIs('client.rentabilite') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
            <path d="M12 7v10"/>
            <path d="M9.5 9.5c.6-1 4.4-1 5 0"/>
            <path d="M9.5 14.5c.6 1 4.4 1 5 0"/>
          </svg>
          <span>Rentabilité</span>
        </a>
        <a href="{{ url('/client/stocks') }}" class="{{ request()->routeIs('client.stocks') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
          <span>Stocks</span>
        </a>
      </nav>

      <!-- Partie Droite : Actions -->
      <div class="topbar-actions">
        <div class="auth-pills">
          <!-- Cloche de notification corrigée -->
          <a href="#" class="notification-bell">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
            <span class="badge">3</span>
          </a>
          
          <a class="pill user-pill" href="{{ url('/client/mon-compte') }}">Sidi</a>
          <form action="{{ route('logout') }}" method="POST" style="display:inline-flex; margin:0;">
            @csrf
            <button type="submit" class="pill auth-logout">Déconnexion</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Styles corrigés pour la cloche -->
  <style>
    .notification-bell {
      position: relative;
      color: #64748b;
      padding: 8px;
      border-radius: 6px;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .notification-bell:hover {
      color: #10b981;
      background: #f0fdf4;
    }
    
    .notification-bell .badge {
      position: absolute;
      top: 4px;
      right: 4px;
      background: #ef4444;
      color: white;
      font-size: 10px;
      font-weight: 600;
      padding: 2px 4px;
      border-radius: 10px;
      min-width: 16px;
      text-align: center;
    }
    
    .notification-bell svg {
      width: 20px;
      height: 20px;
      color: inherit;
    }
    
    .client-nav a.active {
      background: #dcfce7;
      color: #14532d;
      font-weight: 600;
      border-left: 3px solid #10b981;
      border-radius: 0 8px 8px 0;
      transition: all 0.2s ease;
    }

    .client-nav a.active:hover {
      background: #bbf7d0;
      border-left-color: #059669;
    }
  </style>
</header>