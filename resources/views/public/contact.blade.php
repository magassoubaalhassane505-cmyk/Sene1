<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Contact - SeneBI</title>
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRC899hAHlgK6qVImYN8rTk8=" crossorigin="" />
  </head>
  <body data-page="public-contact">
    <div class="app">
      @include('public.header')
      
      <main class="container">
        <div class="page-title">
          <div>
            <h1>Contactez-nous</h1>
            <p>Nous sommes à votre écoute pour répondre à toutes vos questions</p>
          </div>
        </div>

        <!-- Contact Info Cards -->
        <section class="contact-info-section">
          <div class="contact-grid">
            <article class="contact-card">
              <div class="contact-icon">
                <i class="fas fa-envelope"></i>
              </div>
              <h3>Email</h3>
              <p class="contact-value">contact@senebi.ml</p>
              <p class="contact-note">Réponse sous 24h</p>
            </article>

            <article class="contact-card">
              <div class="contact-icon">
                <i class="fas fa-phone"></i>
              </div>
              <h3>Téléphone</h3>
              <p class="contact-value">+223 20 00 00 00</p>
              <p class="contact-note">Lun - Ven: 8h00 - 18h00</p>
            </article>

            <article class="contact-card">
              <div class="contact-icon">
                <i class="fas fa-map-marker-alt"></i>
              </div>
              <h3>Adresse</h3>
              <p class="contact-value">Bamako, Mali</p>
              <p class="contact-note">Quartier de la Gare</p>
            </article>
          </div>
        </section>

        <!-- Contact Form & Map -->
        <section class="contact-form-section">
          <article class="contact-form-card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:18px;">Envoyez-nous un message</h3>
                <div class="small muted">Remplissez le formulaire ci-dessous</div>
              </div>
              <span class="tag good">Message</span>
            </div>
            <form method="POST" action="/contact" class="contact-form">
              @csrf
              <div class="form-row">
                <div class="form-group">
                  <label for="name">Nom complet</label>
                  <input type="text" id="name" name="name" required placeholder="Votre nom complet" />
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" id="email" name="email" required placeholder="votre@email.com" />
                </div>
              </div>
              <div class="form-group">
                <label for="subject">Sujet</label>
                <input type="text" id="subject" name="subject" required placeholder="Sujet de votre message" />
              </div>
              <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required placeholder="Votre message..."></textarea>
              </div>
              <button type="submit" class="btn btn-submit">Envoyer le message</button>
            </form>
          </article>
        </section>

        <!-- Map Section -->
        <section class="map-section">
          <article class="card map-card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Notre bureau</h3>
                <div class="small muted">Bamako, Mali</div>
              </div>
              <div class="card-icon">
                <i class="fas fa-map-marked-alt"></i>
              </div>
            </div>
            <div class="map-container" id="map"></div>
            <div class="map-address">
              <div class="map-address-content">
                <strong>SeneBI</strong>
                <span>Quartier de la Gare</span>
                <span>Bamako, Mali</span>
              </div>
            </div>
          </article>
        </section>

        <!-- Business Hours -->
        <section class="hours-section">
          <article class="card hours-card">
            <div class="card-header">
              <div>
                <h3 style="margin:0; font-size:16px;">Horaires d'ouverture</h3>
                <div class="small muted">Notre disponibilité</div>
              </div>
              <span class="tag muted">Disponibilité</span>
            </div>
            <div class="hours-grid">
              <div class="hours-item">
                <span>Lundi - Vendredi</span>
                <span class="hours-time">8h00 - 18h00</span>
              </div>
              <div class="hours-item">
                <span>Samedi</span>
                <span class="hours-time">9h00 - 13h00</span>
              </div>
              <div class="hours-item">
                <span>Dimanche</span>
                <span class="hours-time">Fermé</span>
              </div>
            </div>
          </article>
        </section>
      </main>

      @include('public.footer')
    </div>

    <style>
    .contact-info-section {
      margin: 30px 0;
    }
    
    .contact-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }
    
    .contact-card {
      background: var(--card);
      border: 1px solid rgba(15,23,42,0.08);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 28px;
      text-align: center;
      transition: transform var(--anim-fast) ease, box-shadow var(--anim-fast) ease;
    }
    
    .contact-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-lg);
    }
    
    .contact-icon {
      width: 60px;
      height: 60px;
      margin: 0 auto 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--accent-soft);
      color: var(--accent);
      border-radius: 50%;
      font-size: 24px;
    }
    
    .contact-card h3 {
      margin: 0 0 8px;
      font-size: 16px;
      font-weight: 700;
      color: var(--text);
    }
    
    .contact-value {
      margin: 0 0 4px;
      font-size: 15px;
      font-weight: 600;
      color: var(--text);
    }
    
    .contact-note {
      margin: 0;
      font-size: 13px;
      color: var(--muted);
    }
    
    .contact-form-section {
      margin: 30px 0;
    }
    
    .contact-form-card {
      padding: 0;
      overflow: hidden;
    }
    
    .contact-form-card .card-header {
      padding: 24px 24px 0;
    }
    
    .contact-form {
      padding: 24px;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    
    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }
    
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }
    
    .form-group label {
      font-size: 13px;
      font-weight: 600;
      color: var(--text);
    }
    
    .contact-form input,
    .contact-form textarea {
      padding: 12px 14px;
      border: 1px solid rgba(15,23,42,0.08);
      border-radius: 12px;
      font-size: 14px;
      width: 100%;
      font-family: inherit;
      transition: border-color var(--anim-fast) ease, box-shadow var(--anim-fast) ease;
    }
    
    .contact-form textarea {
      resize: vertical;
      min-height: 120px;
    }
    
    .contact-form input:focus,
    .contact-form textarea:focus {
      outline: none;
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
    }
    
    .btn-submit {
      background: var(--accent);
      color: #fff;
      border: none;
      padding: 14px 24px;
      border-radius: 12px;
      font-weight: 700;
      font-size: 15px;
      cursor: pointer;
      transition: all var(--anim-fast) ease;
      width: fit-content;
    }
    
    .btn-submit:hover {
      background: #047857;
      transform: translateY(-1px);
    }
    
    .map-section {
      margin: 30px 0;
    }
    
    .map-card .card-header {
      padding-bottom: 20px;
    }
    
    .map-container {
      height: 320px;
      margin: 0 24px 0;
      border-radius: 0 0 18px 18px;
    }
    
    .map-container .leaflet-control-container {
      display: none;
    }
    
    .map-address {
      padding: 16px 24px 24px;
      background: var(--accent-soft);
      margin: 0 24px 24px;
      border-radius: 0 0 12px 12px;
      border-top: 1px solid rgba(5, 150, 105, 0.1);
    }
    
    .map-address-content {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }
    
    .map-address-content strong {
      font-size: 16px;
      color: var(--text);
      font-weight: 700;
    }
    
    .map-address-content span {
      font-size: 14px;
      color: var(--muted);
    }
    
    .hours-section {
      margin: 30px 0;
    }
    
    .hours-card .card-header {
      padding-bottom: 16px;
    }
    
    .hours-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
      padding: 0 24px 24px;
    }
    
    .hours-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 16px;
      background: var(--accent-soft);
      border-radius: 12px;
    }
    
    .hours-item span:first-child {
      font-weight: 600;
      color: var(--text);
      font-size: 14px;
    }
    
    .hours-time {
      font-weight: 700;
      color: var(--accent);
      font-size: 13px;
    }
    
    @media (max-width: 1100px) {
      .contact-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      
      .hours-grid {
        grid-template-columns: 1fr;
      }
    }
    
    @media (max-width: 768px) {
      .contact-grid,
      .form-row {
        grid-template-columns: 1fr;
      }
    }
    </style>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-207O9F7k7J8zv2Y4b8VXXVPFyQTYm2qC9rYzX5b5p2A=" crossorigin=""></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      var map = L.map('map', {
        zoomControl: false,
        attributionControl: false
      }).setView([12.6394, -8.0120], 15);
      
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
      }).addTo(map);
      
      var marker = L.marker([12.6478, -8.0076], {
        icon: L.divIcon({
          className: 'custom-marker',
          html: '<div style="background: var(--accent); color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><i class="fas fa-map-marker-alt"></i></div>',
          iconSize: [30, 30],
          iconAnchor: [15, 30]
        })
      }).addTo(map);
      
      marker.bindPopup('<strong>SeneBI</strong><br>Quartier de la Gare<br>Bamako, Mali').openPopup();
    });
    </script>
  </body>
</html>