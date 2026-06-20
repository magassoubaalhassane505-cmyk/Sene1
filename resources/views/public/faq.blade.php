<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>FAQ - SeneBI</title>
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  </head>
  <body data-page="public-faq">
    <div class="app">
      @include('public.header')
      
      <main class="container">
        <div class="page-title">
          <div>
            <h1>Foire aux Questions</h1>
            <p>Toutes les réponses à vos questions sur SeneBI</p>
          </div>
        </div>

        <!-- FAQ Section -->
        <section class="faq-section">
          <div class="section-header">
            <h2><i class="fas fa-question-circle"></i> Questions fréquentes</h2>
            <p>Cliquez sur chaque question pour voir la réponse</p>
          </div>
          
          <div class="accordion">
            <div class="accordion-item">
              <button class="accordion-header" aria-expanded="false">
                <span>Comment créer un compte SeneBI ?</span>
                <i class="fas fa-chevron-down accordion-icon"></i>
              </button>
              <div class="accordion-content">
                <p>Pour créer un compte, cliquez sur "Créer un compte" dans le menu principal, remplissez le formulaire d'inscription avec vos informations personnelles et celles de votre exploitation. Votre compte sera ensuite validé par un manager avant d'être activé. Vous recevrez un email de confirmation une fois la validation effectuée.</p>
              </div>
            </div>

            <div class="accordion-item">
              <button class="accordion-header" aria-expanded="false">
                <span>Combien coûte l'utilisation de SeneBI ?</span>
                <i class="fas fa-chevron-down accordion-icon"></i>
              </button>
              <div class="accordion-content">
                <p>Nous proposons des tarifs adaptés à tous les budgets. Les tarifs varient selon la taille de votre exploitation et les fonctionnalités dont vous avez besoin. Contactez-nous pour obtenir un devis personnalisé. Nous proposons des forfaits pour les agriculteurs individuels et les organisations agricoles.</p>
              </div>
            </div>

            <div class="accordion-item">
              <button class="accordion-header" aria-expanded="false">
                <span>Puis-je utiliser SeneBI sur mon téléphone ?</span>
                <i class="fas fa-chevron-down accordion-icon"></i>
              </button>
              <div class="accordion-content">
                <p>Oui, SeneBI est une plateforme web responsive accessible depuis n'importe quel appareil connecté à internet : ordinateur, tablette ou smartphone. Notre interface s'adapte automatiquement à la taille de votre écran pour une expérience optimale.</p>
              </div>
            </div>

            <div class="accordion-item">
              <button class="accordion-header" aria-expanded="false">
                <span>Mes données sont-elles sécurisées ?</span>
                <i class="fas fa-chevron-down accordion-icon"></i>
              </button>
              <div class="accordion-content">
                <p>Absolument. Nous utilisons les meilleurs standards de sécurité de l'industrie pour protéger vos données. Toutes les informations sont chiffrées et stockées sur des serveurs sécurisés. Nous sommes conformes aux réglementations en matière de protection des données personnelles.</p>
              </div>
            </div>

            <div class="accordion-item">
              <button class="accordion-header" aria-expanded="false">
                <span>Quelle est la différence entre agriculteur et manager ?</span>
                <i class="fas fa-chevron-down accordion-icon"></i>
              </button>
              <div class="accordion-content">
                <p>L'agriculteur a accès à un dashboard pour gérer ses parcelles, stocks, visites et analyser sa rentabilité. Le manager quant à lui dispose d'un dashboard de supervision pour gérer plusieurs agriculteurs, valider les nouveaux comptes et accéder à des analyses globales de la production.</p>
              </div>
            </div>

            <div class="accordion-item">
              <button class="accordion-header" aria-expanded="false">
                <span>Comment sont gérées les alertes de seuil critique ?</span>
                <i class="fas fa-chevron-down accordion-icon"></i>
              </button>
              <div class="accordion-content">
                <p>Le système surveille automatiquement vos niveaux de stock et vous envoie des notifications (email et dans l'application) lorsque les seuils définis sont atteints ou dépassés. Vous pouvez personnaliser ces seuils selon vos besoins spécifiques.</p>
              </div>
            </div>

            <div class="accordion-item">
              <button class="accordion-header" aria-expanded="false">
                <span>Puis-je intégrer SeneBI à d'autres outils ?</span>

                <i class="fas fa-chevron-down accordion-icon"></i>
              </button>
              <div class="accordion-content">
                <p>Oui, SeneBI propose une API permettant d'intégrer nos services avec d'autres systèmes agricoles, logiciels de comptabilité ou plateformes météo. Contactez notre support technique pour plus d'information sur les possibilités d'intégration.</p>
              </div>
            </div>

            <div class="accordion-item">
              <button class="accordion-header" aria-expanded="false">
                <span>Quel support est disponible ?</span>
                <i class="fas fa-chevron-down accordion-icon"></i>
              </button>
              <div class="accordion-content">
                <p>Nous offrons un support technique par email et téléphone du lundi au vendredi de 8h à 18h. Une documentation complète est également disponible dans votre espace. Pour les urgences, notre support est disponible 24/7 pour les comptes professionnels.</p>
              </div>
            </div>
          </div>
        </section>

        <!-- Still Have Questions -->
        <section class="still-questions-section">
          <article class="card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:18px;">Vous avez d'autres questions ?</h3>
                <div class="small muted">Notre équipe est à votre écoute</div>
              </div>
              <i class="fas fa-comments" style="font-size: 32px; color: var(--accent);"></i>
            </div>
            <div style="display:flex; gap:16px; flex-wrap:wrap;">
              <a href="/contact" class="btn btn-primary">Nous contacter</a>
              <a href="/inscription" class="btn btn-ghost">Créer un compte</a>
            </div>
          </article>
        </section>
      </main>

      @include('public.footer')
    </div>

    <style>
    .faq-section {
      margin: 40px 0;
    }
    
    .accordion {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    
    .accordion-item {
      background: var(--card);
      border: 1px solid rgba(15,23,42,0.08);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
      border-left: 3px solid var(--accent);
    }
    
    .accordion-item:nth-child(2) {
      border-left-color: var(--good);
    }
    
    .accordion-item:nth-child(3) {
      border-left-color: #0891b2;
    }
    
    .accordion-item:nth-child(4) {
      border-left-color: var(--warn);
    }
    
    .accordion-item:nth-child(5) {
      border-left-color: #7c3aed;
    }
    
    .accordion-item:nth-child(6) {
      border-left-color: #ea580c;
    }
    
    .accordion-item:nth-child(7) {
      border-left-color: #be185d;
    }
    
    .accordion-item:nth-child(8) {
      border-left-color: #059669;
    }
    
    .accordion-header {
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 24px;
      background: none;
      border: none;
      cursor: pointer;
      font-weight: 600;
      color: var(--text);
      font-size: 15px;
      transition: background var(--anim-fast) ease;
    }
    
    .accordion-header:hover {
      background: rgba(248, 250, 252, 0.5);
    }
    
    .accordion-icon {
      font-size: 18px;
      color: var(--muted);
      transition: transform var(--anim-fast) ease;
    }
    
    .accordion-header[aria-expanded="true"] .accordion-icon {
      transform: rotate(180deg);
    }
    
    .accordion-content {
      max-height: 0;
      overflow: hidden;
      transition: max-height var(--anim-med) ease, padding var(--anim-med) ease;
      padding: 0 24px;
      font-size: 14px;
      color: var(--muted);
      line-height: 1.6;
    }
    
    .accordion-content p {
      margin: 0 0 12px;
    }
    
    .accordion-content p:last-child {
      margin-bottom: 0;
    }
    
    .accordion-item.active .accordion-content {
      max-height: 300px;
      padding: 0 24px 20px;
    }
    
    .still-questions-section {
      margin: 40px 0;
    }
    
    .still-questions-section .card-header {
      padding-bottom: 20px;
    }
    
    .still-questions-section .card {
      padding-bottom: 24px;
    }
    
    .btn-ghost {
      background: transparent;
      color: var(--text);
      border: 1px solid rgba(15,23,42,0.08);
    }
    
    @media (max-width: 768px) {
      .faq-section {
        margin: 20px 0;
      }
      
      .accordion-header {
        padding: 16px 20px;
        font-size: 14px;
      }
      
      .accordion-item.active .accordion-content {
        padding: 0 20px 16px;
      }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
      const accordionHeaders = document.querySelectorAll('.accordion-header');
      
      accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
          const accordionItem = this.parentElement;
          const isExpanded = this.getAttribute('aria-expanded') === 'true';
          
          // Close all other items
          document.querySelectorAll('.accordion-item').forEach(item => {
            item.classList.remove('active');
            item.querySelector('.accordion-header').setAttribute('aria-expanded', 'false');
          });
          
          // Toggle current item
          if (!isExpanded) {
            accordionItem.classList.add('active');
            this.setAttribute('aria-expanded', 'true');
          }
        });
      });
    });
    </script>
  </body>
</html>