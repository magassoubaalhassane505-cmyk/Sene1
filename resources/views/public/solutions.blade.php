<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Solutions - SeneBI</title>
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  </head>
  <body data-page="public-solutions">
    <div class="app">
      @include('public.header')
      
      <main class="container">
        <div class="page-title">
          <div>
            <h1>Solutions SeneBI</h1>
            <p>Découvrez toutes les solutions adaptées aux agriculteurs et managers pour optimiser votre exploitation</p>
          </div>
        </div>

        <!-- Hero Section -->
        <section class="hero-section-small">
          <div class="section-header">
            <h2>Une suite complète d'outils agricoles intelligents</h2>
            <p>Tout ce dont vous avez besoin pour moderniser votre exploitation</p>
          </div>
        </section>

        <!-- Agriculteur Solutions -->
        <section class="category-section">
          <div class="category-header">
            <h2><i class="fas fa-tractor"></i> Solutions pour les Agriculteurs</h2>
            <p>Outils de gestion de l'exploitation</p>
            <span class="tag good">Agriculteurs</span>
          </div>
          <div class="solutions-grid">
            <article class="solution-card">
              <div class="solution-icon">
                <i class="fas fa-map-marked-alt"></i>
              </div>
              <h3>Gestion des parcelles</h3>
              <p>Suivez vos cultures, surveillez la croissance et optimisez vos rendements avec notre système de suivi intelligent.</p>
            </article>
            <article class="solution-card">
              <div class="solution-icon">
                <i class="fas fa-boxes"></i>
              </div>
              <h3>Gestion des stocks</h3>
              <p>Contrôlez vos intrants et matériels avec des alertes automatiques pour éviter les ruptures de stock.</p>
            </article>
            <article class="solution-card">
              <div class="solution-icon">
                <i class="fas fa-calculator"></i>
              </div>
              <h3>Calculateur de rentabilité</h3>
              <p>Analysez vos coûts et revenus pour identifier les opportunités d'amélioration de votre rentabilité.</p>
            </article>
            <article class="solution-card">
              <div class="solution-icon">
                <i class="fas fa-history"></i>
              </div>
              <h3>Historique des visites</h3>
              <p>Conservez un suivi complet des interventions techniques sur vos parcelles.</p>
            </article>
            <article class="solution-card">
              <div class="solution-icon">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
              <h3>Alertes de seuil critique</h3>
              <p>Recevez des notifications proactives en cas de seuils critiques dépassés.</p>
            </article>
          </div>
        </section>

        <!-- Manager Solutions -->
        <section class="category-section">
          <div class="category-header">
            <h2><i class="fas fa-user-tie"></i> Solutions pour les Managers</h2>
            <p>Outils de supervision et d'administration</p>
            <span class="tag muted">Managers</span>
          </div>
          <div class="solutions-grid">
            <article class="solution-card">
              <div class="solution-icon">
                <i class="fas fa-chart-dashboard"></i>
              </div>
              <h3>Dashboard BI</h3>
              <p>Visualisez en temps réel les performances de toutes les exploitations sous votre supervision.</p>
            </article>
            <article class="solution-card">
              <div class="solution-icon">
                <i class="fas fa-users-cog"></i>
              </div>
              <h3>Gestion des clients</h3>
              <p>Validez les nouveaux comptes, gérez les accès et suivez l'activité de vos agriculteurs.</p>
            </article>
            <article class="solution-card">
              <div class="solution-icon">
                <i class="fas fa-clipboard-list"></i>
              </div>
              <h3>Planification des visites</h3>
              <p>Organisez les interventions techniques et attribuez-les aux équipes terrain.</p>
            </article>
            <article class="solution-card">
              <div class="solution-icon">
                <i class="fas fa-book"></i>
              </div>
              <h3>Catalogue des intrants</h3>
              <p>Gérez la base de données des semences, engrais et produits nécessaires aux cultures.</p>
            </article>
            <article class="solution-card">
              <div class="solution-icon">
                <i class="fas fa-eye"></i>
              </div>
              <h3>Supervision des exploitations</h3>
              <p>Accédez à une vue d'ensemble complète de toutes les exploitations sous votre gestion.</p>
            </article>
          </div>
        </section>

        <!-- Business Intelligence -->
        <section class="category-section">
          <div class="category-header">
            <h2><i class="fas fa-chart-line"></i> Intelligence Décisionnelle</h2>
            <p>Outils d'analyse et d'aide à la décision</p>
            <span class="tag good">BI</span>
          </div>
          <div class="solutions-grid">
            <article class="solution-card">
              <div class="solution-icon">
                <i class="fas fa-bell"></i>
              </div>
              <h3>Alertes automatiques</h3>
              <p>Système intelligent qui détecte les anomalies et vous notifie immédiatement.</p>
            </article>
            <article class="solution-card">
              <div class="solution-icon">
                <i class="fas fa-exclamation-circle"></i>
              </div>
              <h3>Détection des stocks critiques</h3>
              <p>Identification proactive des ruptures potentielles avant qu'elles n'arrivent.</p>
            </article>
            <article class="solution-card">
              <div class="solution-icon">
                <i class="fas fa-chart-bar"></i>
              </div>
              <h3>Analyse des performances</h3>
              <p>Tableaux de bord détaillés pour mesurer l'efficacité de vos cultures.</p>
            </article>
            <article class="solution-card">
              <div class="solution-icon">
                <i class="fas fa-chart-pie"></i>
              </div>
              <h3>Tableaux de bord analytiques</h3>
              <p>Visualisations interactives pour comprendre vos données agricoles.</p>
            </article>
            <article class="solution-card">
              <div class="solution-icon">
                <i class="fas fa-lightbulb"></i>
              </div>
              <h3>Aide à la prise de décision</h3>
              <p>Recommandations intelligentes basées sur l'analyse de vos données.</p>
            </article>
          </div>
        </section>

        <!-- Why Choose Us -->
        <section class="why-section">
          <div class="section-header">
            <h2>Pourquoi choisir SeneBI ?</h2>
            <p>Une solution complète adaptée aux besoins des agriculteurs modernes</p>
          </div>
          <div class="why-grid">
            <article class="why-card">
              <i class="fas fa-check-circle why-icon"></i>
              <h3>Facile à utiliser</h3>
              <p>Interface intuitive conçue pour tous les niveaux d'expérience technique.</p>
            </article>
            <article class="why-card">
              <i class="fas fa-globe-africa why-icon"></i>
              <h3>Accessible partout</h3>
              <p>Web responsive fonctionnant sur ordinateur, tablette et smartphone.</p>
            </article>
            <article class="why-card">
              <i class="fas fa-headset why-icon"></i>
              <h3>Support dédié</h3>
              <p>Assistance technique disponible pour vous accompagner au quotidien.</p>
            </article>
            <article class="why-card">
              <i class="fas fa-shield-alt why-icon"></i>
              <h3>Sécurisé</h3>
              <p>Vos données sont protégées avec les meilleurs standards de sécurité.</p>
            </article>
          </div>
        </section>

        <!-- CTA -->
        <section class="cta-section">
          <div class="cta-content">
            <h2>Prêt à transformer votre exploitation ?</h2>
            <p>Contactez-nous pour découvrir comment SeneBI peut vous aider</p>
            <div class="hero-actions">
              <a href="/inscription" class="btn btn-primary btn-large">Créer un compte</a>
              <a href="/contact" class="btn btn-ghost btn-large">Nous contacter</a>
            </div>
          </div>
        </section>
      </main>

      @include('public.footer')
    </div>

    <style>
    .hero-section-small {
      margin: 20px 0 30px;
    }
    
    .category-section {
      margin: 40px 0;
    }
    
    .category-header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 24px;
      flex-wrap: wrap;
    }
    
    .category-header h2 {
      margin: 0;
      font-size: 22px;
      font-weight: 800;
      color: var(--text);
    }
    
    .category-header h2 i {
      color: var(--accent);
      margin-right: 8px;
    }
    
    .category-header p {
      margin: 0;
      color: var(--muted);
      font-size: 14px;
      flex: 1;
    }
    
    .solutions-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 20px;
    }
    
    .solution-card {
      background: var(--card);
      border: 1px solid rgba(15,23,42,0.08);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 24px;
      transition: transform var(--anim-fast) ease, box-shadow var(--anim-fast) ease;
    }
    
    .solution-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-lg);
    }
    
    .solution-icon {
      width: 56px;
      height: 56px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--accent-soft);
      color: var(--accent);
      border-radius: 16px;
      font-size: 24px;
      margin-bottom: 16px;
    }
    
    .solution-card h3 {
      margin: 0 0 12px;
      font-size: 16px;
      font-weight: 700;
      color: var(--text);
    }
    
    .solution-card p {
      margin: 0;
      font-size: 13px;
      color: var(--muted);
      line-height: 1.5;
    }
    
    .why-section {
      background: var(--card);
      border: 1px solid rgba(15,23,42,0.08);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 32px;
      margin: 40px 0;
    }
    
    .why-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
    }
    
    .why-card {
      text-align: center;
      padding: 20px;
    }
    
    .why-icon {
      font-size: 32px;
      color: var(--good);
      margin-bottom: 16px;
    }
    
    .why-card h3 {
      margin: 0 0 8px;
      font-size: 15px;
      font-weight: 700;
      color: var(--text);
    }
    
    .why-card p {
      margin: 0;
      font-size: 13px;
      color: var(--muted);
      line-height: 1.5;
    }
    
    .cta-section {
      background: linear-gradient(135deg, var(--accent), #047857);
      border-radius: var(--radius);
      padding: 40px;
      text-align: center;
      margin: 40px 0;
    }
    
    .cta-content h2 {
      font-size: 24px;
      font-weight: 800;
      color: #fff;
      margin: 0 0 8px;
    }
    
    .cta-content p {
      font-size: 15px;
      color: rgba(255,255,255,0.85);
      margin: 0 0 20px;
    }
    
    .cta-content .btn-ghost {
      background: rgba(255,255,255,0.1);
      color: #fff;
      border: 1px solid rgba(255,255,255,0.2);
    }
    
    .cta-content .btn-ghost:hover {
      background: rgba(255,255,255,0.2);
    }
    
    @media (max-width: 1100px) {
      .solutions-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      
      .why-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      
      .why-section {
        padding: 24px;
      }
    }
    
    @media (max-width: 768px) {
      .solutions-grid,
      .why-grid {
        grid-template-columns: 1fr;
      }
      
      .category-header {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .cta-section {
        padding: 28px 20px;
      }
    }
    </style>
  </body>
</html>