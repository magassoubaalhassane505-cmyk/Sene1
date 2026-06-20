<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SeneBI - Système Intégré de Gestion Agricole avec Business Intelligence</title>
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  </head>
  <body data-page="public-home">
    <div class="app">
      @include('public.header')
      
      <main class="container">
        <!-- Hero Section -->
        <section class="hero-section">
          <div class="hero-content">
            <h1>SeneBI - Système Intégré de Gestion Agricole avec Business Intelligence</h1>
            <p>Optimisez la gestion de votre exploitation agricole grâce à des outils intelligents de suivi, d'analyse et de planification.</p>
            <div class="hero-actions">
              <a href="/inscription" class="btn btn-primary btn-large">Créer un compte gratuit</a>
              <a href="/solutions" class="btn btn-ghost btn-large">Découvrir la plateforme</a>
            </div>
          </div>
          <div class="hero-image">
            <i class="fas fa-tractor" style="font-size: 120px; color: var(--accent); opacity: 0.1;"></i>
          </div>
        </section>

        <!-- Statistics -->
        <section class="stats-section">
          <div class="section-header">
            <h2>Nos performances en chiffres</h2>
            <p>Des résultats concrets pour des agriculteurs satisfaits</p>
          </div>
          <div class="stats-grid">
            <article class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-users"></i>
              </div>
              <div class="stat-value">500+</div>
              <div class="stat-label">Agriculteurs Actifs</div>
              <div class="stat-sub">Clients certifiés cette saison</div>
            </article>
            <article class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
              </div>
              <div class="stat-value">98%</div>
              <div class="stat-label">Satisfaction Client</div>
              <div class="stat-sub">Taux de satisfaction élevé</div>
            </article>
            <article class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-headset"></i>
              </div>
              <div class="stat-value">24/7</div>
              <div class="stat-label">Support Disponible</div>
              <div class="stat-sub">Assistance dédiée</div>
            </article>
            <article class="stat-card">
              <div class="stat-icon">
                <i class="fas fa-seedling"></i>
              </div>
              <div class="stat-value">85,320</div>
              <div class="stat-label">Hectares Gérés</div>
              <div class="stat-sub">Surface cultivée totale</div>
            </article>
          </div>
        </section>

        <!-- Platform Presentation -->
        <section class="platform-section">
          <div class="section-header">
            <h2>Une plateforme complète pour l'agriculture moderne</h2>
            <p>Découvrez les outils qui transforment votre quotidien</p>
          </div>
          <div class="platform-grid">
            <article class="platform-card">
              <div class="platform-icon">
                <i class="fas fa-leaf"></i>
              </div>
              <h3>Gestion des Parcelles</h3>
              <p>Suivez vos cultures, surveillez la croissance et optimisez vos rendements grâce à notre système de suivi intelligent.</p>
            </article>
            <article class="platform-card">
              <div class="platform-icon">
                <i class="fas fa-boxes"></i>
              </div>
              <h3>Gestion des Stocks</h3>
              <p>Contrôlez vos intrants et matériels avec des alertes automatiques pour éviter les ruptures de stock.</p>
            </article>
            <article class="platform-card">
              <div class="platform-icon">
                <i class="fas fa-chart-bar"></i>
              </div>
              <h3>Analyse BI</h3>
              <p>Prenez des décisions éclairées grâce à des rapports et tableaux de bord interactifs.</p>
            </article>
            <article class="platform-card">
              <div class="platform-icon">
                <i class="fas fa-calendar-alt"></i>
              </div>
              <h3>Planification</h3>
              <p>Organisez vos interventions et visites techniques en quelques clics.</p>
            </article>
          </div>
        </section>

        <!-- Benefits Section -->
        <section class="benefits-section">
          <div class="benefits-content">
            <div class="section-header">
              <h2>Les avantages SeneBI</h2>
              <p>Rendez votre exploitation plus rentable et durable</p>
            </div>
            <div class="benefits-list">
              <div class="benefit-item">
                <i class="fas fa-check-circle"></i>
                <span>Gain de temps grâce à l'automatisation</span>
              </div>
              <div class="benefit-item">
                <i class="fas fa-check-circle"></i>
                <span>Décisions basées sur des données réelles</span>
              </div>
              <div class="benefit-item">
                <i class="fas fa-check-circle"></i>
                <span>Moins de perte de récoltes</span>
              </div>
              <div class="benefit-item">
                <i class="fas fa-check-circle"></i>
                <span>Suivi en temps réel de vos cultures</span>
              </div>
              <div class="benefit-item">
                <i class="fas fa-check-circle"></i>
                <span>Support technique dédié</span>
              </div>
              <div class="benefit-item">
                <i class="fas fa-check-circle"></i>
                <span>Accès mobile partout</span>
              </div>
            </div>
          </div>
          <div class="benefits-image">
            <i class="fas fa-mobile-alt" style="font-size: 100px; color: var(--accent); opacity: 0.1;"></i>
          </div>
        </section>

        <!-- How It Works -->
        <section class="howitworks-section">
          <div class="section-header">
            <h2>Comment ça fonctionne ?</h2>
            <p>Commencez en 5 étapes simples</p>
          </div>
          <div class="steps-grid">
            <div class="step-card">
              <div class="step-number">1</div>
              <i class="fas fa-user-plus step-icon"></i>
              <h4>Création du compte</h4>
              <p>Inscription en quelques minutes</p>
            </div>
            <div class="step-card">
              <div class="step-number">2</div>
              <i class="fas fa-check-double step-icon"></i>
              <h4>Validation par Manager</h4>
              <p>Sécurité et vérification</p>
            </div>
            <div class="step-card">
              <div class="step-number">3</div>
              <i class="fas fa-tachometer-alt step-icon"></i>
              <h4>Accès à la plateforme</h4>
              <p>Dashboard personnalisé</p>
            </div>
            <div class="step-card">
              <div class="step-number">4</div>
              <i class="fas fa-tractor step-icon"></i>
              <h4>Gestion de l'exploitation</h4>
              <p>Parcelles, stocks, visites</p>
            </div>
            <div class="step-card">
              <div class="step-number">5</div>
              <i class="fas fa-chart-line step-icon"></i>
              <h4>Analyse des performances</h4>
              <p>Business Intelligence</p>
            </div>
          </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
          <div class="cta-content">
            <h2>Prêt à transformer votre exploitation ?</h2>
            <p>Rejoignez des centaines d'agriculteurs qui utilisent déjà SeneBI</p>
            <a href="/inscription" class="btn btn-primary btn-large">Commencer maintenant</a>
          </div>
        </section>
      </main>

      @include('public.footer')
    </div>

    <style>
    .hero-section {
      display: grid;
      grid-template-columns: 1.5fr 1fr;
      gap: 40px;
      align-items: center;
      margin: 40px 0;
      padding: 20px 0;
    }
    
    .hero-content h1 {
      font-size: 36px;
      font-weight: 900;
      letter-spacing: -0.02em;
      margin: 0 0 16px;
      line-height: 1.1;
    }
    
    .hero-content p {
      font-size: 18px;
      color: var(--muted);
      margin: 0 0 24px;
      max-width: 500px;
    }
    
    .hero-actions {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
    }
    
    .btn-large {
      padding: 14px 24px;
      font-size: 15px;
    }
    
    .btn-ghost {
      background: transparent;
      color: var(--text);
      border: 1px solid rgba(15,23,42,0.08);
    }
    
    .hero-image {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .section-header {
      text-align: center;
      margin-bottom: 32px;
    }
    
    .section-header h2 {
      font-size: 28px;
      font-weight: 800;
      margin: 0 0 8px;
      letter-spacing: -0.01em;
    }
    
    .section-header p {
      font-size: 15px;
      color: var(--muted);
      margin: 0;
    }
    
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
    }
    
    .stat-card {
      background: var(--card);
      border: 1px solid rgba(15,23,42,0.08);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 24px;
      text-align: center;
      transition: transform var(--anim-fast) ease, box-shadow var(--anim-fast) ease;
    }
    
    .stat-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-lg);
    }
    
    .stat-icon {
      width: 56px;
      height: 56px;
      margin: 0 auto 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--accent-soft);
      color: var(--accent);
      border-radius: 16px;
      font-size: 24px;
    }
    
    .stat-value {
      font-size: 32px;
      font-weight: 900;
      color: var(--text);
      margin: 0 0 8px;
    }
    
    .stat-label {
      font-size: 14px;
      font-weight: 700;
      color: var(--text);
      margin: 0 0 4px;
    }
    
    .stat-sub {
      font-size: 12px;
      color: var(--muted);
    }
    
    .platform-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
    }
    
    .platform-card {
      background: var(--card);
      border: 1px solid rgba(15,23,42,0.08);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 24px;
      text-align: center;
      transition: transform var(--anim-fast) ease, box-shadow var(--anim-fast) ease;
    }
    
    .platform-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-lg);
    }
    
    .platform-icon {
      width: 64px;
      height: 64px;
      margin: 0 auto 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--accent-soft);
      color: var(--accent);
      border-radius: 50%;
      font-size: 28px;
    }
    
    .platform-card h3 {
      font-size: 16px;
      font-weight: 700;
      margin: 0 0 12px;
      color: var(--text);
    }
    
    .platform-card p {
      font-size: 13px;
      color: var(--muted);
      margin: 0;
      line-height: 1.5;
    }
    
    .benefits-section {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
      align-items: center;
      margin: 60px 0;
      padding: 32px;
      background: var(--card);
      border: 1px solid rgba(15,23,42,0.08);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
    }
    
    .benefits-list {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }
    
    .benefit-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
      background: var(--accent-soft);
      border-radius: 12px;
      font-size: 14px;
      font-weight: 600;
      color: var(--text);
    }
    
    .benefit-item i {
      color: var(--accent);
      font-size: 18px;
    }
    
    .benefits-image {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .steps-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 16px;
    }
    
    .step-card {
      background: var(--card);
      border: 1px solid rgba(15,23,42,0.08);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 20px;
      text-align: center;
      transition: transform var(--anim-fast) ease, box-shadow var(--anim-fast) ease;
    }
    
    .step-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-lg);
    }
    
    .step-number {
      width: 40px;
      height: 40px;
      margin: 0 auto 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--accent);
      color: #fff;
      border-radius: 50%;
      font-weight: 800;
      font-size: 16px;
    }
    
    .step-icon {
      font-size: 24px;
      color: var(--accent);
      margin-bottom: 12px;
    }
    
    .step-card h4 {
      font-size: 14px;
      font-weight: 700;
      margin: 0 0 8px;
      color: var(--text);
    }
    
    .step-card p {
      font-size: 12px;
      color: var(--muted);
      margin: 0;
    }
    
    .cta-section {
      background: linear-gradient(135deg, var(--accent), #047857);
      border-radius: var(--radius);
      padding: 48px;
      text-align: center;
      margin: 40px 0;
    }
    
    .cta-content h2 {
      font-size: 28px;
      font-weight: 800;
      color: #fff;
      margin: 0 0 12px;
    }
    
    .cta-content p {
      font-size: 16px;
      color: rgba(255,255,255,0.85);
      margin: 0 0 24px;
    }
    
    .cta-content .btn-primary {
      background: #fff;
      color: var(--accent);
    }
    
    @media (max-width: 1100px) {
      .hero-section {
        grid-template-columns: 1fr;
        text-align: center;
      }
      
      .hero-content h1 {
        font-size: 28px;
      }
      
      .stats-grid,
      .platform-grid,
      .steps-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      
      .benefits-section {
        grid-template-columns: 1fr;
        padding: 24px;
      }
      
      .benefits-list {
        grid-template-columns: 1fr;
      }
    }
    
    @media (max-width: 768px) {
      .stats-grid,
      .platform-grid,
      .steps-grid {
        grid-template-columns: 1fr;
      }
      
      .cta-section {
        padding: 32px 20px;
      }
      
      .hero-content h1 {
        font-size: 24px;
      }
      
      .section-header h2 {
        font-size: 22px;
      }
    }
    </style>
  </body>
</html>