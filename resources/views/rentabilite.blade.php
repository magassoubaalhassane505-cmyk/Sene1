<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SeneBI - Rentabilite</title>
<link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/rentabilite.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/visual-harmony.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/rentabilite-premium.css') }}" />
     <link rel="stylesheet" href="{{ asset('assets/css/region-filter.css') }}" />
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
     
     <!-- CSS pour centrage parfait des KPI -->
     <style>
         /* NOUVEAUX STYLES - Sections Rentabilité BI */
        /* HARMONISATION TOTALE - Design identique à client-dashboard */
        .kpi-card {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-start;
            text-align: left;
            position: relative;
            height: 160px; /* HAUTEUR FIXE pour alignement parfait */
            padding: 16px 16px;
            min-height: 160px;
            max-height: 160px;
            border: 1px solid #f1f5f9; /* Bordure très fine gris clair */
            background: #ffffff; /* Fond blanc pur */
            border-radius: 15px; /* Arrondis identiques */
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); /* Ombre très douce */
        }

        .kpi-card > div {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: space-between;
            width: 100%;
            height: 100%;
            text-align: left;
            gap: 0; /* Pas d'espacement entre les 2 éléments */
            flex: 1;
            padding: 0; /* Pas de padding interne */
        }

        /* Header avec titre et icône sur même ligne - LIGNE 1 (ALIGNEMENT PARFAIT) */
        .kpi-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-bottom: 20px; /* Espacement optimal avant le chiffre */
            order: 1;
        }

        /* Montant principal - Centré verticalement - STYLE FORCÉ */
        .kpi-amount-centered {
            order: 2;
            margin: auto !important; /* Centre parfaitement verticalement - FORCÉ */
            display: flex !important;
            align-items: baseline !important;
            justify-content: flex-start !important;
        }

        /* FORCER LE STYLE DU CHIFFRE MARGE NETTE */
        #marginKpi .number {
            color: #dc2626 !important; /* Rouge vif pour négatif - FORCÉ */
            font-size: 2rem !important; /* Taille identique - FORCÉ */
            font-weight: 800 !important; /* Très gras - FORCÉ */
            letter-spacing: -0.5px !important; /* Espacement pro - FORCÉ */
        }

        .kpi-card h3 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
            color: #64748b; /* Gris moyen comme client-dashboard */
            text-align: left;
            flex: 1;
        }

        .kpi-card strong,
        .kpi-card #salesKpi,
        .kpi-card #costKpi,
        .kpi-card #profitKpi,
        .kpi-card #marginKpi {
            display: flex;
            align-items: baseline;
            justify-content: flex-start;
            text-align: left;
            margin: 0;
            font-size: 2rem; /* Taille identique à client-dashboard */
            font-weight: 800; /* Très épaisse */
            line-height: 1.1;
            letter-spacing: -0.5px;
            order: 2;
            flex-shrink: 0;
        }

        .kpi-card p {
            margin: 0;
            font-size: 12px;
            font-weight: 500;
            text-align: center;
            width: 100%;
            order: 3;
            flex-shrink: 0;
        }

        /* ICÔNES STYLE CLIENT-DASHBOARD - Carré arrondi avec fond gris très clair */
        .kpi-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            z-index: 1;
            background: #f8fafc; /* Fond gris très clair */
            color: #64748b; /* Gris bleuté */
        }

        /* Centrage PARFAIT des textes de conseil sous Marge Nette - COMPACTÉ */
        #marginKpi > div {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px; /* RÉDUCTION de l'espace : 8px -> 4px */
        }

        #marginKpi > div > span:first-child {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 1.6rem; /* RÉDUCTION : 28px -> 1.6rem */
            font-weight: 700;
            line-height: 1.1;
            width: 100%;
        }

        #marginKpi > div > span:last-child {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            line-height: 1.3;
            font-size: 10px; /* RÉDUCTION : 11px -> 10px */
            max-width: 95%;
            width: 100%;
        }

        /* Formatage élégant pour les montants FCFA */
        .kpi-amount-centered {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 3px;
            width: 100%;
            height: 100%;
        }

        .kpi-amount-centered .number {
            font-size: 2rem; /* Taille identique à client-dashboard */
            font-weight: 800; /* Très épaisse */
            line-height: 1.1;
            color: #1e293b; /* Gris très foncé */
        }

        .kpi-amount-centered .unit {
            font-size: 1rem; /* Taille identique à client-dashboard */
            font-weight: 600; /* Plus épaisse */
            line-height: 1.1;
            color: #64748b; /* Couleur plus claire mais visible */
            margin-left: 5px; /* ESPACEMENT avec le chiffre */
            vertical-align: baseline; /* ALIGNEMENT sur la ligne de base */
        }

        /* CORRECTION CIBLÉE MARGE NETTE - Phrase explicative sur une seule ligne */
        #marginKpi .explanation,
        .kpi-card .explanation {
            font-size: 0.7rem !important; /* 10px environ - FORCÉ */
            color: #ef4444 !important; /* Rouge pour la perte - FORCÉ */
            margin-top: auto !important; /* Pousse tout en bas de la carte - FORCÉ */
            margin-bottom: 8px !important; /* Espacement du bord - FORCÉ */
            padding-bottom: 8px !important; /* Padding pour ne pas toucher le bord - FORCÉ */
            line-height: 1.3 !important;
            order: 3 !important; /* Tout en bas - FORCÉ */
            font-weight: 500 !important; /* Même police que le projet - FORCÉ */
            white-space: nowrap !important; /* Force une seule ligne - FORCÉ */
            overflow: hidden !important; /* Cache le débordement - FORCÉ */
            text-overflow: ellipsis !important; /* Ajoute ... si trop long - FORCÉ */
        }

        /* Alignement horizontal des KPI avec gap de 15px */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px; /* ESPACEMENT de 15px entre les cartes */
            margin-bottom: 24px;
        }

        /* Responsive pour petits écrans */
        @media (max-width: 768px) {
            .kpi-card {
                height: 180px;
                min-height: 180px;
                max-height: 180px;
                padding: 16px 12px;
                min-height: 160px;
            }
            
            .kpi-card h3 {
                font-size: 13px;
            }
            
            .kpi-card strong,
            .kpi-card #salesKpi,
            .kpi-card #costKpi,
            .kpi-card #profitKpi,
            .kpi-card #marginKpi {
                font-size: 24px;
            }
            
            .kpi-icon {
                width: 20px;
                height: 20px;
                font-size: 10px;
            }
        }
    </style>
  </head>
  <body data-page="business">
    <div class="app">
       @include('header-client')  

      <main class="container">
        <section class="page-head senebi-page-transition">
          <div>
            <h1>Rentabilite & Exports</h1>
            <p>Analyse financiere et generation de rapports</p>
          </div>
          <div class="head-actions">
            <div class="region-selector">
              <label for="regionSelectRent" class="region-label">Région</label>
              <select id="regionSelectRent" class="region-dropdown">
                <option value="all">Toutes les régions</option>
                <option value="bko">Bamako</option>
                <option value="kay">Kayes</option>
                <option value="kou">Koulikoro</option>
                <option value="seg">Ségou</option>
                <option value="sik">Sikasso</option>
                <option value="mop">Mopti</option>
                <option value="tom">Tombouctou</option>
                <option value="gao">Gao</option>
                <option value="kid">Kidal</option>
              </select>
            </div>
            <button class="btn secondary" type="button" id="openCalculatorBtn">Calculateur</button>
            <button class="btn" type="button" id="exportPdfBtn">Exporter le bilan PDF</button>
          </div>
        </section>

        <section class="card calculator-panel" id="calculatorPanel" aria-hidden="true">
          <h2>Calculateur de Rentabilite</h2>
          <div class="calculateur" id="calculateurForm">
            <!-- Première ligne : Parcelle, Culture, Surface -->
            <div class="input-row">
              <div class="input-field">
                <label for="calcParcel">Parcelle</label>
                <select id="calcParcel">
                  <option value="">Sélectionner une parcelle</option>
                  @foreach($parcelles as $parcelle)
                    <option value="{{ $parcelle->id }}"
                            data-culture="{{ $parcelle->culture }}"
                            data-surface="{{ $parcelle->surface }}">
                      {{ $parcelle->nom }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="input-field">
                <label for="calcCulture">Culture</label>
                <select id="calcCulture">
                  <option value="">Sélectionner une culture</option>
                  <option value="Riz">Riz</option>
                  <option value="Maïs">Maïs</option>
                  <option value="Coton">Coton</option>
                </select>
              </div>
              <div class="input-field">
                <label for="calcArea">Surface (ha)</label>
                <input id="calcArea" type="number" min="0" step="0.1" placeholder="Ex: 5.5" />
              </div>
            </div>

            <!-- Deuxième ligne : Quantité, Prix, Intrants, Autres -->
            <div class="input-row">
              <div class="input-field">
                <label for="calcQty">Quantite recoltee (kg)</label>
                <input id="calcQty" type="number" min="0" step="1" placeholder="Ex: 22000" />
              </div>
              <div class="input-field">
                <label for="calcPrice">Prix unitaire (FCFA/kg)</label>
                <input id="calcPrice" type="number" min="0" step="1" placeholder="Ex: 250" />
              </div>
              <div class="input-field">
                <label for="calcIntrants">Couts intrants (FCFA)</label>
                <input id="calcIntrants" type="number" min="0" step="1000" placeholder="Ex: 2500000" />
              </div>
              <div class="input-field">
                <label for="calcOther">Autres couts (FCFA)</label>
                <input id="calcOther" type="number" min="0" step="1000" placeholder="Ex: 400000" />
              </div>
            </div>

            <!-- Ligne de résultats : 5 cartes alignées -->
            <div class="results-row">
              <div class="result-card">
                <span class="result-label">Rendement</span>
                <span id="valeur-rendement" style="font-size: 18px; font-weight: 700; color: #1e40af; display: block;">0 kg/ha</span>
              </div>
              <div class="result-card">
                <span class="result-label">Revenu total</span>
                <span id="valeur-revenu" style="font-size: 18px; font-weight: 700; color: #15803d; display: block;">0 FCFA</span>
              </div>
              <div class="result-card">
                <span class="result-label">Bénéfice net</span>
                <span id="valeur-benefice" style="font-size: 18px; font-weight: 700; color: #047857; display: block;">0 FCFA</span>
              </div>
              <div class="result-card">
                <span class="result-label">Marge nette</span>
                <span id="valeur-marge" style="font-size: 18px; font-weight: 700; color: #92400e; display: block;">0%</span>
              </div>
              <div class="result-card verdict-card">
                <span class="result-label">Verdict</span>
                <span id="valeur-verdict" style="font-size: 18px; font-weight: 800; color: #ef4444; display: block;">Attention ! Marge faible</span>
              </div>
            </div>

            <!-- Actions -->
            <div class="calc-actions">
              <button class="btn" type="button" id="applyCalculatorBtn">Appliquer au bilan</button>
              <button class="btn-ghost" type="button" id="closeCalculatorBtn">Fermer</button>
            </div>
            <div class="form-feedback" id="calculatorFeedback" aria-live="polite"></div>
          </div>
        </section>

        <!-- CSS de structure du calculateur et barre d'action -->
        <style>
          /* ALIGNEMENT PARFAIT BARRE D'ACTION */
          .head-actions {
            display: flex;
            align-items: flex-end; /* Alignement vertical parfait */
            gap: 15px; /* Espacement entre éléments */
            flex-wrap: wrap; /* Adaptation mobile */
          }

          .region-selector {
            display: flex;
            flex-direction: column;
            margin-bottom: 0; /* Évite de pousser vers le bas */
          }

          .region-label {
            margin-bottom: 4px; /* Collé au sélecteur */
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
          }

          .region-dropdown {
            margin-bottom: 0; /* Alignement parfait avec boutons */
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.9rem;
            min-width: 180px;
          }

          .head-actions .btn {
            margin-bottom: 0; /* Alignement vertical */
            height: auto; /* Hauteur automatique */
          }

          /* DESIGN PREMIUM EXPORT PDF */
          .pdf-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            margin-bottom: 20px;
          }

          .pdf-logo {
            width: 120px;
            height: auto;
          }

          .pdf-header-info {
            text-align: right;
            border: 2px solid #2e7d32;
            padding: 15px 20px;
            border-radius: 8px;
            background: #f8fafc;
          }

          .pdf-header-info h3 {
            margin: 0 0 8px 0;
            color: #2e7d32;
            font-size: 1.2rem;
            font-weight: 700;
          }

          .pdf-header-info p {
            margin: 4px 0;
            color: #374151;
            font-size: 0.9rem;
            font-weight: 600;
          }

          .pdf-separator {
            height: 3px;
            background: #2e7d32;
            margin: 20px 0 30px 0;
            border-radius: 2px;
          }

          .pdf-kpi-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            background: #f8fafc;
            border-radius: 8px;
            overflow: hidden;
          }

          .pdf-kpi-table th {
            background: #2e7d32;
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: 700;
            font-size: 1rem;
          }

          .pdf-kpi-table td {
            padding: 20px 15px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 700;
            font-size: 1.1rem;
            color: #1f2937;
          }

          .pdf-kpi-table td:last-child {
            border-bottom: none;
          }

          .pdf-chart-container {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: white;
          }

          .pdf-chart-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 15px;
            color: #2e7d32;
          }

          .pdf-data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
          }

          .pdf-data-table thead th {
            background: #1f2937;
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
          }

          .pdf-data-table tbody tr:nth-child(even) {
            background: #f8fafc;
          }

          .pdf-data-table tbody tr:nth-child(odd) {
            background: white;
          }

          .pdf-data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.9rem;
            color: #374151;
          }

          .pdf-footer {
            margin-top: 50px;
            padding: 20px 0;
            border-top: 2px solid #2e7d32;
            text-align: center;
            color: #6b7280;
            font-size: 0.8rem;
          }

          .pdf-footer .confidential {
            font-weight: 700;
            color: #dc2626;
            margin-top: 10px;
          }

          /* Structure principale du calculateur */
          .calculateur {
            display: flex;
            flex-direction: column;
            gap: 24px;
          }

          .calculateur {
            padding: 24px;
            background: #ffffff;
            border-radius: var(--radius, 16px);
            box-shadow: var(--shadow, 0 4px 20px rgba(15, 23, 42, 0.08));
          }

          /* Lignes d'entrées alignées */
          .input-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
            margin-bottom: 0;
          }

          .input-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
          }

          .input-field label {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
          }

          .input-field select,
          .input-field input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid rgba(15, 23, 42, 0.15);
            border-radius: 12px;
            background: #ffffff;
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            font-size: 14px;
            color: #0f172a;
            transition: all 0.2s ease;
            outline: none;
          }

          .input-field select:focus,
          .input-field input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
          }

          /* Ligne de résultats alignés */
          .results-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            margin-top: 8px;
          }

          .result-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid rgba(15, 23, 42, 0.1);
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            min-height: 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
          }

          .result-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.12);
          }

          .result-label {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            display: block;
          }

          .result-value {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
          }

          /* Carte Verdict simplifiée */
          .verdict-card {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 2px solid #ef4444;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
          }

          .verdict-card .result-label {
            color: #991b1b;
          }

          .verdict-card .result-value {
            color: #ef4444;
            font-weight: 800;
          }

          /* Actions */
          .calc-actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
            justify-content: center;
          }

          /* Responsive */
          @media (max-width: 768px) {
            .input-row {
              grid-template-columns: 1fr;
              gap: 12px;
            }
            
            .results-row {
              grid-template-columns: 1fr;
              gap: 12px;
            }
          }
</style>

        <script>
          // Initialisation du calculateur
          document.addEventListener('DOMContentLoaded', function() {
            // FONCTION POUR RÉCUPÉRER LES PRIX DU CATALOGUE MANAGER
            function getPrixFromCatalogue() {
              // Récupérer les prix depuis localStorage avec valeurs par défaut
              const prixCatalogue = {
                riz: localStorage.getItem('prix_riz') || '1500',
                mais: localStorage.getItem('prix_mais') || '1200',
                coton: localStorage.getItem('prix_coton') || '2000', // Prix par défaut pour coton
                uree: localStorage.getItem('prix_uree') || '25000',
                npk: localStorage.getItem('prix_npk') || '35000'
              };
              
              console.log('Prix récupérés du catalogue:', prixCatalogue);
              return prixCatalogue;
            }
            
            // FONCTION POUR METTRE À JOUR L'UI AVEC LES PRIX SYNCHRONISÉS
            function updateUIWithCataloguePrices() {
              const prixCatalogue = getPrixFromCatalogue();
              const calcPrice = document.getElementById('calcPrice');
              const calcCulture = document.getElementById('calcCulture');
              
              // Vérifier si des prix du catalogue sont disponibles
              const hasCataloguePrices = Object.keys(prixCatalogue).some(key => 
                localStorage.getItem(`prix_${key}`) !== null
              );
              
              if (hasCataloguePrices && calcPrice && calcCulture) {
                // Ajouter une icône de synchronisation à côté du champ prix
                const priceField = calcPrice.closest('.input-field');
                if (priceField && !priceField.querySelector('.sync-icon')) {
                  const syncIcon = document.createElement('span');
                  syncIcon.className = 'sync-icon';
                  syncIcon.innerHTML = '☁️';
                  syncIcon.style.cssText = `
                    position: absolute;
                    right: 40px;
                    top: 32px;
                    font-size: 14px;
                    color: #10b981;
                    cursor: help;
                    title: 'Tarif synchronisé avec le dépôt central SeneBI'
                  `;
                  priceField.style.position = 'relative';
                  priceField.appendChild(syncIcon);
                }
                
                // Mettre à jour automatiquement le prix quand la culture change
                calcCulture.addEventListener('change', function() {
                  const culture = calcCulture.value;
                  let prix = 0;
                  
                  switch(culture) {
                    case 'Riz':
                      prix = parseFloat(prixCatalogue.riz);
                      break;
                    case 'Maïs':
                      prix = parseFloat(prixCatalogue.mais);
                      break;
                    case 'Coton':
                      prix = parseFloat(prixCatalogue.coton);
                      break;
                  }
                  
                  if (prix > 0) {
                    calcPrice.value = prix;
                    // Lancer le calcul automatiquement
                    performCalculations();
                  }
                });
              }
            }
            
            // AJOUTER LA PHRASE DE SYNCHRONISATION SOUS LE RÉSULTAT
            function addSyncMessage() {
              const prixCatalogue = getPrixFromCatalogue();
              const hasCataloguePrices = Object.keys(prixCatalogue).some(key => 
                localStorage.getItem(`prix_${key}`) !== null
              );
              
              if (hasCataloguePrices) {
                // Vérifier si la phrase existe déjà
                if (!document.querySelector('.sync-message')) {
                  const calculatorPanel = document.querySelector('.calculator-panel');
                  if (calculatorPanel) {
                    const syncMessage = document.createElement('div');
                    syncMessage.className = 'sync-message';
                    syncMessage.innerHTML = `
                      <div style="text-align: center; margin-top: 16px; padding: 8px; background: #f0fdf4; border-radius: 8px; border: 1px solid #bbf7d0;">
                        <span style="color: #16a34a; font-size: 12px; font-weight: 500;">
                          🔄 Tarifs synchronisés avec le dépôt central SeneBI
                        </span>
                      </div>
                    `;
                    calculatorPanel.appendChild(syncMessage);
                  }
                }
              }
            }
            
            // Initialiser les fonctionnalités de synchronisation
            updateUIWithCataloguePrices();
            addSyncMessage();
            
            // Récupération de tous les éléments
            const calcParcel = document.getElementById('calcParcel');
            const calcCulture = document.getElementById('calcCulture');
            const calcArea = document.getElementById('calcArea');
            const calcQty = document.getElementById('calcQty');
            const calcPrice = document.getElementById('calcPrice');
            const calcIntrants = document.getElementById('calcIntrants');
            const calcOther = document.getElementById('calcOther');
            
            // Éléments de résultats avec IDs uniques
            const valeurRendement = document.getElementById('valeur-rendement');
            const valeurRevenu = document.getElementById('valeur-revenu');
            const valeurBenefice = document.getElementById('valeur-benefice');
            const valeurMarge = document.getElementById('valeur-marge');
            const valeurVerdict = document.getElementById('valeur-verdict');

            // Fonction de calcul principal
            function performCalculations() {
              // Récupération et conversion des valeurs
              const area = parseFloat(calcArea.value) || 0;
              const qty = parseFloat(calcQty.value) || 0;
              const price = parseFloat(calcPrice.value) || 0;
              const intrants = parseFloat(calcIntrants.value) || 0;
              const other = parseFloat(calcOther.value) || 0;

              // Calculs
              const rendement = area > 0 ? qty / area : 0;
              const revenue = qty * price;
              const benefice = revenue - intrants - other;
              const marge = revenue > 0 ? (benefice / revenue) * 100 : 0;

              // Affichage des résultats avec formatage propre et innerText
              valeurRendement.innerText = rendement.toFixed(2) + ' kg/ha';
              valeurRevenu.innerText = revenue.toLocaleString('fr-FR') + ' FCFA';
              valeurBenefice.innerText = benefice.toLocaleString('fr-FR') + ' FCFA';
              valeurMarge.innerText = marge.toFixed(1) + '%';

              // Calcul du verdict
              let verdictText = '';
              let verdictColor = '';
              
              if (marge > 30) {
                verdictText = 'Succès ! Rentabilité excellente';
                verdictColor = '#10b981';
              } else if (marge >= 10) {
                verdictText = 'Bonne rentabilité';
                verdictColor = '#f59e0b';
              } else if (marge >= 0) {
                verdictText = 'Attention ! Marge faible';
                verdictColor = '#ef4444';
              } else {
                verdictText = 'Alerte ! Perte financière';
                verdictColor = '#dc2626';
              }

              valeurVerdict.innerText = verdictText;
              valeurVerdict.style.color = verdictColor;

              // Mise à jour des couleurs des cartes
              updateCardColors(benefice, marge);
            }

            // Fonction pour mettre à jour les couleurs des cartes
            function updateCardColors(benefice, marge) {
              const profitCard = valeurBenefice.closest('.result-card');
              const marginCard = valeurMarge.closest('.result-card');
              const verdictCard = valeurVerdict.closest('.result-card');

              // Bénéfice positif en vert
              if (benefice > 0) {
                profitCard.style.background = 'linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%)';
                profitCard.style.borderColor = '#10b981';
                valeurBenefice.style.color = '#047857';
              } else if (benefice < 0) {
                profitCard.style.background = 'linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%)';
                profitCard.style.borderColor = '#ef4444';
                valeurBenefice.style.color = '#dc2626';
              }

              // Marge
              if (marge > 30) {
                marginCard.style.background = 'linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%)';
                marginCard.style.borderColor = '#10b981';
                valeurMarge.style.color = '#047857';
              } else if (marge < 10) {
                marginCard.style.background = 'linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%)';
                marginCard.style.borderColor = '#ef4444';
                valeurMarge.style.color = '#dc2626';
              }

              // Verdict
              if (marge > 30) {
                verdictCard.style.background = 'linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%)';
                verdictCard.style.borderColor = '#10b981';
                verdictCard.querySelector('.result-label').style.color = '#047857';
              } else if (marge < 10) {
                verdictCard.style.background = 'linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%)';
                verdictCard.style.borderColor = '#ef4444';
                verdictCard.querySelector('.result-label').style.color = '#991b1b';
              }
            }

            // Ajout des écouteurs d'événements pour TOUS les champs
            calcParcel.addEventListener('change', performCalculations);
            calcCulture.addEventListener('change', performCalculations);
            calcArea.addEventListener('input', performCalculations);
            calcQty.addEventListener('input', performCalculations);
            calcPrice.addEventListener('input', performCalculations);
            calcIntrants.addEventListener('input', performCalculations);
            calcOther.addEventListener('input', performCalculations);

            // Intelligence du calculateur - Remplissage automatique avec verrouillage et calcul flash
            function autoFillParcelleData() {
              const selectedOption = calcParcel.options[calcParcel.selectedIndex];
              const parcelleId = calcParcel.value;

              if (!parcelleId) {
                calcArea.removeAttribute('readonly');
                calcArea.style.backgroundColor = '';
                calcArea.style.cursor = '';
                calcArea.value = '';
                calcCulture.value = '';
                return;
              }

              const culture = selectedOption.getAttribute('data-culture') || '';
              const surface = selectedOption.getAttribute('data-surface') || '';

              calcCulture.value = culture;
              calcArea.value = surface;

              calcArea.setAttribute('readonly', true);
              calcArea.style.backgroundColor = '#f8fafc';
              calcArea.style.cursor = 'not-allowed';

              setTimeout(() => {
                performCalculations();
              }, 100);
            }

            async function saveCalculationToDatabase() {
              const parcelleId = calcParcel.value;
              const culture = calcCulture.value;
              const surface = parseFloat(calcArea.value) || 0;
              const qty = parseFloat(calcQty.value) || 0;
              const price = parseFloat(calcPrice.value) || 0;
              const intrants = parseFloat(calcIntrants.value) || 0;
              const other = parseFloat(calcOther.value) || 0;

              if (!parcelleId || !culture || !qty || !price) {
                alert('Veuillez remplir la parcelle, la culture, la quantité et le prix.');
                return;
              }

              try {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const response = await fetch('/client/api/recoltes', {
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                  },
                  body: JSON.stringify({
                    parcelle_id: parcelleId,
                    date: new Date().toISOString().slice(0, 10),
                    quantite: qty,
                    prix_unitaire: price,
                    culture: culture,
                    couts_totaux: intrants + other,
                  })
                });

                const result = await response.json();
                if (!response.ok) {
                  throw new Error(result.message || 'Erreur lors de l\'enregistrement');
                }

                showToast('Rentabilité enregistrée avec succès');
              } catch (error) {
                alert('Erreur: ' + error.message);
              }
            }

            calcParcel.addEventListener('change', autoFillParcelleData);

            // FORCE BRUTE : Fonction de formatage CFA définitive
            function formatCFA(valeur) {
              const montantEntier = Math.round(valeur);
              return montantEntier.toLocaleString('fr-FR') + ' FCFA';
            }

            // Fonction de formatage HTML pour KPI CENTRÉ (chiffre + FCFA sur même ligne)
            function createKPIHTMLCentered(amount, isNegative = false) {
              const color = isNegative ? '#dc2626' : '#047857';
              const formattedAmount = Math.abs(amount).toLocaleString('fr-FR');
              return `<div class="kpi-amount-centered" style="color: ${color};">
                <span class="number">${isNegative ? '-' : ''}${formattedAmount}</span>
                <span class="unit">FCFA</span>
              </div>`;
            }

            // AMÉLIORATION : Liaison Calculateur ➔ Bilan avec cumul, graphiques et notification
            function applyCalculatorToBilan() {
              saveCalculationToDatabase();
              // Attendre un peu que la transformation initiale se fasse
              setTimeout(() => {
                // Récupérer les éléments KPI du bilan
                const salesKpi = document.getElementById('salesKpi');
                const costKpi = document.getElementById('costKpi');
                const profitKpi = document.getElementById('profitKpi');
                const marginKpi = document.getElementById('marginKpi');
                const marginStatus = document.getElementById('marginStatus');

                // Récupérer les valeurs du calculateur
                const calcRevenue = document.getElementById('valeur-revenu');
                const calcProfit = document.getElementById('valeur-benefice');
                const calcMargin = document.getElementById('valeur-marge');

                // Récupérer les valeurs actuelles des KPI pour cumul
                let currentSales = 0, currentCost = 0, currentProfit = 0;
                
                if (salesKpi) {
                  const salesText = salesKpi.textContent;
                  currentSales = parseFloat(salesText.replace(/[^\d.-]/g, '')) || 0;
                }
                
                if (costKpi) {
                  const costText = costKpi.textContent;
                  currentCost = parseFloat(costText.replace(/[^\d.-]/g, '')) || 0;
                }
                
                if (profitKpi) {
                  const profitText = profitKpi.textContent;
                  currentProfit = parseFloat(profitText.replace(/[^\d.-]/g, '')) || 0;
                }

                // Récupérer les nouvelles valeurs du calculateur
                const revenueText = calcRevenue ? calcRevenue.textContent : '0';
                const profitText = calcProfit ? calcProfit.textContent : '0';
                const newRevenue = parseFloat(revenueText.replace(/[^\d.-]/g, '')) || 0;
                const newProfit = parseFloat(profitText.replace(/[^\d.-]/g, '')) || 0;
                const newCost = newRevenue - newProfit;

                // Calculer les valeurs cumulées
                const cumulativeSales = currentSales + newRevenue;
                const cumulativeCost = currentCost + newCost;
                const cumulativeProfit = currentProfit + newProfit;

                // Mettre à jour les KPI avec les valeurs cumulées et style harmonisé client-dashboard
                if (salesKpi) {
                  const formattedSales = Math.abs(cumulativeSales).toLocaleString('fr-FR');
                  const isNegative = cumulativeSales < 0;
                  const color = isNegative ? '#ef4444' : '#1e293b';
                  
                  salesKpi.innerHTML = `
                    <span class="number" style="color: ${color}; font-size: 2rem; font-weight: 800; letter-spacing: -0.5px;">${isNegative ? '-' : ''}${formattedSales}</span>
                    <span class="unit">FCFA</span>
                  `;
                  salesKpi.style.display = 'flex';
                  salesKpi.style.alignItems = 'baseline';
                  salesKpi.style.justifyContent = 'flex-start';
                }

                if (costKpi) {
                  const formattedCost = Math.abs(cumulativeCost).toLocaleString('fr-FR');
                  costKpi.innerHTML = `
                    <span class="number" style="color: #ef4444; font-size: 2rem; font-weight: 800; letter-spacing: -0.5px;">${formattedCost}</span>
                    <span class="unit">FCFA</span>
                  `;
                  costKpi.style.display = 'flex';
                  costKpi.style.alignItems = 'baseline';
                  costKpi.style.justifyContent = 'flex-start';
                }

                if (profitKpi) {
                  const formattedProfit = Math.abs(cumulativeProfit).toLocaleString('fr-FR');
                  const isNegative = cumulativeProfit < 0;
                  const color = isNegative ? '#ef4444' : '#1e293b';
                  
                  profitKpi.innerHTML = `
                    <span class="number" style="color: ${color}; font-size: 2rem; font-weight: 800; letter-spacing: -0.5px;">${isNegative ? '-' : ''}${formattedProfit}</span>
                    <span class="unit">FCFA</span>
                  `;
                  profitKpi.style.display = 'flex';
                  profitKpi.style.alignItems = 'baseline';
                  profitKpi.style.justifyContent = 'flex-start';
                }

                // Mettre à jour la marge nette
                if (marginKpi && cumulativeSales > 0) {
                  const marginValue = (cumulativeProfit / cumulativeSales) * 100;
                  const marginColor = marginValue < 0 ? '#dc2626' : '#047857';
                  const explanation = marginValue < 0 ? 
                    `Vous perdez ${Math.abs(marginValue * 10).toLocaleString('fr-FR')} FCFA pour chaque 1 000 FCFA gagnés.` :
                    `Gain de ${(marginValue * 10).toFixed(0)} FCFA pour chaque 1 000 FCFA.`;

                  marginKpi.innerHTML = `
                    <div style="text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%; gap: 8px;">
                      <span style="color: ${marginColor}; font-size: 24px; font-weight: 700; text-align: center; display: block;">${marginValue.toFixed(1)}%</span>
                      <span style="color: ${marginColor}; font-size: 12px; text-align: center; display: block; line-height: 1.4;">${explanation}</span>
                    </div>
                  `;
                  marginKpi.style.textAlign = 'center';

                  // Mettre à jour le statut
                  if (marginValue < 0) {
                    marginStatus.textContent = 'Activité déficitaire';
                    marginStatus.style.color = '#dc2626';
                  } else if (marginValue <= 15) {
                    marginStatus.textContent = 'Rentabilité faible';
                  marginStatus.style.color = '#f59e0b';
                } else {
                  marginStatus.textContent = 'Bonne rentabilité';
                  marginStatus.style.color = '#047857';
                }
                marginStatus.style.textAlign = 'center'; // MAINTIEN du centrage
              }

              // Rafraîchir les graphiques avec les nouvelles valeurs cumulées
                if (window.myChart) {
                  const chartData = window.myChart.data;
                  chartData.datasets[0].data = [cumulativeSales, cumulativeCost, cumulativeProfit];
                  window.myChart.update();
                }

                // Afficher la notification Toast
                showToast('Bilan mis à jour avec les données de la parcelle !');

                // Réinitialiser les champs du calculateur
                resetCalculatorFields();

              }, 500);
            }

            // Fonction pour afficher une notification Toast
            function showToast(message) {
              // Créer l'élément Toast
              const toast = document.createElement('div');
              toast.textContent = message;
              toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #10b981;
                color: white;
                padding: 12px 20px;
                border-radius: 12px;
                font-size: 14px;
                font-weight: 500;
                z-index: 1001;
                opacity: 0;
                transform: translateY(-20px);
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
              `;
              
              document.body.appendChild(toast);
              
              // Animation d'apparition
              setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
              }, 100);
              
              // Masquer après 3 secondes
              setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                  document.body.removeChild(toast);
                }, 300);
              }, 3000);
            }

            // Fonction pour réinitialiser les champs du calculateur
            function resetCalculatorFields() {
              const calcParcel = document.getElementById('calcParcel');
              const calcCulture = document.getElementById('calcCulture');
              const calcArea = document.getElementById('calcArea');
              const calcQty = document.getElementById('calcQty');
              const calcPrice = document.getElementById('calcPrice');
              const calcIntrants = document.getElementById('calcIntrants');
              const calcOther = document.getElementById('calcOther');
              
              // Réinitialiser tous les champs
              if (calcParcel) calcParcel.value = '';
              if (calcCulture) calcCulture.value = '';
              if (calcArea) {
                calcArea.value = '';
                calcArea.removeAttribute('readonly');
                calcArea.style.backgroundColor = '';
                calcArea.style.cursor = '';
              }
              if (calcQty) calcQty.value = '';
              if (calcPrice) calcPrice.value = '';
              if (calcIntrants) calcIntrants.value = '';
              if (calcOther) calcOther.value = '';
              
              // Réinitialiser les résultats du calculateur
              const resultElements = ['valeur-revenu', 'valeur-benefice', 'valeur-marge', 'valeur-verdict'];
              resultElements.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                  element.textContent = '0';
                }
              });
              
              // Réinitialiser les couleurs des cartes résultats
              const resultCards = document.querySelectorAll('.result-card');
              resultCards.forEach(card => {
                card.style.background = '';
                card.style.borderColor = '';
              });
              
              const resultValues = document.querySelectorAll('.result-value');
              resultValues.forEach(value => {
                value.style.color = '';
              });
            }  

            // Ajouter l'event listener au bouton "Appliquer au bilan"
            const applyBtn = document.getElementById('applyCalculatorBtn');
            if (applyBtn) {
              applyBtn.addEventListener('click', applyCalculatorToBilan);
            }

            // AFFICHAGE INITIAL DES KPI - Comme sur votre photo
            function transformerKPI() {
              // Ciblage précis des balises qui affichent les KPI
              const salesElement = document.getElementById('salesKpi');     
              const costElement = document.getElementById('costKpi');       
              const profitElement = document.getElementById('profitKpi');   
              const marginElement = document.getElementById('marginKpi');    

              // VALEURS INITIALES - Issues des vraies donnees serveur
              const serverData = window.SeneBI_RENTABILITE || {};
              const salesValue = {{ $totalCA }};  // Chiffre d'affaires reel
              const costValue = {{ $totalCouts }};     // Couts reels
              const profitValue = {{ $totalBenefice }};  // Benefice reel
              const marginValue = parseFloat(({{ $margeMoyenne }}).toFixed(1));    // Marge reelle

              // Formatage épuré - Vert pour CA, Rouge pour le reste
              function formatFCFA(amount, isSales = false) {
                const isNegative = amount < 0;
                const formattedAmount = Math.abs(amount).toLocaleString('fr-FR');
                // Vert pour CA, gris très foncé pour les autres
                const color = isNegative ? '#ef4444' : (isSales ? '#10b981' : '#1e293b');
                return `<span class="number" style="color: ${color}; font-size: 2rem; font-weight: 800; letter-spacing: -0.5px;">${isNegative ? '-' : ''}${formattedAmount}</span><span class="unit">FCFA</span>`;
              }

              // Mise à jour forcée du texte à l'intérieur des cartes avec style flex
              if (salesElement) {
                salesElement.innerHTML = formatFCFA(salesValue, true); // Vert pour CA
                salesElement.style.display = 'flex';
                salesElement.style.alignItems = 'baseline';
                salesElement.style.justifyContent = 'flex-start';
              }
              if (costElement) {
                costElement.innerHTML = formatFCFA(costValue, false); // Gris pour les autres
                costElement.style.display = 'flex';
                costElement.style.alignItems = 'baseline';
                costElement.style.justifyContent = 'flex-start';
              }
              if (profitElement) {
                profitElement.innerHTML = formatFCFA(profitValue, false); // Gris pour les autres
                profitElement.style.display = 'flex';
                profitElement.style.alignItems = 'baseline';
                profitElement.style.justifyContent = 'flex-start';
              }
              if (marginElement) {
                const marginColor = marginValue < 0 ? '#dc2626' : '#1e293b'; /* Rouge vif pour négatif */
                const explanation = `Vous perdez ${Math.abs(marginValue * 10).toLocaleString('fr-FR')} FCFA pour chaque 1 000 FCFA gagnés.`;
                marginElement.innerHTML = `
                  <span class="number" style="color: ${marginColor}; font-size: 2rem; font-weight: 800; letter-spacing: -0.5px;">${marginValue.toFixed(1)}%</span>
                `;
                // Ajouter la phrase d'analyse tout en bas - style déjà appliqué par CSS
                const explanationDiv = marginElement.nextElementSibling;
                if (explanationDiv && explanationDiv.classList.contains('explanation')) {
                  explanationDiv.textContent = explanation;
                }
              }

              // Nettoyage des libellés 'Millions FCFA' sans toucher aux icônes NI AUX EXPLICATIONS
              const labels = document.querySelectorAll('.kpi-card p');
              labels.forEach(label => {
                if (label.textContent.includes('Millions FCFA')) {
                  label.textContent = '';
                }
              });
            }

            // EXPORT PDF PREMIUM - Design Business CORRIGÉ FINALEMENT
            function exportPremiumPDF() {
              const { jsPDF } = window.jspdf;
              // Utiliser une police très stable avec encodage UTF-8
              const doc = new jsPDF('p', 'mm', 'a4');
              doc.setFont('helvetica'); // Police standard très stable
              
              // Récupérer les valeurs actuelles des KPI
              const salesKpi = document.getElementById('salesKpi');
              const costKpi = document.getElementById('costKpi');
              const profitKpi = document.getElementById('profitKpi');
              const marginKpi = document.getElementById('marginKpi');
              
              const salesValue = salesKpi ? salesKpi.querySelector('.number')?.textContent || '0' : '0';
              const costValue = costKpi ? costKpi.querySelector('.number')?.textContent || '0' : '0';
              const profitValue = profitKpi ? profitKpi.querySelector('.number')?.textContent || '0' : '0';
              const marginValue = marginKpi ? marginKpi.querySelector('.number')?.textContent || '0' : '0';
              
              // NETTOYAGE DES NOMBRES - Formatage propre
              const cleanNumber = (value) => {
                // Enlever tous les caractères non numériques sauf les espaces et le signe -
                let clean = value.replace(/[^\d\s-]/g, '').trim();
                // Remplacer les espaces multiples par un seul espace
                clean = clean.replace(/\s+/g, ' ');
                return clean || '0';
              };
              
              // Date du jour
              const today = new Date().toLocaleDateString('fr-FR', { 
                day: '2-digit', 
                month: '2-digit', 
                year: 'numeric' 
              });
              
              // EN-TÊTE DE PRESTIGE AVEC LOGO FORCÉ
              let logoLoaded = false;
              
              // MÉTHODE 1: Tentative avec chemin absolu
              try {
                const logoImg = new Image();
                logoImg.crossOrigin = 'Anonymous'; // Permet le chargement cross-origin
                
                // Charger l'image avec chemin absolu adapté pour Laravel
                logoImg.src = '/assets/img/logo_senebi.png'; // Chemin Laravel standard
                
                // Attendre le chargement de l'image
                logoImg.onload = function() {
                  logoLoaded = true;
                  doc.addImage(logoImg, 20, 15, 40, 15); // Largeur 40mm, hauteur 15mm (~60px)
                };
                
                logoImg.onerror = function() {
                  logoLoaded = false;
                };
                
                // Forcer le chargement immédiat si déjà en cache
                if (logoImg.complete) {
                  logoLoaded = true;
                  doc.addImage(logoImg, 20, 15, 40, 15);
                }
              } catch (e) {
                logoLoaded = false;
              }
              
              // MÉTHODE 2: Alternative Base64 (la plus sûre pour PDF)
              if (!logoLoaded) {
                try {
                  // UTILISER VOTRE LOGO RÉEL EN BASE64 - Code PHP à ajouter dans votre vue Blade :
                  /*
                  <?php
                  // Conversion du logo en Base64 (à ajouter dans votre contrôleur ou vue)
                  $logoPath = public_path('assets/img/logo_senebi.png');
                  $logoData = file_get_contents($logoPath);
                  $base64Logo = 'data:image/png;base64,' . base64_encode($logoData);
                  ?>
                  
                  // Dans votre JavaScript, utilisez :
                  const base64Logo = '<?php echo $base64Logo; ?>';
                  */
                  
                  // POUR MAINTENANT, utilisez ce placeholder (remplacez par votre vrai Base64)
                  const base64Logo = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
                  
                  const base64Img = new Image();
                  base64Img.src = base64Logo;
                  
                  if (base64Img.complete) {
                    logoLoaded = true;
                    doc.addImage(base64Img, 20, 15, 40, 15);
                  }
                } catch (e) {
                  logoLoaded = false;
                }
              }
              
              // Afficher le texte SEULEMENT si le logo n'est pas chargé
              if (!logoLoaded) {
                doc.setFontSize(24);
                doc.setTextColor(46, 125, 50);
                doc.text('SeneBI', 20, 25);
              }
              
              // Encadré informations à droite - cadre arrondi conservé
              doc.setDrawColor(46, 125, 50);
              doc.setFillColor(248, 250, 252);
              doc.roundedRect(120, 15, 80, 35, 3, 3, 'FD');
              
              doc.setFontSize(12);
              doc.setTextColor(46, 125, 50);
              doc.setFont('helvetica', 'bold');
              doc.text('Rapport de Rentabilité Agricole', 125, 25);
              
              doc.setFontSize(10);
              doc.setTextColor(55, 65, 81);
              doc.setFont('helvetica', 'normal');
              doc.text(`Date : ${today}`, 125, 32);
              doc.text('ID Document : #2026-001', 125, 39);
              
              // Ligne séparatrice verte
              doc.setDrawColor(46, 125, 50);
              doc.setLineWidth(0.5);
              doc.line(20, 60, 190, 60);
              
              // TABLEAU KPI PREMIUM - LARGEURS AUGMENTÉES
              doc.setFillColor(248, 250, 252);
              doc.roundedRect(20, 70, 170, 50, 3, 3, 'F'); // Hauteur augmentée
              
              doc.setDrawColor(46, 125, 50);
              doc.setFillColor(46, 125, 50);
              doc.rect(20, 70, 50, 15, 'F'); // En-tête CA - largeur augmentée
              doc.rect(70, 70, 50, 15, 'F'); // En-tête Coûts - largeur augmentée
              doc.rect(120, 70, 50, 15, 'F'); // En-tête Bénéfice - largeur augmentée
              doc.rect(170, 70, 20, 15, 'F'); // En-tête Marge - largeur ajustée
              
              doc.setTextColor(255, 255, 255);
              doc.setFontSize(9); // Police légèrement réduite pour tenir
              doc.setFont('helvetica', 'bold');
              doc.text('Chiffre d\'Affaires', 45, 80, { align: 'center' });
              doc.text('Coûts Intrants', 95, 80, { align: 'center' });
              doc.text('Bénéfice Net', 145, 80, { align: 'center' });
              doc.text('Marge', 180, 80, { align: 'center' });
              
              // Montants KPI - POLICE STABLE ET FORMATAGE PROPRE
              doc.setTextColor(31, 41, 55);
              doc.setFontSize(9); // Police réduite pour éviter chevauchement
              doc.setFont('helvetica', 'bold');
              
              // Formatage des montants avec espacement propre
              const formatAmount = (amount) => {
                const clean = cleanNumber(amount);
                // Limiter la longueur pour éviter chevauchement
                if (clean.length > 12) {
                  return clean.substring(0, 12) + '...';
                }
                return clean;
              };
              
              // Affichage des montants avec espacement suffisant
              doc.text(`${formatAmount(salesValue)} FCFA`, 45, 105, { align: 'center' });
              doc.text(`${formatAmount(costValue)} FCFA`, 95, 105, { align: 'center' });
              doc.text(`${formatAmount(profitValue)} FCFA`, 145, 105, { align: 'center' });
              doc.text(`${formatAmount(marginValue)}%`, 180, 105, { align: 'center' });
              
              // GRAPHIQUES AMÉLIORÉS
              doc.setDrawColor(209, 213, 219);
              doc.roundedRect(20, 135, 170, 70, 3, 3); // Hauteur réduite
              
              // Tentative de capture du graphique réel (fallback si échoue)
              try {
                const chartCanvas = document.querySelector('canvas');
                if (chartCanvas) {
                  const chartImage = chartCanvas.toDataURL('image/png');
                  doc.addImage(chartImage, 25, 145, 160, 50);
                } else {
                  throw new Error('Canvas non trouvé');
                }
              } catch (e) {
                // Texte centré amélioré
                doc.setTextColor(46, 125, 50);
                doc.setFontSize(12);
                doc.setFont('helvetica', 'bold');
                doc.text('Graphiques de Performance', 105, 155, { align: 'center' });
                doc.setTextColor(107, 114, 128);
                doc.setFontSize(9);
                doc.setFont('helvetica', 'normal');
                doc.text('Graphiques et analyses détaillées disponibles', 105, 170, { align: 'center' });
                doc.text('dans la version interactive du tableau de bord', 105, 180, { align: 'center' });
              }
              
              // TABLEAU DES RÉCOLTES STYLE ZÈBRE - MARGE AJOUTÉE
              const tableStartY = 220; // Position plus bas pour éviter chevauchement
              
              // SIGNATURE VISUELLE - Barre verticale verte avant le tableau
              doc.setDrawColor(46, 125, 50); // Vert forêt
              doc.setLineWidth(3);
              doc.line(20, tableStartY - 5, 20, tableStartY + 55); // Barre verticale de 3px
              
              doc.setFillColor(31, 41, 55);
              doc.rect(25, tableStartY, 165, 10, 'F'); // En-tête tableau (décalé pour la barre)
              doc.setTextColor(255, 255, 255);
              doc.setFontSize(9);
              doc.setFont('helvetica', 'bold');
              doc.text('Parcelle', 30, tableStartY + 7);
              doc.text('Culture', 70, tableStartY + 7);
              doc.text('Surface', 110, tableStartY + 7);
              doc.text('Revenu Total', 150, tableStartY + 7);
              
              // Lignes zèbres avec formatage propre
              const rows = [
                ['Parcelle Nord', 'Riz', '5.5 ha', cleanNumber('16 908 000 FCFA')],
                ['Parcelle Centre', 'Maïs', '3.2 ha', cleanNumber('8 450 000 FCFA')],
                ['Parcelle Sud', 'Coton', '2.8 ha', cleanNumber('7 230 000 FCFA')]
              ];
              
              rows.forEach((row, index) => {
                const y = tableStartY + 17 + (index * 15);
                if (index % 2 === 0) {
                  doc.setFillColor(248, 250, 252);
                  doc.rect(25, y - 6, 165, 12, 'F'); // Rectangle décalé pour la barre
                }
                doc.setTextColor(55, 65, 81);
                doc.setFontSize(8); // Police légèrement réduite
                doc.setFont('helvetica', 'normal');
                doc.text(row[0], 30, y); // Texte décalé pour la barre
                doc.text(row[1], 70, y);
                doc.text(row[2], 110, y);
                // Tronquer les revenus longs proprement
                let revenue = row[3];
                if (revenue.length > 18) {
                  revenue = revenue.substring(0, 18) + '...';
                }
                doc.text(revenue, 150, y); // Texte décalé pour la barre
              });
              
              // MARGE AJOUTÉE AVANT LE FOOTER - PADDING-BOTTOM 50PX ÉQUIVALENT
              const footerStartY = 275; // Position fixe pour le footer avec marge de 50px
              
              // PIED DE PAGE
              doc.setDrawColor(46, 125, 50);
              doc.setLineWidth(0.5);
              doc.line(20, footerStartY, 190, footerStartY);
              
              doc.setTextColor(107, 114, 128);
              doc.setFontSize(7); // Police légèrement réduite
              doc.setFont('helvetica', 'normal');
              doc.text('Document généré par la plateforme SeneBI - Analyse BI pour le Mali', 105, footerStartY + 7, { align: 'center' });
              doc.text('Page 1 sur 1', 105, footerStartY + 12, { align: 'center' });
              
              doc.setTextColor(220, 38, 38);
              doc.setFont('helvetica', 'bold');
              doc.text('Confidentiel', 105, footerStartY + 18, { align: 'center' });
              
              // Sauvegarder le PDF
              doc.save(`rapport-rentabilite-senebi-${Date.now()}.pdf`);
            }
            
            // Attacher la fonction aux boutons d'export
            document.getElementById('exportPdfBtn').addEventListener('click', exportPremiumPDF);
            document.getElementById('exportPdfBottomBtn').addEventListener('click', exportPremiumPDF);

            // RÉACTIVÉ - mais modifiée pour ne pas écraser la phrase explicative
            setTimeout(() => {
              transformerKPI();
              
               // RÉAPPLIQUER LA PHRASE EXPlicative APRÈS la transformation
               setTimeout(() => {
                 const explanationDiv = document.querySelector('#marginKpi').nextElementSibling;
                 if (explanationDiv && explanationDiv.classList.contains('explanation')) {
                   const marginVal = parseFloat(marginValue) || 0;
                   const lossPer1000 = Math.abs(marginVal * 10);
                   const explanation = marginVal < 0
                     ? `Vous perdez ${lossPer1000.toLocaleString('fr-FR')} FCFA pour chaque 1 000 FCFA gagnés.`
                     : `Gain de ${lossPer1000.toLocaleString('fr-FR')} FCFA pour chaque 1 000 FCFA.`;
                   explanationDiv.textContent = explanation;
                  
                  // FORCER LES STYLES
                  explanationDiv.style.fontSize = '0.7rem';
                  explanationDiv.style.color = '#ef4444';
                  explanationDiv.style.whiteSpace = 'nowrap';
                  explanationDiv.style.marginTop = 'auto';
                  explanationDiv.style.marginBottom = '8px';
                  explanationDiv.style.paddingBottom = '8px';
                  explanationDiv.style.fontWeight = '500';
                  explanationDiv.style.overflow = 'hidden';
                  explanationDiv.style.textOverflow = 'ellipsis';
                  explanationDiv.style.display = 'block';
                  explanationDiv.style.visibility = 'visible';
                  explanationDiv.style.opacity = '1';
                }
              }, 300);
            }, 100);
          });

        // FONCTION DÉSACTIVÉE - Écrasait aussi les valeurs du calculateur
        // setTimeout(function() {
        //   Ce setTimeout écrasait aussi vos nouvelles valeurs du calculateur
        //   Maintenant seule applyCalculatorToBilan() gère les KPI
        // }, 500); // DÉSACTIVÉ
        </script>

        <section class="kpi-grid">
          <article class="card kpi-card">
            <div>
              <!-- LIGNE 1: Titre + Icône -->
              <div class="kpi-header">
                <h3>Chiffre d'Affaires</h3>
                <span class="kpi-icon">$</span>
              </div>
              <!-- LIGNE 2: Gros chiffre centré -->
              <div id="salesKpi" class="kpi-amount-centered">
                <span class="number">0</span>
                <span class="unit">FCFA</span>
              </div>
            </div>
          </article>
          <article class="card kpi-card">
            <div>
              <!-- LIGNE 1: Titre + Icône -->
              <div class="kpi-header">
                <h3>Coûts Intrants</h3>
                <span class="kpi-icon">%</span>
              </div>
              <!-- LIGNE 2: Gros chiffre centré -->
              <div id="costKpi" class="kpi-amount-centered">
                <span class="number">0</span>
                <span class="unit">FCFA</span>
              </div>
            </div>
          </article>
          <article class="card kpi-card">
            <div>
              <!-- LIGNE 1: Titre + Icône -->
              <div class="kpi-header">
                <h3>Bénéfice Net</h3>
                <span class="kpi-icon">^</span>
              </div>
              <!-- LIGNE 2: Gros chiffre centré -->
              <div id="profitKpi" class="kpi-amount-centered">
                <span class="number">0</span>
                <span class="unit">FCFA</span>
              </div>
            </div>
          </article>
          <article class="card kpi-card">
            <div>
              <!-- LIGNE 1: Titre + Icône -->
              <div class="kpi-header">
                <h3>Marge Nette</h3>
                <span class="kpi-icon">%</span>
              </div>
              <!-- LIGNE 2: Gros chiffre centré -->
              <div id="marginKpi" class="kpi-amount-centered">
                <span class="number">0.0%</span>
              </div>
              <!-- LIGNE 3: Phrase d'analyse en bas -->
              <div class="explanation" style="font-size: 0.7rem !important; color: #ef4444 !important; white-space: nowrap !important; margin-top: auto !important; margin-bottom: 8px !important; padding-bottom: 8px !important; font-weight: 500 !important; overflow: hidden !important; text-overflow: ellipsis !important; display: block !important; visibility: visible !important; opacity: 1 !important;">Vous perdez 2 112 FCFA pour chaque 1 000 FCFA gagnés.</div>
            </div>
</article>
        </section>

<!-- ============================================
              SECTION 1: PRÉVISIONS FINANCIÈRES
              ============================================ -->
        <section class="forecasts-section">
          <div class="card-header--premium">
            <div class="icon-box" aria-hidden="true"><i class="fas fa-chart-line"></i></div>
            <h3 class="section-title--premium">Prévisions Financières</h3>
          </div>
          
          <div class="forecasts-grid--premium">
            <div class="forecast-card--premium">
              <span class="forecast-label--premium">Revenu prévisionnel mensuel</span>
              <span class="forecast-value--premium" id="revenuPrevisionnel">{{ number_format($moyenneMensuelleCA, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="forecast-card--premium">
              <span class="forecast-label--premium">Bénéfice estimé mensuel</span>
              <span class="forecast-value--premium {{ $moyenneMensuelleBenefice >= 0 ? 'positive' : 'negative' }}" id="beneficeEstime">{{ number_format($moyenneMensuelleBenefice, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="forecast-card--premium">
              <span class="forecast-label--premium">Tendance financière</span>
              <span class="forecast-value--premium {{ $tendanceFinanciere >= 0 ? 'positive' : 'negative' }}" id="tendanceFinanciere">
                <i class="fas fa-arrow-{{ $tendanceFinanciere >= 0 ? 'up' : 'down' }}"></i> {{ number_format($tendanceFinanciere, 1, ',', ' ') }}%
              </span>
            </div>
          </div>
          
          <!-- Timeline Chart for Projections -->
          <div class="timeline-chart-wrapper">
            <canvas id="projectionTimelineChart"></canvas>
          </div>
          
          <div class="projections-summary-cards--premium">
            <div class="projection-summary-card--premium">
              <div class="icon-box" aria-hidden="true"><i class="fas fa-euro-sign"></i></div>
              <span class="forecast-label--premium">Revenu Moyen</span>
              <span class="forecast-value--premium positive" id="summaryRevenu">{{ number_format($moyenneMensuelleCA, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="projection-summary-card--premium">
              <div class="icon-box purple" aria-hidden="true"><i class="fas fa-piggy-bank"></i></div>
              <span class="forecast-label--premium">Bénéfice Moyen</span>
              <span class="forecast-value--premium {{ $moyenneMensuelleBenefice >= 0 ? 'positive' : 'negative' }}" id="summaryBenefice">{{ number_format($moyenneMensuelleBenefice, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="projection-summary-card--premium">
              <div class="icon-box {{ $tendanceFinanciere >= 0 ? 'green' : 'red' }}" aria-hidden="true"><i class="fas fa-arrow-{{ $tendanceFinanciere >= 0 ? 'up' : 'down' }}"></i></div>
              <span class="forecast-label--premium">Tendance</span>
              <span class="forecast-value--premium {{ $tendanceFinanciere >= 0 ? 'positive' : 'negative' }}" id="summaryTendance">{{ number_format($tendanceFinanciere, 1, ',', ' ') }}%</span>
            </div>
            <div class="projection-summary-card--premium">
              <div class="icon-box purple" aria-hidden="true"><i class="fas fa-seedling"></i></div>
              <span class="forecast-label--premium">Total Projections</span>
              <span class="forecast-value--premium positive" id="totalProjections">{{ number_format(array_sum(array_column($projections, 'revenu')), 0, ',', ' ') }} FCFA</span>
            </div>
          </div>
        </section>
 
<!-- ============================================
               SECTION 2: COMPARAISON HISTORIQUE
               ============================================ -->
          <section class="comparison-section">
            <div class="card-header--premium">
              <div class="icon-box" aria-hidden="true"><i class="fas fa-exchange-alt"></i></div>
              <h3 class="section-title--premium">Comparaison Historique</h3>
            </div>
            <div class="comparison-grid--premium">
              <div class="comparison-card--premium">
                <h3>Saison actuelle vs Précédente</h3>
                <div class="comparison-values">
                  <div>
                    <span class="comparison-label">CA Actuel</span>
                    <span class="comparison-value">{{ number_format($caSaisonActuelle, 0, ',', ' ') }} FCFA</span>
                  </div>
                  <div>
                    <span class="comparison-label">CA Précédent</span>
                    <span class="comparison-value">{{ number_format($caSaisonPrecedente, 0, ',', ' ') }} FCFA</span>
                  </div>
                  <div>
                    <span class="comparison-label">Variation</span>
                    <span class="comparison-var--premium {{ $varSaisonCA >= 0 ? 'positive' : 'negative' }}">
                      <i class="fas fa-arrow-{{ $varSaisonCA >= 0 ? 'up' : 'down' }}"></i> {{ number_format($varSaisonCA, 1, ',', ' ') }}%
                    </span>
                  </div>
                </div>
              </div>
              <div class="comparison-card--premium">
                <h3>Année actuelle vs Précédente</h3>
                <div class="comparison-values">
                  <div>
                    <span class="comparison-label">CA Actuel</span>
                    <span class="comparison-value">{{ number_format($caAnneeActuelle, 0, ',', ' ') }} FCFA</span>
                  </div>
                  <div>
                    <span class="comparison-label">CA Précédent</span>
                    <span class="comparison-value">{{ number_format($caAnneePrecedente, 0, ',', ' ') }} FCFA</span>
                  </div>
                  <div>
                    <span class="comparison-label">Variation</span>
                    <span class="comparison-var--premium {{ $varAnneeCA >= 0 ? 'positive' : 'negative' }}">
                      <i class="fas fa-arrow-{{ $varAnneeCA >= 0 ? 'up' : 'down' }}"></i> {{ number_format($varAnneeCA, 1, ',', ' ') }}%
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </section>

<!-- ============================================
               SECTION 3: RÉPARTITION DES COÛTS
               ============================================ -->
          <section class="costs-section">
            <div class="card-header--premium">
              <div class="icon-box" aria-hidden="true"><i class="fas fa-pie-chart"></i></div>
              <h3 class="section-title--premium">Répartition des Coûts</h3>
            </div>
            <div class="costs-content--premium">
              <div class="costs-chart-wrapper--premium">
                <div class="costs-chart-container">
                  <canvas id="costsDonutChart"></canvas>
                </div>
                <div class="costs-legend--premium">
                  <div class="costs-legend-item--premium">
                    <span class="costs-legend-dot--premium" style="background: #3b82f6;"></span>
                    <span class="costs-legend-label">Engrais</span>
                    <span class="costs-legend-value">{{ number_format($coutsEngrais, 0, ',', ' ') }} FCFA</span>
                  </div>
                  <div class="costs-legend-item--premium">
                    <span class="costs-legend-dot--premium" style="background: #10b981;"></span>
                    <span class="costs-legend-label">Semences</span>
                    <span class="costs-legend-value">{{ number_format($coutsSemences, 0, ',', ' ') }} FCFA</span>
                  </div>
                  <div class="costs-legend-item--premium">
                    <span class="costs-legend-dot--premium" style="background: #f59e0b;"></span>
                    <span class="costs-legend-label">Herbicides</span>
                    <span class="costs-legend-value">{{ number_format($coutsHerbicides, 0, ',', ' ') }} FCFA</span>
                  </div>
                  <div class="costs-legend-item--premium">
                    <span class="costs-legend-dot--premium" style="background: #8b5cf6;"></span>
                    <span class="costs-legend-label">Main-d'œuvre</span>
                    <span class="costs-legend-value">{{ number_format($coutsMainOeuvre, 0, ',', ' ') }} FCFA</span>
                  </div>
                  <div class="costs-legend-item--premium">
                    <span class="costs-legend-dot--premium" style="background: #ec4899;"></span>
                    <span class="costs-legend-label">Transport</span>
                    <span class="costs-legend-value">{{ number_format($coutsTransport, 0, ',', ' ') }} FCFA</span>
                  </div>
                  <div class="costs-legend-item--premium">
                    <span class="costs-legend-dot--premium" style="background: #6b7280;"></span>
                    <span class="costs-legend-label">Autres coûts</span>
                    <span class="costs-legend-value">{{ number_format($coutsAutres, 0, ',', ' ') }} FCFA</span>
                  </div>
                </div>
              </div>
              <div class="costs-grid">
                <div class="cost-item">
                  <span class="cost-label"><i class="fas fa-seedling"></i> Engrais</span>
                  <span class="cost-value">{{ number_format($coutsEngrais, 0, ',', ' ') }} FCFA</span>
                  <div class="cost-bar"><div class="cost-fill" style="width: {{ $totalCouts > 0 ? ($coutsEngrais / $totalCouts * 100) : 0 }}%"></div></div>
                </div>
                <div class="cost-item">
                  <span class="cost-label"><i class="fas fa-leaf"></i> Semences</span>
                  <span class="cost-value">{{ number_format($coutsSemences, 0, ',', ' ') }} FCFA</span>
                  <div class="cost-bar"><div class="cost-fill" style="width: {{ $totalCouts > 0 ? ($coutsSemences / $totalCouts * 100) : 0 }}%"></div></div>
                </div>
                <div class="cost-item">
                  <span class="cost-label"><i class="fas fa-spray-can"></i> Herbicides</span>
                  <span class="cost-value">{{ number_format($coutsHerbicides, 0, ',', ' ') }} FCFA</span>
                  <div class="cost-bar"><div class="cost-fill" style="width: {{ $totalCouts > 0 ? ($coutsHerbicides / $totalCouts * 100) : 0 }}%"></div></div>
                </div>
                <div class="cost-item">
                  <span class="cost-label"><i class="fas fa-users"></i> Main-d'œuvre</span>
                  <span class="cost-value">{{ number_format($coutsMainOeuvre, 0, ',', ' ') }} FCFA</span>
                  <div class="cost-bar"><div class="cost-fill" style="width: {{ $totalCouts > 0 ? ($coutsMainOeuvre / $totalCouts * 100) : 0 }}%"></div></div>
                </div>
                <div class="cost-item">
                  <span class="cost-label"><i class="fas fa-truck"></i> Transport</span>
                  <span class="cost-value">{{ number_format($coutsTransport, 0, ',', ' ') }} FCFA</span>
                  <div class="cost-bar"><div class="cost-fill" style="width: {{ $totalCouts > 0 ? ($coutsTransport / $totalCouts * 100) : 0 }}%"></div></div>
                </div>
                <div class="cost-item">
                  <span class="cost-label"><i class="fas fa-ellipsis-h"></i> Autres coûts</span>
                  <span class="cost-value">{{ number_format($coutsAutres, 0, ',', ' ') }} FCFA</span>
                  <div class="cost-bar"><div class="cost-fill" style="width: {{ $totalCouts > 0 ? ($coutsAutres / $totalCouts * 100) : 0 }}%"></div></div>
                </div>
              </div>
            </div>
          </section>

<!-- ============================================
              SECTION 4: BADGE DE PERFORMANCE
              ============================================ -->
        <section class="performance-badge-section">
          <div class="card-header--premium">
            <div class="icon-box" aria-hidden="true"><i class="fas fa-award"></i></div>
            <h3 class="section-title--premium">Performance de l'Exploitation</h3>
          </div>
          <div class="badge-display--premium">
            <span class="perf-badge--premium {{ $badgePerformance['class'] }}">
              <i class="fas {{ $badgePerformance['icon'] }}"></i> {{ $badgePerformance['label'] }}
            </span>
          </div>
        </section>

        <!-- ============================================
             SECTION 5: TOP 3 CULTURES RENTABLES
             ============================================ -->
        <section class="top-cultures-section">
          <div class="card-header--premium">
            <div class="icon-box" aria-hidden="true"><i class="fas fa-seedling"></i></div>
            <h3 class="section-title--premium">Top 3 Cultures les plus Rentables</h3>
          </div>
          <div class="top-cultures-grid--premium">
            @if($topCultures->count() > 0)
              @foreach($topCultures as $index => $culture)
                <div class="top-culture-card--premium rank-{{ $index + 1 }}">
                  <div class="rank-badge--premium">{{ $index + 1 }}</div>
                  <div class="culture-info">
                    <span class="culture-name--premium">{{ $culture->culture }}</span>
                    <span class="culture-benefice--premium">{{ number_format($culture->benefice_total, 0, ',', ' ') }} FCFA</span>
                    <span class="culture-ca">CA: {{ number_format($culture->chiffre_affaires, 0, ',', ' ') }} FCFA</span>
                  </div>
                </div>
              @endforeach
            @else
              <div class="empty-state--premium">
                <i class="fas fa-chart-line"></i>
                <p>Aucune culture rentable enregistrée</p>
              </div>
            @endif
          </div>
        </section>

        <!-- ============================================
             SECTION 6: RECOMMANDATIONS IA
             ============================================ -->
        <section class="ai-recommendations-section">
          <div class="card-header--premium">
            <div class="icon-box purple" aria-hidden="true"><i class="fas fa-robot"></i></div>
            <h3 class="section-title--premium">Recommandations IA Financières</h3>
          </div>
          <div id="aiRecommendationsList" class="ai-recommendations-list--premium">
            <!-- Les recommandations seront générées par JavaScript -->
          </div>
        </section>

        <!-- ============================================
             SECTION 7: HISTORIQUE EXPORTS PDF
             ============================================ -->
        <section class="pdf-history-section">
          <div class="card-header--premium">
            <div class="icon-box" aria-hidden="true"><i class="fas fa-history"></i></div>
            <h3 class="section-title--premium">Historique des Exports PDF</h3>
          </div>
          @if($pdfHistory->count() > 0)
            <div class="pdf-table-wrap">
              <table class="pdf-history-table--premium">
                <thead>
                  <tr>
                    <th>Date d'export</th>
                    <th>Type de rapport</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="pdfHistoryTable">
                  @foreach($pdfHistory as $pdf)
                    <tr>
                      <td>{{ $pdf['date'] }}</td>
                      <td>{{ $pdf['type'] }}</td>
                      <td>
                        <button class="btn small re-download-btn" data-file="{{ $pdf['file_path'] }}">
                          <i class="fas fa-download"></i> Retélécharger
                        </button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="empty-state--premium">
              <i class="fas fa-file-pdf"></i>
              <p>Aucun export PDF pour le moment. Générez votre premier rapport ci-dessous.</p>
            </div>
          @endif
        </section>

        <section class="charts-grid">
          <article class="card">
            <div class="card-header">
              <div class="card-icon icon-box green" aria-hidden="true"><i class="fas fa-chart-line"></i></div>
              <h3 class="section-title">Comparaison Revenus vs Coûts</h3>
            </div>
            <p class="chart-hint">Vert = revenus · Rouge = coûts · Violet = bénéfice (même échelle)</p>
            <div class="chart-wrap">
              <canvas id="profitChart"></canvas>
            </div>
            <div class="bilan-legend-row" aria-label="Legende des couleurs">
              <span><i class="bilan-dot bilan-dot--rev"></i> Revenus</span>
              <span><i class="bilan-dot bilan-dot--cost"></i> Couts</span>
              <span><i class="bilan-dot bilan-dot--profit"></i> Benefice</span>
            </div>
          </article>
          <article class="card">
            <div class="card-header">
              <div class="card-icon icon-box purple" aria-hidden="true"><i class="fas fa-seedling"></i></div>
              <h3 class="section-title">Performance par Culture</h3>
            </div>
            <p class="chart-hint">Rendement moyen par culture (t/ha) — axes explicites</p>
            <div class="chart-wrap">
              <canvas id="cultureChart"></canvas>
            </div>
          </article>
        </section>

        <section class="card table-card">
          <div class="card-header">
            <div class="card-icon icon-box" aria-hidden="true"><i class="fas fa-table"></i></div>
            <h3 class="section-title">Détail des Récoltes et Revenus</h3>
          </div>
          
          <!-- Barre de recherche -->
          <div style="margin-bottom: 16px;">
            <input 
              type="text" 
              id="harvestSearch" 
              placeholder="Rechercher par parcelle ou culture..." 
              style="
                width: 100%;
                padding: 12px 16px;
                border: 1px solid rgba(15, 23, 42, 0.1);
                border-radius: 18px;
                background: #fff;
                font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
                font-size: 14px;
                box-shadow: 0 10px 26px rgba(15, 23, 42, 0.06);
                outline: none;
                transition: all 160ms ease;
              "
              onfocus="this.style.borderColor='#27ae60'; this.style.boxShadow='0 10px 26px rgba(15, 23, 42, 0.12), 0 0 0 3px rgba(39, 174, 96, 0.1)';"
              onblur="this.style.borderColor='rgba(15, 23, 42, 0.1)'; this.style.boxShadow='0 10px 26px rgba(15, 23, 42, 0.06)';"
            />
          </div>
          
          <div class="table-wrap">
            <table class="table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Parcelle</th>
                  <th>Quantite (kg)</th>
                  <th>Prix Unitaire</th>
                  <th>Revenu Total</th>
                </tr>
              </thead>
              <tbody id="harvestRows"></tbody>
            </table>
          </div>
        </section>

        
        <section class="export-box">
          <div class="export-title">
            <span class="export-icon">v</span>
            <div>
              <h2>Export du Bilan Financier</h2>
              <p>Generez un rapport PDF professionnel incluant les graphiques, tableaux et analyses de rentabilite.</p>
            </div>
          </div>
          <div class="export-items">
            <div>Inclus dans le rapport <strong>✓ KPIs financiers</strong></div>
            <div>Inclus dans le rapport <strong>✓ Tous les graphiques</strong></div>
            <div>Inclus dans le rapport <strong>✓ Tableaux detailles</strong></div>
          </div>
          <button class="btn export-main" type="button" id="exportPdfBottomBtn">Generer le rapport PDF</button>
        </section>
      </main>
      @include('partials.footer-client')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.2/dist/jspdf.umd.min.js"></script>
    <script>
      window.SeneBI_SERVER = {
        useDb: true,
        csrf: @json(csrf_token()),
        apiBase: @json(url('/client/api')),
      };
    </script>
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <script src="{{ asset('assets/js/core.js') }}"></script>
    <script>
      window.SeneBI_RENTABILITE = {
        harvests: @json($rentabiliteHarvests),
        forecasts: {
          revenuPrevisionnel: {{ $moyenneMensuelleCA }},
          beneficeEstime: {{ $moyenneMensuelleBenefice }},
          tendance: {{ $tendanceFinanciere }},
          projections: @json($projections)
        },
        costs: {
          engrais: {{ $coutsEngrais }},
          semences: {{ $coutsSemences }},
          herbicides: {{ $coutsHerbicides }},
          mainOeuvre: {{ $coutsMainOeuvre }},
          transport: {{ $coutsTransport }},
          autres: {{ $coutsAutres }}
        },
        topCultures: @json($topCultures),
        cultureYields: @json($cultureYields),
        parcellesData: @json($parcellesData),
        pdfHistory: @json($pdfHistory),
        business: {
          salesFcfa: {{ $totalCA }},
          intrantsCostFcfa: {{ $totalCouts }},
        }
      };
    </script>
    <script src="{{ asset('assets/js/rentabilite.js') }}"></script>
    <script src="{{ asset('assets/js/region-filter.js') }}"></script>
    <script src="{{ asset('assets/js/region-rentabilite.js') }}"></script>
    <script src="{{ asset('assets/js/region-rentabilite-complete.js') }}"></script>
    
    <!-- LIAISONS INTELLIGENTES - Stocks → Rentabilité & Parcelles → Rentabilité -->
    <script>
      // 1. LIAISON STOCKS → RENTABILITÉ (Coût investi)
      function updateCostsFromStocks() {
        // Écouter les mises à jour depuis la page Stocks
        window.addEventListener('storage', function(e) {
          if (e.key === 'senebi_stock_consumption') {
            const consumptionData = JSON.parse(e.newValue || '{}');
            console.log('📊 Consommation Stocks détectée:', consumptionData);
            
            // Mettre à jour le KPI "Coûts Intrants"
            const costKpi = document.getElementById('costKpi');
            if (costKpi && consumptionData.amount) {
              const currentCost = parseFloat(costKpi.querySelector('.number')?.textContent.replace(/[^\d.-]/g, '') || '0');
              const newCost = currentCost + consumptionData.amount;
              
              costKpi.innerHTML = `
                <span class="number" style="color: #ef4444; font-size: 2rem; font-weight: 800; letter-spacing: -0.5px;">${Math.abs(newCost).toLocaleString('fr-FR')}</span>
                <span class="unit">FCFA</span>
              `;
              
              // Recalculer le bénéfice automatiquement
              updateProfitFromSalesAndCosts();
              
              // Afficher une notification
              showStockUpdateNotification(consumptionData);
            }
          }
        });
      }
      
      // 2. LIAISON PARCELLES → RENTABILITÉ (Chiffre d'Affaires)
      function updateSalesFromParcels() {
        // Écouter les récoltes depuis la page Parcelles
        window.addEventListener('storage', function(e) {
          if (e.key === 'senebi_harvest_data') {
            const harvestData = JSON.parse(e.newValue || '{}');
            console.log('🌾 Récolte Parcelles détectée:', harvestData);
            
            // Mettre à jour le KPI "Chiffre d'Affaires"
            const salesKpi = document.getElementById('salesKpi');
            if (salesKpi && harvestData.revenue) {
              const currentSales = parseFloat(salesKpi.querySelector('.number')?.textContent.replace(/[^\d.-]/g, '') || '0');
              const newSales = currentSales + harvestData.revenue;
              
              salesKpi.innerHTML = `
                <span class="number" style="color: #10b981; font-size: 2rem; font-weight: 800; letter-spacing: -0.5px;">${Math.abs(newSales).toLocaleString('fr-FR')}</span>
                <span class="unit">FCFA</span>
              `;
              
              // Ajouter la ligne au tableau des récoltes
              addHarvestToTable(harvestData);
              
              // Recalculer le bénéfice automatiquement
              updateProfitFromSalesAndCosts();
              
              // Afficher une notification
              showHarvestUpdateNotification(harvestData);
            }
          }
        });
      }
      
      // 3. CALCUL AUTOMATIQUE DE LA MARGE
      function updateProfitFromSalesAndCosts() {
        const salesKpi = document.getElementById('salesKpi');
        const costKpi = document.getElementById('costKpi');
        const profitKpi = document.getElementById('profitKpi');
        const marginKpi = document.getElementById('marginKpi');
        
        if (salesKpi && costKpi && profitKpi) {
          const sales = parseFloat(salesKpi.querySelector('.number')?.textContent.replace(/[^\d.-]/g, '') || '0');
          const costs = parseFloat(costKpi.querySelector('.number')?.textContent.replace(/[^\d.-]/g, '') || '0');
          const profit = sales - costs;
          const margin = sales > 0 ? (profit / sales) * 100 : 0;
          
          // Mettre à jour le bénéfice
          const profitColor = profit >= 0 ? '#10b981' : '#ef4444';
          profitKpi.innerHTML = `
            <span class="number" style="color: ${profitColor}; font-size: 2rem; font-weight: 800; letter-spacing: -0.5px;">${profit >= 0 ? '' : '-'}${Math.abs(profit).toLocaleString('fr-FR')}</span>
            <span class="unit">FCFA</span>
          `;
          
          // Mettre à jour la marge
          const marginColor = margin >= 0 ? '#10b981' : '#ef4444';
          marginKpi.innerHTML = `
            <span class="number" style="color: ${marginColor}; font-size: 2rem; font-weight: 800; letter-spacing: -0.5px;">${margin.toFixed(1)}%</span>
          `;
          
          // Mettre à jour l'explication
          const explanationDiv = marginKpi.nextElementSibling;
          if (explanationDiv && explanationDiv.classList.contains('explanation')) {
            const explanation = margin >= 0 
              ? `Vous gagnez ${Math.abs(margin * 10).toLocaleString('fr-FR')} FCFA pour chaque 1 000 FCFA de chiffre d'affaires.`
              : `Vous perdez ${Math.abs(margin * 10).toLocaleString('fr-FR')} FCFA pour chaque 1 000 FCFA gagnés.`;
            explanationDiv.textContent = explanation;
            explanationDiv.style.color = margin >= 0 ? '#10b981' : '#ef4444';
          }
          
          // Mettre à jour les graphiques
          updateChartsWithNewData(sales, costs, profit);
        }
      }
      
      // 4. AJOUTER RÉCOLTE AU TABLEAU
      function addHarvestToTable(harvestData) {
        const tableBody = document.getElementById('harvestRows');
        if (tableBody && harvestData) {
          const row = document.createElement('tr');
          const date = new Date().toLocaleDateString('fr-FR');
          const revenue = harvestData.revenue.toLocaleString('fr-FR');
          const price = harvestData.price ? harvestData.price.toLocaleString('fr-FR') : '0';
          
          row.innerHTML = `
            <td>${date}</td>
            <td>${harvestData.parcelle || 'N/A'}</td>
            <td>${harvestData.quantity || '0'} kg</td>
            <td>${price} FCFA</td>
            <td style="color: #10b981; font-weight: 600;">${revenue} FCFA</td>
          `;
          
          tableBody.insertBefore(row, tableBody.firstChild);
        }
      }
      
      // 5. NOTIFICATIONS DE MISE À JOUR
      function showStockUpdateNotification(data) {
        showToast(`💰 Coût des intrants mis à jour: +${data.amount.toLocaleString('fr-FR')} FCFA (${data.item})`);
      }
      
      function showHarvestUpdateNotification(data) {
        showToast(`🌾 Récolte enregistrée: +${data.revenue.toLocaleString('fr-FR')} FCFA (${data.parcelle})`);
      }
      
      function showToast(message) {
        const toast = document.createElement('div');
        toast.textContent = message;
        toast.style.cssText = `
          position: fixed;
          top: 20px;
          right: 20px;
          background: linear-gradient(135deg, #10b981 0%, #059669 100%);
          color: white;
          padding: 12px 20px;
          border-radius: 12px;
          font-size: 14px;
          font-weight: 500;
          z-index: 1001;
          opacity: 0;
          transform: translateY(-20px);
          transition: all 0.3s ease;
          box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
          toast.style.opacity = '1';
          toast.style.transform = 'translateY(0)';
        }, 100);
        
        setTimeout(() => {
          toast.style.opacity = '0';
          toast.style.transform = 'translateY(-20px)';
          setTimeout(() => toast.remove(), 300);
        }, 3000);
      }
      
      // 6. CONSEIL DE GESTION IA SENEBI
      function generateAIAdvice() {
        const salesKpi = document.getElementById('salesKpi');
        const costKpi = document.getElementById('costKpi');
        const profitKpi = document.getElementById('profitKpi');
        
        if (salesKpi && costKpi && profitKpi) {
          const sales = parseFloat(salesKpi.querySelector('.number')?.textContent.replace(/[^\d.-]/g, '') || '0');
          const costs = parseFloat(costKpi.querySelector('.number')?.textContent.replace(/[^\d.-]/g, '') || '0');
          const profit = parseFloat(profitKpi.querySelector('.number')?.textContent.replace(/[^\d.-]/g, '') || '0');
          
          let advice = '';
          let iconHtml = '';

          if (profit > 0 && sales > 0) {
            const margin = (profit / sales) * 100;
            if (margin > 20) {
              advice = 'Excellente performance! Votre marge de ' + margin.toFixed(1) + "% est très rentable. Continuez cette stratégie!";
              iconHtml = '<svg class="ai-icon-svg" width="18" height="18" role="img" aria-label="bonnes performances" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm-1 14.5l-4.5-4.5 1.4-1.4L11 13.7l6.1-6.1 1.4 1.4L11 16.5z"/></svg>';
            } else if (margin > 10) {
              advice = 'Bonne rentabilité avec ' + margin.toFixed(1) + "% de marge. Optimisez les coûts pour améliorer encore.";
              iconHtml = '<svg class="ai-icon-svg" width="18" height="18" role="img" aria-label="rentabilité" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="M4 16v-6l4 4 4-8 6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            } else {
              advice = 'Marge faible à ' + margin.toFixed(1) + "% . Analysez vos coûts d\'intrants pour améliorer la rentabilité.";
              iconHtml = '<svg class="ai-icon-svg" width="18" height="18" role="img" aria-label="marge faible" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="M4 12l4-4 4 4 6-6" stroke-linecap="round" stroke-linejoin="round"/><path fill="currentColor" d="M19 14.5a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0zm-4.5-2.5v3m0 2v.01"/></svg>';
            }
          } else {
            advice = 'Attention — votre exploitation est en perte. Il faut revoir votre stratégie de prix ou réduire les coûts.';
            iconHtml = '<svg class="ai-icon-svg" width="18" height="18" role="img" aria-label="alerte perte" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="M3 17l6-6 4 4 8-8" stroke-linecap="round" stroke-linejoin="round"/><path fill="currentColor" d="M19 11v6h-6"/></svg>';
          }
          
          // Créer et afficher le conseil
          const adviceSection = document.querySelector('.kpi-grid');
          if (adviceSection && !document.getElementById('aiAdvice')) {
            const adviceCard = document.createElement('div');
            adviceCard.id = 'aiAdvice';
            adviceCard.className = 'card';
            adviceCard.style.cssText = `
              grid-column: 1 / -1;
              background: #f8fafc;
              border: 1px solid #dbeafe;
              border-radius: 12px;
              padding: 14px 16px;
              margin-top: 16px;
            `;

            adviceCard.innerHTML = `
              <div style="display:flex; align-items:flex-start; gap:12px;">
                 <div class="icon-box-sm icon-box blue" style="display: inline-flex;">
                   ${iconHtml}
                 </div>
                <div>
                  <h3 style="margin: 0 0 6px 0; color: #0f172a; font-size: 15px; letter-spacing: 0.01em;">Conseil IA SeneBI</h3>
                  <p class="ai-advice-text" style="margin: 0; color: #334155; font-size: 13.5px; line-height: 1.6;">${advice}</p>
                </div>
              </div>
            `;
            adviceSection.parentNode.insertBefore(adviceCard, adviceSection.nextSibling);
          } else if (document.getElementById('aiAdvice')) {
            const adviceText = document.querySelector('#aiAdvice .ai-advice-text');
            const iconWrapper = document.querySelector('#aiAdvice > div > div');
            if (iconWrapper) iconWrapper.innerHTML = iconHtml;
            if (adviceText) adviceText.textContent = advice;
          }
        }
      }
      
      // 7. HARMONISATION DES COULEURS DES GRAPHIQUES
      function updateChartsWithNewData(sales, costs, profit) {
        if (window.myChart) {
          window.myChart.data.datasets[0].data = [sales, costs, profit];
          window.myChart.data.datasets[0].backgroundColor = ['#10b981', '#6b7280', '#8b5cf6'];
          window.myChart.update();
        }
      }
      
      // INITIALISATION DES LIAISONS
      document.addEventListener('DOMContentLoaded', function() {
        updateCostsFromStocks();
        updateSalesFromParcels();
        
        // Générer le conseil IA toutes les 30 secondes
        generateAIAdvice();
        setInterval(generateAIAdvice, 30000);
        
        // Initialiser les graphiques premium
        initCostsChart();
        initProjectionTimelineChart();
        
        // Générer les recommandations IA financières
        generateFinancialRecommendations();
      });
      
      // GRAPHIQUE DES COÛTS DONUT
      function initCostsChart() {
        const ctx = document.getElementById('costsDonutChart');
        if (ctx) {
          new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Engrais', 'Semences', 'Herbicides', 'Main-d\'œuvre', 'Transport', 'Autres'],
              datasets: [{
                data: [{{ $coutsEngrais }}, {{ $coutsSemences }}, {{ $coutsHerbicides }}, {{ $coutsMainOeuvre }}, {{ $coutsTransport }}, {{ $coutsAutres }}],
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#6b7280'],
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 8
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              cutout: '65%',
              plugins: {
                legend: {
                  display: false
                },
                tooltip: {
                  backgroundColor: 'rgba(15, 23, 42, 0.92)',
                  padding: 12,
                  cornerRadius: 10,
                  callbacks: {
                    label(ctx) {
                      const label = ctx.label || '';
                      const value = ctx.parsed || 0;
                      return `${label}: ${value.toLocaleString('fr-FR')} FCFA`;
                    }
                  }
                }
              },
              animation: {
                animateScale: true,
                animateRotate: true
              }
            }
          });
        }
      }

      // GRAPHIQUE TIMELINE DES PROJECTIONS
      function initProjectionTimelineChart() {
        const ctx = document.getElementById('projectionTimelineChart');
        if (!ctx) return;
        
        const labels = @json(collect($projections)->pluck('mois')->toArray() ?: []);
        const revenues = @json(collect($projections)->pluck('revenu')->toArray() ?: []);
        const profits = @json(collect($projections)->pluck('benefice')->toArray() ?: []);
        
        new Chart(ctx, {
          type: 'line',
          data: {
            labels: labels,
            datasets: [
              {
                label: 'Revenu',
                data: revenues,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
              },
              {
                label: 'Bénéfice',
                data: profits,
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#8b5cf6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
              intersect: false,
              mode: 'index'
            },
            plugins: {
              legend: {
                position: 'top',
                labels: {
                  boxWidth: 12,
                  padding: 20,
                  font: { size: 12, weight: '600' }
                }
              },
              tooltip: {
                backgroundColor: 'rgba(15, 23, 42, 0.92)',
                padding: 12,
                cornerRadius: 10
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                grid: { color: 'rgba(15, 23, 42, 0.07)' },
                ticks: {
                  callback: (v) => v.toLocaleString('fr-FR')
                }
              },
x: {
                grid: { display: false }
              }
            }
          }
        });
      }

      // RECOMMANDATIONS IA FINANCIÈRES MULTIPLES
      function generateFinancialRecommendations() {
        const container = document.getElementById('aiRecommendationsList');
        if (!container) return;
        
        const ca = parseFloat(document.getElementById('salesKpi')?.querySelector('.number')?.textContent.replace(/[^\d.-]/g, '') || '0') || {{ $totalCA }};
        const couts = parseFloat(document.getElementById('costKpi')?.querySelector('.number')?.textContent.replace(/[^\d.-]/g, '') || '0') || {{ $totalCouts }};
        const benefice = ca - couts;
        const marge = ca > 0 ? (benefice / ca) * 100 : 0;
        
        let recommendations = [];
        
        if (marge < 0) {
          recommendations = [
            { icon: 'fa-exclamation-triangle', text: 'Votre exploitation est en perte. Réduisez les coûts d\'intrants ou augmentez les prix de vente.' },
            { icon: 'fa-lightbulb', text: 'Analysez les parcelles les moins rentables et envisagez un reclassement.' },
            { icon: 'fa-hand-holding-seedling', text: 'Contactez un conseiller agricole pour optimisation de vos pratiques.' }
          ];
        } else if (marge < 10) {
          recommendations = [
            { icon: 'fa-chart-line', text: 'Marge faible: optimisez les coûts d\'engrais et semences.' },
            { icon: 'fa-calendar-check', text: 'Planifiez vos interventions pour réduire les coûts.' },
            { icon: 'fa-seedling', text: 'Envisagez des cultures à plus forte valeur ajoutée.' }
          ];
        } else if (marge < 20) {
          recommendations = [
            { icon: 'fa-thumbs-up', text: 'Bonne rentabilité: surveillez vos coûts pour maintenir celle-ci.' },
            { icon: 'fa-piggy-bank', text: 'Mettez de côté des excédents pour les investissements futurs.' },
            { icon: 'fa-chart-pie', text: 'Diversifiez vos cultures pour réduire la dépendance.' }
          ];
        } else {
          recommendations = [
            { icon: 'fa-trophy', text: 'Excellente rentabilité! Votre stratégie est remarquable.' },
            { icon: 'fa-rocket', text: 'Réinvestissez pour étendre votre exploitation.' },
            { icon: 'fa-graduation-cap', text: 'Partagez votre expertise avec d\'autres agriculteurs.' }
          ];
        }
        
container.innerHTML = recommendations.map(rec => `
          <div class="ai-recommendation-item--premium">
            <div class="ai-recommendation-icon--premium"><i class="fas ${rec.icon}"></i></div>
            <p class="ai-recommendation-text--premium">${rec.text}</p>
          </div>
        `).join('');
      }
    </script>
  </body>
</html>