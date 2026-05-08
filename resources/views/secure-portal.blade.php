@extends('layouts.app')

@section('content')
<main class="portal-shell container">
  <section class="portal-head card">
    <div class="portal-brand-wrap">
      <img class="portal-logo" src="{{ asset('assets/img/logo.png') }}" alt="Logo SeneBI" />
      <div>
        <h1>Portail Manager SeneBI</h1>
        <p id="welcomeText">Bienvenue.</p>
      </div>
    </div>
    <div class="portal-actions">
      <span id="roleBadge" class="tag muted">Role</span>
    </div>
  </section>

  <section class="card section-shell" id="adminPanel" hidden>
    <div class="section-head">
      <h2>Gestion des comptes</h2>
      <p>Ajoutez des clients, attribuez des droits manager et bloquez des comptes si necessaire.</p>
    </div>
    <div class="admin-stats" id="adminStats"></div>
    <div class="admin-grid">
      <nav class="nav manager-nav">
        <a href="/manager/dashboard">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 13h8V3H3v10z"/>
            <path d="M13 21h8V11h-8v10z"/>
            <path d="M13 3h8v6h-8V3z"/>
            <path d="M3 17h8v4H3v-4z"/>
          </svg>
          <span>Dashboard</span>
        </a>
        <a href="/manager/supervision">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
          </svg>
          <span>Supervision</span>
        </a>
        <a href="/manager/visites">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
          <span>Visites</span>
        </a>
        <a href="/manager/catalogue">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
            <path d="M12 22V8"/>
            <path d="M4.93 4.93L12 12l7.07-7.07"/>
          </svg>
          <span>Catalogue</span>
        </a>
      </nav>
      <section class="admin-block admin-block--add" aria-labelledby="admin-add-title">
        <h3 id="admin-add-title" class="admin-block-title">Ajouter un utilisateur</h3>
        <div class="admin-add-layout">
          <form id="createUserForm" class="auth-form mini admin-create-form">
            @csrf
            <div class="admin-form-grid">
              <div class="form-field">
                <label for="newName">Nom complet</label>
                <input id="newName" type="text" required autocomplete="name" />
              </div>
              <div class="form-field">
                <label for="newCompany">Entreprise</label>
                <input id="newCompany" type="text" required autocomplete="organization" />
              </div>
              <div class="form-field form-field--full">
                <label for="newEmail">Email</label>
                <input id="newEmail" type="email" required autocomplete="email" />
              </div>
            </div>
            <div class="admin-form-footer">
              <button class="btn" type="submit">Ajouter l'utilisateur</button>
              <div id="adminFeedback" class="form-feedback" aria-live="polite"></div>
            </div>
          </form>
          <aside class="admin-add-aside" aria-label="Aide à la création de compte">
            <h4 class="admin-aside-title">Bonnes pratiques</h4>
            <ul class="admin-aside-list">
              <li>Un mot de passe provisoire est généré automatiquement après la création.</li>
              <li>
                Les <strong>managers</strong> gèrent le terrain ; les <strong>clients</strong> ont une vue synthétique.
              </li>
              <li>En cas d'oubli de mot de passe, utilisez <strong>Régénérer l'accès</strong> dans la liste des comptes.</li>
            </ul>
          </aside>
        </div>
      </section>
      <section class="admin-block" aria-labelledby="admin-list-title">
        <h3 id="admin-list-title" class="admin-block-title">Liste des comptes</h3>
        <div id="usersList" class="users-list"></div>
      </section>
    </div>
  </section>
</main>

@push('scripts')
<script src="{{ asset('assets/js/layout.js') }}"></script>
<script src="{{ asset('assets/js/core.js') }}"></script>
<script src="{{ asset('assets/js/auth.js') }}"></script>
@endpush
@endsection