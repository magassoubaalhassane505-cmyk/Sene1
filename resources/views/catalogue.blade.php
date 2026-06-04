<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Catalogue des Intrants & Tarification - SeneBI</title>
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" />
    
    <!-- Styles pour la navigation active -->
    <style>
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
  </head>
  <body data-page="catalogue">
    <div class="app">
      @include('header-manager')
      
      <main class="container">
        <div class="page-title">
          <div>
            <h1>Catalogue des Intrants & Tarification</h1>
            <p>Gestion des prix et disponibilités des intrants agricoles</p>
          </div>
        </div>

        <section class="card">
          <div class="card-header">
            <div>
              <h3 style="margin:0; font-size:16px;">Tarification des Intrants</h3>
              <div class="small muted">Définissez les prix de vente pour vos clients</div>
            </div>
            <span class="tag good">À jour</span>
          </div>
          
          <div class="card-body">
            <form action="{{ route('manager.catalogue.update') }}" method="POST">
              @csrf
              <table class="catalogue-table">
                <thead>
                  <tr>
                    <th>Intrant</th>
                    <th>Unité</th>
                    <th>Prix Actuel (FCFA)</th>
                    <th>Dernière mise à jour</th>
                    <th>Statut</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($intrants as $intrant)
                  <tr>
                    <td>
                      <div class="product-info">
                        <div class="product-icon {{ $intrant->type }}">
                          @if($intrant->type === 'fertilizer')
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-fertilizer"></use></svg>
                          @elseif($intrant->type === 'seed')
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-seed"></use></svg>
                          @else
                            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-chemical"></use></svg>
                          @endif
                        </div>
                        <div class="product-details">
                          <div class="product-name">{{ $intrant->nom }}</div>
                          <div class="product-description">{{ $intrant->description }}</div>
                        </div>
                      </div>
                    </td>
                    <td>{{ $intrant->unite }}</td>
                    <td>
                      <div class="price-wrapper">
                        <input type="number" name="prix[{{ $intrant->id }}]" class="price-input" value="{{ $intrant->prix }}" min="0" step="100">
                        <span class="price-unit">FCFA</span>
                      </div>
                    </td>
                    <td>{{ $intrant->derniere_maj ? \Carbon\Carbon::parse($intrant->derniere_maj)->format('d/m/Y') : '-' }}</td>
                    <td>
                      <select name="statut[{{ $intrant->id }}]" class="status-badge {{ $intrant->statut === 'Disponible' ? 'available' : ($intrant->statut === 'Stock limité' ? 'limited' : 'out-of-stock') }}" style="border: none; background: transparent; cursor: pointer;">
                        <option value="Disponible" {{ $intrant->statut === 'Disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="Stock limité" {{ $intrant->statut === 'Stock limité' ? 'selected' : '' }}>Stock limité</option>
                        <option value="Rupture" {{ $intrant->statut === 'Rupture' ? 'selected' : '' }}>Rupture</option>
                      </select>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              
              <div style="text-align: center; margin-top: 32px;">
                <button type="submit" class="save-btn">
                  <svg class="save-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                    <polyline points="17 21 17 13 7 13 7 21"/>
                    <polyline points="7 3 7 8 15 8"/>
                  </svg>
                  Enregistrer les nouveaux tarifs
                </button>
              </div>
            </form>
          </div>
        </section>
      </main>
      <div data-layout="footer"></div>
    </div>

    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <script src="{{ asset('assets/js/core.js') }}"></script>
    <script src="{{ asset('assets/js/auth.js') }}"></script>
    
    <!-- JavaScript pour la navigation active -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Détecter la page active dans la navigation
        const navLinks = document.querySelectorAll('.manager-nav a');
        const currentPath = window.location.pathname;

        navLinks.forEach(link => {
          const linkPath = new URL(link.href, window.location.origin).pathname;
          if (currentPath === linkPath) {
            link.classList.add('active');
          }
        });
        
        // Fonction pour sauvegarder les prix
        function savePrices() {
          // Récupérer les valeurs des champs avec leurs IDs
          const prixUree = document.getElementById('prix-uree').value;
          const prixNpk = document.getElementById('prix-npk').value;
          const prixMais = document.getElementById('prix-mais').value;
          const prixRiz = document.getElementById('prix-riz').value;
          const prixHerbicide = document.getElementById('prix-herbicide').value;
          const prixInsecticide = document.getElementById('prix-insecticide').value;
          
          // Sauvegarder dans localStorage
          localStorage.setItem('prix_uree', prixUree);
          localStorage.setItem('prix_npk', prixNpk);
          localStorage.setItem('prix_mais', prixMais);
          localStorage.setItem('prix_riz', prixRiz);
          localStorage.setItem('prix_herbicide', prixHerbicide);
          localStorage.setItem('prix_insecticide', prixInsecticide);
          
          console.log('Tarifs sauvegardés dans localStorage:', {
            uree: prixUree,
            npk: prixNpk,
            mais: prixMais,
            riz: prixRiz,
            herbicide: prixHerbicide,
            insecticide: prixInsecticide
          });
          
          // Afficher un feedback visuel
          const btn = document.querySelector('.save-btn');
          const originalContent = btn.innerHTML;
          
          btn.innerHTML = `
            <svg class="save-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            Tarifs enregistrés !
          `;
          btn.style.background = '#16a34a';
          
          setTimeout(() => {
            btn.innerHTML = originalContent;
            btn.style.background = '#00a65a';
          }, 3000);
        }
      });
    </script>
    
    <!-- Styles pour le catalogue -->
    <style>
      /* Champs de prix avec unité FCFA */
      .price-wrapper {
        position: relative;
        display: inline-block;
      }
      
      .price-input {
        width: 140px;
        padding: 8px 45px 8px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        background: #f9fafb;
        color: var(--text-primary);
        transition: all 0.2s ease;
      }
      
      .price-input:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        background: white;
      }
      
      .price-input:hover {
        border-color: #d1d5db;
      }
      
      .price-unit {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 12px;
        color: var(--text-muted);
        font-weight: 500;
        pointer-events: none;
      }
      
      /* Badges de statut avec couleurs pastels */
      .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
      }
      
      .status-badge.available {
        background: rgba(34, 197, 94, 0.1);
        color: #15803d;
        border: 1px solid rgba(34, 197, 94, 0.2);
      }
      
      .status-badge.limited {
        background: rgba(245, 158, 11, 0.1);
        color: #92400e;
        border: 1px solid rgba(245, 158, 11, 0.2);
      }
      
      .status-badge.out-of-stock {
        background: rgba(239, 68, 68, 0.1);
        color: #991b1b;
        border: 1px solid rgba(239, 68, 68, 0.2);
      }
      
      /* Bouton d'enregistrement avec icône */
      .save-btn {
        background: #00a65a;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 24px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
      }
      
      .save-btn:hover {
        background: #008d4c;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 166, 90, 0.3);
      }
      
      .save-btn:active {
        transform: translateY(0);
      }
      
      .save-icon {
        width: 16px;
        height: 16px;
      }
      
      /* Icônes de produits */
      .product-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 18px;
        flex-shrink: 0;
      }

      .product-icon .icon {
        width: 20px;
        height: 20px;
        display: block;
        color: inherit;
      }
      
      .product-icon.fertilizer {
        background: rgba(34, 197, 94, 0.1);
        color: #16a34a;
      }
      
      .product-icon.seed {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
      }
      
      .product-icon.chemical {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
      }
      
      .product-info {
        display: flex;
        align-items: center;
      }
      
      .product-details {
        flex: 1;
      }
      
      .product-name {
        font-weight: 600;
        margin-bottom: 2px;
      }
      
      .product-description {
        font-size: 12px;
        color: var(--text-muted);
      }
      
      /* Tableau des intrants */
      .catalogue-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
      }
      
      .catalogue-table th {
        background: var(--bg-secondary);
        padding: 12px;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid var(--border);
        color: var(--text-primary);
      }
      
      .catalogue-table td {
        padding: 12px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
        transition: background-color 0.2s ease;
      }
      
      .catalogue-table tbody tr:hover {
        background: rgba(59, 130, 246, 0.05);
      }
    </style>
  </body>
</html>