<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>À propos - SeneBI</title>
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  </head>
  <body data-page="public-about">
    <div class="app">
      @include('public.header')
      
      <main class="container">
        <div class="page-title">
          <div>
            <h1>À propos de SeneBI</h1>
            <p>Découvrez notre mission, nos objectifs et comment nous aidons les agriculteurs à optimiser leurs exploitations</p>
          </div>
        </div>

        <!-- Mission & Vision Section -->
        <section class="mission-vision-section">
          <div class="mv-grid">
            <article class="card mv-card">
              <div class="mv-header">
                <div class="mv-icon">
                  <i class="fas fa-seedling"></i>
                </div>
                <h3>Notre Mission</h3>
              </div>
              <div class="mv-content">
                <p>SeneBI a pour mission de révolutionner la gestion agricole en Afrique grâce à la technologie. Nous croyons que chaque agriculteur mérite d'avoir accès à des outils professionnels de gestion et d'analyse pour optimiser son exploitation et augmenter sa rentabilité.</p>
                <p>Notre plateforme combine simplicité d'utilisation et puissance analytique pour permettre aux agriculteurs de tous niveaux de prendre des décisions éclairées basées sur des données réelles.</p>
              </div>
            </article>

            <article class="card mv-card">
              <div class="mv-header">
                <div class="mv-icon">
                  <i class="fas fa-globe-africa"></i>
                </div>
                <h3>Notre Vision</h3>
              </div>
              <div class="mv-content">
                <p>Devenir la référence incontournable de la digitalisation agricole en Afrique, en déployant des solutions innovantes qui transforme l'agriculture traditionnelle en une activité moderne, productive et durable.</p>
                <p>Nous visionnons un futur où chaque exploitation agricole, indépendamment de sa taille, peut accéder aux outils de Business Intelligence pour maximiser sa production et sa rentabilité.</p>
              </div>
            </article>
          </div>
        </section>

        <!-- Values Section -->
        <section class="values-section">
          <div class="section-header">
            <h2><i class="fas fa-heart"></i> Nos Valeurs</h2>
            <p>Les principes qui guident nos actions</p>
          </div>
          <div class="values-grid">
            <article class="value-card">
              <i class="fas fa-bolt"></i>
              <h3>Innovation</h3>
              <p>Nous exploitons la technologie pour créer des solutions toujours plus performantes et accessibles.</p>
            </article>
            <article class="value-card">
              <i class="fas fa-users"></i>
              <h3>Proximité</h3>
              <p>Nous restons proches des agriculteurs pour comprendre leurs besoins et les aider efficacement.</p>
            </article>
            <article class="value-card">
              <i class="fas fa-leaf"></i>
              <h3>Durabilité</h3>
              <p>Nous promouvons une agriculture respectueuse de l'environnement et des ressources naturelles.</p>
            </article>
            <article class="value-card">
              <i class="fas fa-shield-alt"></i>
              <h3>Confiance</h3>
              <p>La sécurité et la confidentialité des données de nos clients sont au cœur de notre engagement.</p>
            </article>
          </div>
        </section>

        <!-- Why Choose Section -->
        <section class="why-choose-section">
          <div class="section-header">
            <h2><i class="fas fa-star"></i> Pourquoi choisir SeneBI ?</h2>
            <p>Une solution complète adaptée aux besoins des agriculteurs modernes</p>
          </div>
          <div class="why-choose-grid">
            <article class="card why-choose-card">
              <div class="card-header">
                <div>
                  <h3 style="margin:0; font-size:16px;">Pour les Agriculteurs</h3>
                  <div class="small muted">Outils professionnels adaptés</div>
                </div>
                <span class="tag good">Agriculteurs</span>
              </div>
              <ul class="feature-list">
                <li><i class="fas fa-check"></i> Gestion simplifiée</li>
                <li><i class="fas fa-check"></i> Alertes intelligentes</li>
                <li><i class="fas fa-check"></i> Analyse de rentabilité</li>
                <li><i class="fas fa-check"></i> Suivi des performances</li>
                <li><i class="fas fa-check"></i> Support personnalisé</li>
              </ul>
            </article>

            <article class="card why-choose-card">
              <div class="card-header">
                <div>
                  <h3 style="margin:0; font-size:16px;">Pour les Managers</h3>
                  <div class="small muted">Outils de supervision</div>
                </div>
                <span class="tag muted">Managers</span>
              </div>
              <ul class="feature-list">
                <li><i class="fas fa-check"></i> Vue d'ensemble complète</li>
                <li><i class="fas fa-check"></i> Gestion centralisée</li>
                <li><i class="fas fa-check"></i> Planification des visites</li>
                <li><i class="fas fa-check"></i> Analyse comparative</li>
                <li><i class="fas fa-check"></i> Rapports détaillés</li>
              </ul>
            </article>
          </div>
        </section>

        <!-- Objectives Grid -->
        <section class="objectives-section">
          <div class="section-header">
            <h2>Nos Objectifs</h2>
            <p>Ce que nous nous efforçons d'accomplir</p>
          </div>
          <div class="objectives-grid">
            <article class="objective-card">
              <i class="fas fa-chart-line objective-icon"></i>
              <h3>Optimiser les rendements</h3>
              <p>Aider les agriculteurs à maximiser leur production grâce à une meilleure gestion des ressources.</p>
            </article>
            <article class="objective-card">
              <i class="fas fa-piggy-bank objective-icon"></i>
              <h3>Augmenter la rentabilité</h3>
              <p>Permettre une analyse précise des coûts et revenus pour identifier les opportunités d'amélioration.</p>
            </article>
            <article class="objective-card">
              <i class="fas fa-brain objective-icon"></i>
              <h3>Démocratiser la BI</h3>
              <p>Rendre la Business Intelligence accessible à tous les agriculteurs, indépendamment de leur taille.</p>
            </article>
            <article class="objective-card">
              <i class="fas fa-seedling objective-icon"></i>
              <h3>Promouvoir l'agriculture durable</h3>
              <p>Encourager des pratiques agricoles plus durables grâce à une meilleure gestion des intrants.</p>
            </article>
          </div>
        </section>

        <!-- CTA -->
        <section class="cta-section">
          <div class="cta-content">
            <h2>Rejoignez la communauté SeneBI</h2>
            <p>Faites partie de la révolution agricole et optimisez votre exploitation dès maintenant</p>
            <div class="hero-actions">
              <a href="/inscription" class="btn btn-primary btn-large">Créer un compte</a>
              <a href="/contact" class="btn btn-ghost btn-large">En savoir plus</a>
            </div>
          </div>
        </section>
      </main>

      @include('public.footer')
    </div>

    <style>
    .section-header {
      margin-bottom: 32px;
    }
    
    .section-header h2 {
      margin: 0 0 8px;
      font-size: 24px;
      font-weight: 800;
      color: var(--text);
    }
    
    .section-header h2 i {
      color: var(--accent);
      margin-right: 10px;
    }
    
    .section-header p {
      margin: 0;
      color: var(--muted);
      font-size: 14px;
    }
    
    .mission-vision-section {
      margin: 40px 0;
    }
    
    .mv-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
    }
    
    .mv-card {
      padding: 0;
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }
    
    .mv-header {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 20px 24px 0;
    }
    
    .mv-icon {
      width: 48px;
      height: 48px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--accent-soft);
      color: var(--accent);
      border-radius: 12px;
      font-size: 20px;
    }
    
    .mv-header h3 {
      margin: 0;
      font-size: 18px;
      font-weight: 700;
      color: var(--text);
    }
    
    .mv-content {
      padding: 8px 24px 24px;
    }
    
    .mv-content p {
      margin: 0 0 12px;
      font-size: 14px;
      color: var(--muted);
      line-height: 1.6;
    }
    
    .mv-content p:last-child {
      margin-bottom: 0;
    }
    
    .values-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
      margin: 40px 0;
    }
    
    .value-card {
      background: var(--card);
      border: 1px solid rgba(15,23,42,0.08);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 24px;
      text-align: center;
      transition: transform var(--anim-fast) ease, box-shadow var(--anim-fast) ease;
    }
    
    .value-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-lg);
    }
    
    .value-card i {
      font-size: 32px;
      color: var(--accent);
      margin-bottom: 16px;
    }
    
    .value-card h3 {
      margin: 0 0 12px;
      font-size: 16px;
      font-weight: 700;
      color: var(--text);
    }
    
    .value-card p {
      margin: 0;
      font-size: 13px;
      color: var(--muted);
      line-height: 1.5;
    }
    
    .why-choose-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin: 40px 0;
    }
    
    .feature-list {
      list-style: none;
      padding: 0;
      margin: 16px 0 0;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    
    .feature-list li {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      color: var(--text);
      font-weight: 500;
    }
    
    .feature-list li i {
      color: var(--good);
      font-size: 14px;
    }
    
    .objectives-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
      margin: 40px 0;
    }
    
    .objective-card {
      background: var(--card);
      border: 1px solid rgba(15,23,42,0.08);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 24px;
      text-align: center;
      transition: transform var(--anim-fast) ease, box-shadow var(--anim-fast) ease;
    }
    
    .objective-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-lg);
    }
    
    .objective-icon {
      font-size: 36px;
      color: var(--accent);
      margin-bottom: 16px;
    }
    
    .objective-card h3 {
      margin: 0 0 12px;
      font-size: 15px;
      font-weight: 700;
      color: var(--text);
    }
    
    .objective-card p {
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
    
    .hero-actions {
      display: flex;
      gap: 12px;
      justify-content: center;
      flex-wrap: wrap;
    }
    
    .btn-large {
      padding: 14px 24px;
      font-size: 15px;
    }
    
    .btn-ghost {
      background: rgba(255,255,255,0.1);
      color: #fff;
      border: 1px solid rgba(255,255,255,0.2);
    }
    
    .btn-ghost:hover {
      background: rgba(255,255,255,0.2);
    }
    
    @media (max-width: 1100px) {
      .mv-grid,
      .values-grid,
      .objectives-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      
      .why-choose-grid {
        grid-template-columns: 1fr;
      }
    }
    
    @media (max-width: 768px) {
      .mv-grid,
      .values-grid,
      .objectives-grid {
        grid-template-columns: 1fr;
      }
      
      .cta-section {
        padding: 28px 20px;
      }
    }
    </style>
  </body>
</html>