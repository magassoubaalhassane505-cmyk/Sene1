// Centre de Contrôle des Visites - JavaScript
(function() {
  'use strict';

  console.log('visits-control.js chargé');

  // Données de visites pour la page de planning
  const visits = [
    {
      id: 1,
      farmer: "Mamadou Diallo",
      location: "Bamako",
      date: new Date(Date.now() + 2 * 24 * 60 * 60 * 1000), // 2 days from now
      reason: "Contrôle stock Urée",
      status: "planned"
    },
    {
      id: 2,
      farmer: "Aminata Touré",
      location: "Sikasso",
      date: new Date(Date.now() + 4 * 24 * 60 * 60 * 1000), // 4 days from now
      reason: "Alerte rendement Riz",
      status: "planned"
    },
    {
      id: 3,
      farmer: "Bakary Camara",
      location: "Kayes",
      date: new Date(Date.now() + 6 * 24 * 60 * 60 * 1000), // 6 days from now
      reason: "Conseil semis Coton",
      status: "planned"
    }
  ];

  // Charger les visites planifiées
  function loadVisits() {
    // Les visites sont maintenant affichées depuis la base de données via Blade
    // Cette fonction n'est plus nécessaire
  }

  // Charger les visites urgentes
  function loadUrgentVisits() {
    const urgentList = document.getElementById('urgentList');
    if (!urgentList) return;

    // Agriculteurs avec stocks critiques
    const urgentFarmers = [
      {
        name: "Mamadou Diallo",
        location: "Bamako",
        reason: "Stock Urée critique (15%)",
        action: "Planifier visite"
      },
      {
        name: "Aminata Touré",
        location: "Sikasso",
        reason: "Alerte rendement Riz",
        action: "Planifier visite"
      },
      {
        name: "Bakary Camara",
        location: "Kayes",
        reason: "Conseil semis Coton urgent",
        action: "Planifier visite"
      }
    ];

    urgentList.innerHTML = urgentFarmers.map(farmer => `
      <div class="urgent-item">
        <div class="urgent-indicator"></div>
        <div class="urgent-info">
          <div class="urgent-name">${farmer.name}</div>
          <div class="urgent-location">${farmer.location}</div>
          <div class="urgent-reason">${farmer.reason}</div>
        </div>
        <button class="btn btn-small btn-danger" onclick="planUrgentVisit('${farmer.name}', '${farmer.location}')">
          ${farmer.action}
        </button>
      </div>
    `).join('');
  }

  // Planifier une visite urgente
  function planUrgentVisit(button, farmerName, location, reason) {
    const farmerSelect = document.getElementById('farmerSelect');
    const dateTime = document.getElementById('dateTime');
    const reasonField = document.getElementById('reason');

    if (farmerSelect) {
      // Sélectionner l'agriculteur
      const options = farmerSelect.options;
      for (let i = 0; i < options.length; i++) {
        if (options[i].text.includes(farmerName)) {
          farmerSelect.value = options[i].value;
          break;
        }
      }
    }

    if (reasonField) {
      // Pré-remplir le champ motif avec le texte de l'alerte
      reasonField.value = reason || `Urgence - Contrôle stock`;
    }

    if (dateTime) {
      // Définir demain à 9h
      const tomorrow = new Date();
      tomorrow.setDate(tomorrow.getDate() + 1);
      tomorrow.setHours(9, 0, 0, 0);
      dateTime.value = tomorrow.toISOString().slice(0, 16);
    }

    // Scroller vers le formulaire
    const form = document.getElementById('visitForm');
    if (form) {
      form.scrollIntoView({ behavior: 'smooth' });
    }
  }

  // Attacher la fonction à window pour l'accessibilité globale
  window.planUrgentVisit = planUrgentVisit;

  // Initialiser la page
  function init() {
    // Les visites urgentes sont maintenant générées par Blade depuis le contrôleur
    // loadUrgentVisits(); // Supprimé - les données viennent de la base de données

    // Configurer le formulaire
    setupForm();

    // Définir la date/heure par défaut (demain 9h)
    setDefaultDateTime();
  }
  
  // Configurer le formulaire de visite
  function setupForm() {
    const form = document.getElementById('visitForm');
    if (!form) return;

    form.addEventListener('submit', async function(e) {
      e.preventDefault();

      const farmerSelect = document.getElementById('farmerSelect');
      const dateTime = document.getElementById('dateTime');
      const reason = document.getElementById('reason');
      const recommandation = document.getElementById('recommandation');

      if (!farmerSelect.value || !dateTime.value || !reason.value) {
        alert('Veuillez remplir tous les champs');
        return;
      }

      try {
        const csrfToken = document.querySelector('input[name="_token"]')?.value || '';

        const response = await fetch('/manager/visites', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            user_id: farmerSelect.value,
            date_visite: dateTime.value,
            action_effectuee: reason.value,
            recommandation: recommandation ? recommandation.value : '',
            duree: 60
          })
        });

        // Vérifier le type de contenu de la réponse
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
          throw new Error('Erreur serveur: réponse non JSON. Vous êtes peut-être déconnecté.');
        }

        const result = await response.json();

        if (!response.ok) {
          throw new Error(result.error || 'Erreur lors de la création de la visite');
        }

        // Recharger la page pour afficher la nouvelle visite
        console.log('Visite créée avec succès, rechargement de la page...');
        location.reload();

      } catch (error) {
        alert('Erreur: ' + (error.message || "Erreur lors de l'enregistrement"));
      }
    });
  }
  
  // Définir la date/heure par défaut (demain 9h)
  function setDefaultDateTime() {
    const dateTime = document.getElementById('dateTime');
    if (dateTime) {
      const tomorrow = new Date();
      tomorrow.setDate(tomorrow.getDate() + 1);
      tomorrow.setHours(9, 0, 0, 0);
      dateTime.value = tomorrow.toISOString().slice(0, 16);
    }
  }

  // Démarrer quand le DOM est prêt
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
