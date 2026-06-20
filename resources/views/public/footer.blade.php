<footer class="modern-footer">
  <div class="footer-container">
    <div class="footer-brand">
      <a href="/" class="footer-logo">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo SeneBI" />
        <div class="footer-brand-text">
          <strong>SeneBI</strong>
          <span>Business Intelligence Agricole</span>
        </div>
      </a>
      <p>SeneBI est une plateforme de Business Intelligence Agricole permettant aux agriculteurs et aux managers de suivre, analyser et optimiser leurs exploitations grâce à des outils intelligents de gestion et d'aide à la décision.</p>
    </div>
    
    <div class="footer-nav">
      <h4>Navigation</h4>
      <a href="/">Accueil</a>
      <a href="/solutions">Solutions</a>
      <a href="/a-propos">À propos</a>
      <a href="/contact">Contact</a>
      <a href="/faq">FAQ</a>
    </div>
    
    <div class="footer-account">
      <h4>Compte</h4>
      <a href="/connexion">Se connecter</a>
      <a href="/inscription">Créer un compte</a>
    </div>
    
    <div class="footer-contact">
      <h4>Contact</h4>
      <div class="contact-item">
        <i class="fas fa-envelope"></i>
        <span>contact@senebi.ml</span>
      </div>
      <div class="contact-item">
        <i class="fas fa-phone"></i>
        <span>+223 20 00 00 00</span>
      </div>
      <div class="contact-item">
        <i class="fas fa-map-marker-alt"></i>
        <span>Bamako, Mali</span>
      </div>
    </div>
  </div>
  
  <div class="footer-divider"></div>
  
  <div class="footer-bottom">
    <p>&copy; 2026 SeneBI. Tous droits réservés.</p>
  </div>
</footer>

<style>
.modern-footer {
  background: var(--text);
  color: #fff;
  padding: 60px 20px 0;
}

.footer-container {
  max-width: 1200px;
  margin: 0 auto 40px;
  display: grid;
  grid-template-columns: 1.5fr 1fr 1fr 1fr;
  gap: 40px;
}

.footer-logo {
  display: flex;
  align-items: center;
  gap: 12px;
  text-decoration: none;
  margin-bottom: 16px;
}

.footer-logo img {
  width: 36px;
  height: 36px;
  object-fit: contain;
  border-radius: 10px;
}

.footer-brand-text {
  display: flex;
  flex-direction: column;
  line-height: 1.2;
}

.footer-brand-text strong {
  font-weight: 800;
  font-size: 20px;
  color: #fff;
}

.footer-brand-text span {
  color: var(--muted);
  font-size: 12px;
}

.footer-brand p {
  margin: 0;
  font-size: 13px;
  color: rgba(255,255,255,0.6);
  line-height: 1.6;
  max-width: 400px;
}

.footer-nav h4,
.footer-account h4,
.footer-contact h4 {
  font-size: 16px;
  font-weight: 700;
  margin: 0 0 20px;
  color: #fff;
}

.footer-nav a,
.footer-account a {
  display: block;
  color: rgba(255,255,255,0.7);
  text-decoration: none;
  margin-bottom: 12px;
  font-size: 14px;
  transition: color 0.2s ease;
}

.footer-nav a:hover,
.footer-account a:hover {
  color: var(--accent);
}

.contact-item {
  display: flex;
  gap: 10px;
  align-items: center;
  margin-bottom: 12px;
  font-size: 14px;
  color: rgba(255,255,255,0.6);
}

.contact-item i {
  color: var(--accent);
  font-size: 16px;
  width: 20px;
}

.footer-divider {
  max-width: 1200px;
  margin: 0 auto;
  height: 1px;
  background: rgba(255,255,255,0.1);
}

.footer-bottom {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px 0;
  text-align: center;
}

.footer-bottom p {
  margin: 0;
  font-size: 13px;
  color: rgba(255,255,255,0.5);
}

@media (max-width: 1100px) {
  .footer-container {
    grid-template-columns: 1fr 1fr;
    gap: 32px;
  }
}

@media (max-width: 768px) {
  .footer-container {
    grid-template-columns: 1fr;
    gap: 24px;
  }
  
  .modern-footer {
    padding: 40px 20px 0;
  }
}
</style>