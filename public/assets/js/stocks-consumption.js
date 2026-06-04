// Script simple pour gérer le formulaire de consommation
document.addEventListener("DOMContentLoaded", function() {
  const consumeForm = document.querySelector("#consumeForm");
  if (!consumeForm) return;

  consumeForm.addEventListener("submit", async function(e) {
    e.preventDefault();
    
    const region = document.querySelector("#consumeRegion")?.value || "";
    const parcelle = document.querySelector("#consumeParcel")?.value || "";
    const intrant = document.querySelector("#consumeItem")?.value || "";
    const quantite = parseFloat(document.querySelector("#consumeQty")?.value) || 0;
    const dateInput = document.querySelector("#consumeDate")?.value || "";
    const date = dateInput ? new Date(dateInput).toISOString().split('T')[0] : new Date().toISOString().split('T')[0];
    
    console.log("Données envoyées:", { region, parcelle, intrant, quantite, date });
    
    if (!parcelle || !intrant || quantite <= 0) {
      alert("Veuillez remplir tous les champs");
      return;
    }
    
    try {
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      console.log("CSRF Token:", csrfToken ? "présent" : "manquant");
      
      const response = await fetch('/client/api/consommation', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken || ''
        },
        body: JSON.stringify({
          region: region,
          parcelle: parcelle,
          date: date,
          intrant: intrant,
          quantite: quantite
        })
      });
      
      console.log("Response status:", response.status);
      console.log("Response ok:", response.ok);
      
      // Vérifier si la réponse est du JSON
      const contentType = response.headers.get('content-type');
      console.log("Content-Type:", contentType);
      
      if (!contentType || !contentType.includes('application/json')) {
        const text = await response.text();
        console.log("Réponse HTML:", text.substring(0, 200));
        throw new Error('Erreur serveur: réponse non JSON. Status: ' + response.status);
      }
      
      const result = await response.json();
      console.log("Résultat:", result);
      
      if (!response.ok) {
        throw new Error(result.error || 'Erreur lors de l\'enregistrement');
      }
      
      // Mettre à jour le tableau localement
      const stockRows = document.querySelectorAll("#stockTableBody tr");
      stockRows.forEach(row => {
        const nameCell = row.cells[0]?.textContent;
        if (nameCell === result.stock.nom) {
          const stockCell = row.cells[2]; // Colonne "Stock Actuel"
          const thresholdCell = row.cells[3]; // Colonne "Seuil Critique"
          const statusCell = row.cells[5]; // Colonne "Statut"
          
          // Mettre à jour le stock avec la valeur du backend
          stockCell.textContent = `${result.stock.quantite_actuelle.toLocaleString("fr-FR")} kg`;
          
          // Mettre à jour le statut selon si c'est critique
          if (result.stock.est_critique) {
            statusCell.innerHTML = `<span class="badge red">CRITIQUE</span>`;
          } else {
            const pourcentage = Math.round((result.stock.quantite_actuelle / result.stock.seuil_critique) * 100);
            statusCell.innerHTML = `<span class="badge green">OK (${pourcentage}%)</span>`;
          }
        }
      });
      
      alert("Consommation enregistrée avec succès !");
      consumeForm.reset();
      location.reload();
      
    } catch (error) {
      console.error("Erreur complète:", error);
      alert("Erreur: " + (error.message || "Erreur lors de l'enregistrement"));
    }
  });
});
