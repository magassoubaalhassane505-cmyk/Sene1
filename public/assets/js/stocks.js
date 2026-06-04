(function () {
  function fmtKg(n) {
    return `${Number(n || 0).toLocaleString("fr-FR")} kg`;
  }
  function pct(value, cap) {
    return cap > 0 ? Math.round((value / cap) * 100) : 0;
  }

  function renderStockGauge(state) {
    const canvas = SeneBI.qs("#stockGaugeChart");
    const pctEl = SeneBI.qs("#stockGaugePct");
    if (!canvas || !window.Chart) return;
    const s = SeneBI.getSeasonData(state);
    const cap = state.capacity;
    const inv = s.inventory;
    const totalInv = Number(inv.ureeKg || 0) + Number(inv.npkKg || 0) + Number(inv.semencesKg || 0);
    const totalCap = Number(cap.ureeKg || 0) + Number(cap.npkKg || 0) + Number(cap.semencesKg || 0);
    const pct = totalCap > 0 ? Math.min(100, Math.round((totalInv / totalCap) * 100)) : 0;
    if (pctEl) pctEl.textContent = `${pct}%`;
    const rest = Math.max(0, 100 - pct);
    const existing = Chart.getChart(canvas);
    if (existing) existing.destroy();
    new Chart(canvas, {
      type: "doughnut",
      data: {
        datasets: [
          {
            data: [pct, rest],
            backgroundColor: ["#059669", "#e2e8f0"],
            borderWidth: 0,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        circumference: 180,
        rotation: 270,
        cutout: "72%",
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: "rgba(15, 23, 42, 0.92)",
            padding: 10,
            cornerRadius: 10,
            callbacks: {
              label(ctx) {
                if (ctx.dataIndex === 0) return `Remplissage : ${pct}% de la capacite totale`;
                return `Libre : ${rest}%`;
              },
            },
          },
        },
      },
    });
  }

  function renderTopConsumers(state) {
    const tbody = SeneBI.qs("#topConsumersBody");
    const fb = SeneBI.qs("#topConsumersFallback");
    if (!tbody) return;
    const s = SeneBI.getSeasonData(state);
    const now = new Date();
    const y = now.getFullYear();
    const m = now.getMonth();
    let rows = s.consumptionHistory.filter((h) => {
      const d = new Date(h.date);
      return !Number.isNaN(d.getTime()) && d.getFullYear() === y && d.getMonth() === m;
    });
    let fallback = false;
    if (!rows.length) {
      rows = [...s.consumptionHistory];
      fallback = true;
    }
    if (fb) fb.hidden = !fallback;
    const byParcel = new Map();
    for (const h of rows) {
      byParcel.set(h.parcelId, (byParcel.get(h.parcelId) || 0) + Number(h.quantityKg || 0));
    }
    const top3 = [...byParcel.entries()].sort((a, b) => b[1] - a[1]).slice(0, 3);
    if (!top3.length) {
      tbody.innerHTML =
        '<tr><td colspan="3" style="text-align:center;padding:12px;color:#64748b;font-weight:700;">Aucune consommation enregistree.</td></tr>';
      return;
    }
    tbody.innerHTML = top3
      .map((entry, i) => {
        const p = s.parcels.find((x) => x.id === entry[0]);
        const name = p ? p.name : entry[0];
        return `<tr><td>${i + 1}</td><td><strong>${name}</strong></td><td>${Number(entry[1]).toLocaleString("fr-FR")} kg</td></tr>`;
      })
      .join("");
  }

  function isStockCritical(state) {
    const s = SeneBI.getSeasonData(state);
    const cap = state.capacity;
    const ratios = [
      { label: "Urée", value: s.inventory.ureeKg, cap: cap.ureeKg },
      { label: "NPK", value: s.inventory.npkKg, cap: cap.npkKg },
      { label: "Semences", value: s.inventory.semencesKg, cap: cap.semencesKg },
    ];
    const critical = ratios.filter((r) => (r.cap ? r.value / r.cap : 0) <= 0.1).map((r) => ({ ...r, pct: r.cap ? r.value / r.cap : 0 }));
    return { any: critical.length > 0, critical };
  }

  function render(state, readOnly = false) {
    const s = SeneBI.getSeasonData(state);
    const cap = state.capacity;
    const stock = isStockCritical(state);
    const seedTotal = Number(s.inventory.semencesKg || 0);
    const semenceRiz = Math.round(seedTotal * 0.5);
    const semenceMais = Math.round(seedTotal * 0.35);
    const semenceCoton = Math.max(0, seedTotal - semenceRiz - semenceMais);

    const stockRows = [
      { name: "Urée", type: "Engrais", value: Number(s.inventory.ureeKg || 0), threshold: Math.round(cap.ureeKg * 0.1), cost: 15000 },
      { name: "NPK", type: "Engrais", value: Number(s.inventory.npkKg || 0), threshold: Math.round(cap.npkKg * 0.1), cost: 18000 },
      { name: "Semence Riz", type: "Semence", value: semenceRiz, threshold: 50, cost: 800 },
      { name: "Semence Maïs", type: "Semence", value: semenceMais, threshold: 40, cost: 900 },
      { name: "Semence Coton", type: "Semence", value: semenceCoton, threshold: 30, cost: 1200 },
    ];

    const wrap = SeneBI.qs("#inventoryCards");
    if (wrap) {
      const totalIntrants = stockRows.length;
      const criticalCount = stockRows.filter((r) => r.value <= r.threshold).length;
      
      // Calculer la valeur totale du stock
      let totalValue = 0;
      stockRows.forEach(row => {
        const stockActuel = Number(row.value) || 0;
        const coutUnitaire = Number(row.cost) || 0;
        totalValue += stockActuel * coutUnitaire;
      });
      
      const formattedValue = totalValue.toLocaleString("fr-FR");
      
      wrap.innerHTML = `
        <article class="card kpi-card">
          <p class="kpi-title">Total Intrants</p>
          <div class="kpi-value">${totalIntrants}</div>
        </article>
        <article class="card kpi-card">
          <p class="kpi-title">Valeur Totale du Stock</p>
          <div class="kpi-value valeur-stock">${formattedValue} FCFA</div>
        </article>
        <article class="card kpi-card ${criticalCount > 0 ? 'critical-alert' : ''}">
          <p class="kpi-title">Alertes Critiques</p>
          <div class="kpi-value">${criticalCount}</div>
        </article>
      `;
      
      // DEBUG : Vérifier que les classes sont appliquées
      console.log("🔍 DEBUG KPI CARDS:");
      console.log("Total Intrants:", totalIntrants);
      console.log("Valeur Totale:", formattedValue);
      console.log("Alertes Critiques:", criticalCount);
      console.log("Class critical-alert appliquée:", criticalCount > 0 ? 'OUI' : 'NON');
      
      // Forcer l'application des styles si nécessaire
      setTimeout(() => {
        const valeurStock = document.querySelector('.valeur-stock');
        if (valeurStock) {
          console.log("✅ Élément valeur-stock trouvé:", valeurStock);
          valeurStock.style.fontSize = '36px !important';
          valeurStock.style.color = '#1b4332 !important';
          valeurStock.style.fontWeight = '900 !important';
        }
        
        const alertCard = document.querySelector('.critical-alert');
        if (alertCard) {
          console.log("✅ Carte alerte critique trouvée:", alertCard);
          alertCard.style.background = '#fff5f5 !important';
          alertCard.style.border = '1px solid rgba(239, 68, 68, 0.2) !important';
        }
        
        const kpiSubs = document.querySelectorAll('.kpi-sub');
        console.log("🔢 Sous-titres trouvés:", kpiSubs.length);
        kpiSubs.forEach(sub => sub.style.display = 'none !important');
      }, 100);
    }

    const alert = SeneBI.qs("#stocksLocalAlert");
    if (alert) {
      if (stock.any) {
        alert.classList.add("show");
        const list = stock.critical.map((c) => c.label).join(", ");
        SeneBI.qs("#stocksLocalAlertText").textContent = `${stock.critical.length} intrant(s) en dessous du seuil critique. Reapprovisionnement urgent necessaire.`;
        const first = stock.critical[0];
        const threshold = Math.round((first.cap || 0) * 0.1);
        const criticalChip = SeneBI.qs("#criticalChip");
        if (criticalChip) criticalChip.textContent = `${first.label} - Stock: ${first.value} / Seuil: ${threshold}`;
      } else {
        alert.classList.remove("show");
      }
    }

    const stockTableBody = SeneBI.qs("#stockTableBody");
    if (stockTableBody) {
      const intrants = window.SeneBI_SERVER?.intrants || {};
      stockTableBody.innerHTML = stockRows
        .map((row) => {
          const level = pct(row.value, row.threshold);
          const ok = row.value > row.threshold;
          
          // Récupérer le prix dynamique depuis la table intrants
          let cost = row.cost;
          const intrantKey = Object.keys(intrants).find(key => row.name.toLowerCase().includes(key.toLowerCase()) || key.toLowerCase().includes(row.name.toLowerCase()));
          if (intrantKey && intrants[intrantKey]) {
            cost = intrants[intrantKey].prix;
          }
          
          return `
            <tr>
              <td><strong>${row.name}</strong></td>
              <td><span class="stock-type">${row.type}</span></td>
              <td>${row.value} ${row.type === "Engrais" ? "sac" : "kg"}</td>
              <td>${row.threshold} ${row.type === "Engrais" ? "sac" : "kg"}</td>
              <td>${cost.toLocaleString("fr-FR")} FCFA</td>
              <td class="${ok ? "status-ok" : "status-bad"}">${ok ? `OK (${level}%)` : `Critique (${level}%)`}</td>
            </tr>
          `;
        })
        .join("");
    }

    const historyList = SeneBI.qs("#consumptionList");
    if (historyList) {
      const byParcel = new Map();
      for (const h of s.consumptionHistory) byParcel.set(h.parcelId, (byParcel.get(h.parcelId) || 0) + Number(h.quantityKg || 0));
      const top = [...byParcel.entries()].sort((a, b) => b[1] - a[1])[0];
      const topEl = SeneBI.qs("#topConsumer");
      if (topEl) {
        const p = s.parcels.find((x) => x.id === (top ? top[0] : "")) || null;
        topEl.textContent = top && p ? `${p.name} (${top[1]} kg)` : "—";
      }
      historyList.innerHTML = [...s.consumptionHistory]
        .sort((a, b) => (a.date < b.date ? 1 : -1))
        .map((h) => {
          const p = s.parcels.find((x) => x.id === h.parcelId);
          const dateFr = h.date.split("-").reverse().join("/");
          return `
            <article class="history-item">
              <div class="left">
                <strong>${p ? p.name : h.parcelId}</strong>
                <span>${h.item}</span>
              </div>
              <div class="right">
                <strong>${Number(h.quantityKg).toLocaleString("fr-FR")} unites</strong>
                <span>${dateFr}</span>
              </div>
            </article>
          `;
        })
        .join("");
    }

    renderStockGauge(state);
    renderTopConsumers(state);

    if (window.Chart) {
      const chartCanvas = SeneBI.qs("#stocksChart");
      if (chartCanvas) {
        const current = Chart.getChart(chartCanvas);
        if (current) current.destroy();
        new Chart(chartCanvas, {
          type: "bar",
          data: {
            labels: stockRows.map((r) => r.name),
            datasets: [{
              data: stockRows.map((r) => r.value),
              backgroundColor: stockRows.map((r) => (r.value <= r.threshold ? "#ef4444" : "#10b981")),
              borderRadius: 10,
            }],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: "index", intersect: false },
            plugins: {
              legend: { display: false },
              tooltip: {
                backgroundColor: "rgba(15, 23, 42, 0.92)",
                padding: 10,
                cornerRadius: 10,
                callbacks: {
                  title(items) {
                    return items[0]?.label ? `Intrant : ${items[0].label}` : "";
                  },
                  label(ctx) {
                    const r = stockRows[ctx.dataIndex];
                    const unit = r.type === "Engrais" ? "sac(s)" : "kg";
                    const st = r.value <= r.threshold ? "sous seuil critique" : "OK";
                    return [`Stock : ${r.value} ${unit}`, `Seuil : ${r.threshold} ${unit}`, `Statut : ${st}`];
                  },
                },
              },
            },
            scales: {
              y: { beginAtZero: true, grid: { color: "rgba(15,23,42,0.06)" } },
              x: { grid: { display: false } },
            },
          },
        });
      }
    }

    const form = SeneBI.qs("#consumeForm");
    const feedbackEl = SeneBI.qs("#consumeFeedback");
    if (readOnly) return;

    if (form && !form.dataset.bound) {
      form.dataset.bound = "1";
      const parcelSel = SeneBI.qs("#consumeParcel");
      if (parcelSel) parcelSel.innerHTML = s.parcels.map((p) => `<option value="${p.id}">${p.name}</option>`).join("");

      form.addEventListener("submit", (e) => {
        e.preventDefault();
        const parcelId = SeneBI.qs("#consumeParcel").value;
        const item = SeneBI.qs("#consumeItem").value;
        const qty = Number(SeneBI.qs("#consumeQty").value);
        const date = SeneBI.qs("#consumeDate").value;
        if (!parcelId || !item || !date || !Number.isFinite(qty) || qty <= 0) {
          if (feedbackEl) {
            feedbackEl.textContent = "Merci de remplir tous les champs avec des valeurs valides.";
            feedbackEl.className = "form-feedback error";
          }
          return;
        }

        const invKey = item === "Urée" ? "ureeKg" : item === "NPK" ? "npkKg" : "semencesKg";
        const currentStock = Number(s.inventory[invKey] || 0);
        if (qty > currentStock) {
          if (feedbackEl) {
            feedbackEl.textContent = `Quantite trop elevee. Stock disponible: ${currentStock.toLocaleString("fr-FR")} kg.`;
            feedbackEl.className = "form-feedback error";
          }
          return;
        }
        s.consumptionHistory.push({ id: `C-${Date.now()}`, parcelId, item, quantityKg: qty, date });
        s.inventory[invKey] = Math.max(0, Number(s.inventory[invKey] || 0) - qty);
        SeneBI.saveState(state);
        render(state, readOnly);
        form.reset();
        if (feedbackEl) {
          feedbackEl.textContent = "Consommation enregistree avec succes.";
          feedbackEl.className = "form-feedback success";
        }
        const d = SeneBI.qs("#consumeDate");
        if (d) d.valueAsDate = new Date();
      });
      const d = SeneBI.qs("#consumeDate");
      if (d && !d.value) d.valueAsDate = new Date();
    }
  }

  document.addEventListener("DOMContentLoaded", function () {
    const auth = SeneBI.requireRole(
      ["manager", "client"],
      "Acces refuse."
    );
    if (!auth) return;
    const readOnly = auth.role === "client";
    const consumeSection = SeneBI.qs("#stocksConsumeSection");
    if (consumeSection) consumeSection.hidden = readOnly;

    const state = SeneBI.loadState();
    SeneBI.renderTopbar(state);
    render(state, readOnly);
    window.addEventListener("senebi:seasonChanged", () => {
      SeneBI.renderTopbar(state);
  });
  });

// FONCTION SUPPRIMÉE - RETOUR À L'ÉTAT ORIGINAL

// Fonction de filtrage par type
function initStockFilters() {
  const filterButtons = document.querySelectorAll('.filter-btn');
  const tableRows = document.querySelectorAll('#stockTableBody tr');

  filterButtons.forEach(button => {
    button.addEventListener('click', function() {
      const filter = this.getAttribute('data-filter');
      
      // Mettre à jour l'état actif des boutons
      filterButtons.forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');
      
      // Filtrer les lignes du tableau
      tableRows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length >= 2) {
          const typeCell = cells[1]; // Colonne "Type"
          const type = typeCell.textContent.trim();
          
          if (filter === 'all' || type === filter) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        }
      });
    });
  });
}

// Initialiser les filtres après le chargement
setTimeout(initStockFilters, 200);

  // Fonction de filtrage par type
  function initStockFilters() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const tableRows = document.querySelectorAll('#stockTableBody tr');

    filterButtons.forEach(button => {
      button.addEventListener('click', function() {
        const filter = this.getAttribute('data-filter');
        
        // Mettre à jour l'état actif des boutons
        filterButtons.forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        
        // Filtrer les lignes du tableau
        tableRows.forEach(row => {
          const cells = row.querySelectorAll('td');
          if (cells.length >= 2) {
            const typeCell = cells[1]; // Colonne "Type"
            const type = typeCell.textContent.trim();
            
            if (filter === 'all' || type === filter) {
              row.style.display = '';
            } else {
              row.style.display = 'none';
            }
          }
        });
      });
    });
  }

  // Initialiser les filtres après le chargement
  setTimeout(initStockFilters, 200);

  // Fonction pour le simulateur de besoins
  function initNeedsSimulator() {
    const form = document.getElementById('needsSimulatorForm');
    const resultDiv = document.getElementById('needsResult');
    
    if (!form || !resultDiv) return;
    
    // Obtenir les stocks actuels depuis le tableau
    function getCurrentStock(intrantName) {
      const tableRows = document.querySelectorAll('#stockTableBody tr');
      for (let row of tableRows) {
        const cells = row.querySelectorAll('td');
        if (cells.length >= 3) {
          const nameCell = cells[0].textContent.trim();
          const stockCell = cells[2];
          if (nameCell === intrantName) {
            return parseFloat(stockCell.textContent.replace(/[^0-9.-]/g, '')) || 0;
          }
        }
      }
      return 0;
    }
    
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const surface = parseFloat(document.getElementById('surfaceInput').value);
      const intrant = document.getElementById('intrantSelect').value;
      
      if (!surface || !intrant) {
        resultDiv.style.display = 'block';
        resultDiv.style.background = '#fef3c7';
        resultDiv.style.color = '#92400e';
        resultDiv.style.border = '1px solid #fbbf24';
        resultDiv.textContent = 'Veuillez remplir tous les champs';
        return;
      }
      
      // Calcul: Surface * 150kg
      const besoin = surface * 150;
      const stockActuel = getCurrentStock(intrant);
      
      resultDiv.style.display = 'block';
      
      if (besoin > stockActuel) {
        // Stock insuffisant
        resultDiv.style.background = '#fee2e2';
        resultDiv.style.color = '#991b1b';
        resultDiv.style.border = '1px solid #fca5a5';
        resultDiv.innerHTML = `⚠️ Stock insuffisant ! Besoin: ${besoin.toLocaleString('fr-FR')} kg | Disponible: ${stockActuel.toLocaleString('fr-FR')} kg | Manque: ${(besoin - stockActuel).toLocaleString('fr-FR')} kg`;
      } else {
        // Stock suffisant
        resultDiv.style.background = '#dcfce7';
        resultDiv.style.color = '#166534';
        resultDiv.style.border = '1px solid #86efac';
        resultDiv.innerHTML = `✅ Stock suffisant pour cette surface. Besoin: ${besoin.toLocaleString('fr-FR')} kg | Disponible: ${stockActuel.toLocaleString('fr-FR')} kg`;
      }
    });
  }

  // Automatisation du Simulateur de Besoins
  function initNeedsSimulator() {
    const surfaceInput = SeneBI.qs("#surfaceInput");
    const intrantSelect = SeneBI.qs("#intrantSelect");
    const badgeTag = SeneBI.q(".tag.muted"); // Le badge "150 kg/ha"
    const verifyBtn = SeneBI.q("#needsSimulatorForm button[type='submit']");
    
    if (!surfaceInput || !intrantSelect || !badgeTag || !verifyBtn) return;

    // Fonction de calcul en temps réel
    function calculateNeeds() {
      const surface = parseFloat(surfaceInput.value) || 0;
      const intrant = intrantSelect.value;
      
      if (surface > 0 && intrant) {
        // Calcul: Surface * 150 kg/ha
        const need = Math.round(surface * 150);
        
        // Mettre à jour le badge avec le résultat
        badgeTag.textContent = `${need.toLocaleString("fr-FR")} kg nécessaire`;
        
        // Récupérer le stock actuel depuis le tableau
        const stockRows = document.querySelectorAll("#stockTableBody tr");
        let currentStock = 0;
        
        stockRows.forEach(row => {
          const nameCell = row.cells[0]?.textContent;
          if (nameCell === intrant) {
            const stockText = row.cells[2]?.textContent; // Colonne "Stock Actuel"
            currentStock = parseInt(stockText.replace(/[^\d]/g, '')) || 0;
          }
        });
        
        // Comparer et mettre à jour le bouton
        if (need > currentStock) {
          // Stock insuffisant
          verifyBtn.textContent = "Stock Insuffisant";
          verifyBtn.style.backgroundColor = "#f97316"; // Orange
          verifyBtn.style.color = "white";
          verifyBtn.style.borderColor = "#f97316";
        } else {
          // Stock suffisant
          verifyBtn.textContent = "Vérifier la disponibilité";
          verifyBtn.style.backgroundColor = ""; // Noir par défaut
          verifyBtn.style.color = "";
          verifyBtn.style.borderColor = "";
        }
      } else {
        // Réinitialiser si champs vides
        badgeTag.textContent = "150 kg/ha";
        verifyBtn.textContent = "Vérifier la disponibilité";
        verifyBtn.style.backgroundColor = "";
        verifyBtn.style.color = "";
        verifyBtn.style.borderColor = "";
      }
    }

    // Écouteurs d'événements pour le calcul en temps réel
    surfaceInput.addEventListener("input", calculateNeeds);
    intrantSelect.addEventListener("change", calculateNeeds);
  }

  // Fonction pour mettre à jour le graphique global
  function updateGlobalGauge() {
    const canvas = SeneBI.qs("#stockGaugeChart");
    if (!canvas || !window.Chart) return;
    
    const chart = Chart.getChart(canvas);
    if (!chart) return;
    
    // Récupérer les nouvelles données du tableau
    const stockRows = document.querySelectorAll("#stockTableBody tr");
    let totalStock = 0;
    let totalCapacity = 0;
    
    stockRows.forEach(row => {
      const stockCell = row.cells[2]; // Stock Actuel
      const stockValue = parseInt(stockCell.textContent.replace(/[^\d]/g, '')) || 0;
      totalStock += stockValue;
      
      // Estimer la capacité (simulé)
      totalCapacity += 1000; // Valeur fixe pour simplifier
    });
    
    const pct = totalCapacity > 0 ? Math.min(100, Math.round((totalStock / totalCapacity) * 100)) : 0;
    const rest = Math.max(0, 100 - pct);
    
    // Mettre à jour le graphique
    chart.data.datasets[0].data = [pct, rest];
    chart.update('active');
    
    // Mettre à jour le pourcentage affiché
    const pctEl = SeneBI.qs("#stockGaugePct");
    if (pctEl) pctEl.textContent = `${pct}%`;
    
    console.log(`📊 Graphique global mis à jour: ${pct}%`);
  }

  // Fonction pour ajouter à l'historique des consommations
  function addToConsumptionHistory(parcelle, intrant, quantite, date) {
    const historyList = SeneBI.qs("#consumptionList");
    if (!historyList) return;
    
    // Créer la nouvelle carte d'historique
    const historyCard = document.createElement("div");
    historyCard.className = "history-item";
    historyCard.style.cssText = `
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 12px;
      margin-bottom: 8px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      animation: slideInRight 0.3s ease;
    `;
    
    historyCard.innerHTML = `
      <div>
        <div style="font-weight: 600; color: #1a1d23;">${parcelle}</div>
        <div style="font-size: 12px; color: #64748b;">${date}</div>
      </div>
      <div style="text-align: right;">
        <div style="font-weight: 600; color: #10b981;">${quantite.toLocaleString('fr-FR')} kg</div>
        <div style="font-size: 11px; color: #64748b;">${intrant}</div>
      </div>
    `;
    
    // Ajouter au début de la liste
    historyList.insertBefore(historyCard, historyList.firstChild);
    
    // Limiter à 5 éléments les plus récents
    const items = historyList.querySelectorAll(".history-item");
    if (items.length > 5) {
      items[items.length - 1].remove();
    }
    
    console.log(`📝 Historique ajouté: ${parcelle} - ${quantite} kg de ${intrant}`);
  }

  // Dynamisme du Formulaire "Déclarer une consommation"
  // Géré par stocks-consumption.js pour éviter les doublons

  // Fonction pour afficher la notification
  function showNotification(message, type = "success") {
    // Créer l'élément de notification
    const notification = document.createElement("div");
    notification.textContent = message;
    
    // Style selon le type
    const bgColor = type === "success" ? "#10b981" : "#ef4444"; // Vert pour succès, rouge pour erreur
    notification.style.cssText = `
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: ${bgColor};
      color: white;
      padding: 12px 20px;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 500;
      z-index: 1000;
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    `;
    
    document.body.appendChild(notification);
    
    // Animation d'apparition
    setTimeout(() => {
      notification.style.opacity = "1";
      notification.style.transform = "translateY(0)";
    }, 100);
    
    // Disparition après 3 secondes
    setTimeout(() => {
      notification.style.opacity = "0";
      notification.style.transform = "translateY(20px)";
      setTimeout(() => {
        if (document.body.contains(notification)) {
          document.body.removeChild(notification);
        }
      }, 300);
    }, 3000);
  }

  // Fonction pour simuler la baisse dans le graphique
  function updateChartAfterConsumption(intrant, quantite) {
    const canvas = SeneBI.qs("#stockChart");
    if (!canvas || !window.Chart) return;
    
    const chart = Chart.getChart(canvas);
    if (!chart) return;
    
    // Trouver l'index de l'intrant dans le graphique
    const labels = chart.data.labels;
    const index = labels.findIndex(label => 
      label.toLowerCase().includes(intrant.toLowerCase())
    );
    
    if (index !== -1) {
      // Réduire la valeur dans le graphique
      const currentValue = chart.data.datasets[0].data[index];
      chart.data.datasets[0].data[index] = Math.max(0, currentValue - quantite);
      chart.update('active'); // Animation fluide
    }
  }

  // Fonction pour mettre à jour le tableau des stocks depuis la réponse backend
  function updateStockTableFromBackend(stockData) {
    const stockRows = document.querySelectorAll("#stockTableBody tr");
    
    stockRows.forEach(row => {
      const nameCell = row.cells[0]?.textContent;
      if (nameCell === stockData.nom) {
        const stockCell = row.cells[2]; // Colonne "Stock Actuel"
        const thresholdCell = row.cells[3]; // Colonne "Seuil Critique"
        const statusCell = row.cells[5]; // Colonne "Statut"
        
        // Mettre à jour le stock avec la valeur du backend
        stockCell.textContent = `${stockData.quantite_actuelle.toLocaleString("fr-FR")} kg`;
        
        // Mettre à jour le seuil critique
        thresholdCell.textContent = `${stockData.seuil_critique.toLocaleString("fr-FR")} kg`;
        
        // Mettre à jour le statut selon si c'est critique
        if (stockData.est_critique) {
          statusCell.innerHTML = `<span class="badge red">CRITIQUE</span>`;
          console.log(`🚨 STATUT CRITIQUE: ${stockData.nom} - Stock: ${stockData.quantite_actuelle} kg, Seuil: ${stockData.seuil_critique} kg`);
        } else {
          const pourcentage = Math.round((stockData.quantite_actuelle / stockData.seuil_critique) * 100);
          statusCell.innerHTML = `<span class="badge green">OK (${pourcentage}%)</span>`;
          console.log(`✅ STATUT OK: ${stockData.nom} - Stock: ${stockData.quantite_actuelle} kg (${pourcentage}%)`);
        }
      }
    });
    
    // Après la mise à jour, vérifier tous les seuils
    setTimeout(checkAllThresholds, 100);
  }

  // Fonction pour mettre à jour le tableau des stocks depuis la réponse backend
  function updateStockTableFromBackend(stockData) {
    const stockRows = document.querySelectorAll("#stockTableBody tr");
    
    stockRows.forEach(row => {
      const nameCell = row.cells[0]?.textContent;
      if (nameCell === stockData.nom) {
        const stockCell = row.cells[2]; // Colonne "Stock Actuel"
        const thresholdCell = row.cells[3]; // Colonne "Seuil Critique"
        const statusCell = row.cells[5]; // Colonne "Statut"
        
        // Mettre à jour le stock avec la valeur du backend
        stockCell.textContent = `${stockData.quantite_actuelle.toLocaleString("fr-FR")} kg`;
        
        // Mettre à jour le seuil critique
        thresholdCell.textContent = `${stockData.seuil_critique.toLocaleString("fr-FR")} kg`;
        
        // Mettre à jour le statut selon si c'est critique
        if (stockData.est_critique) {
          statusCell.innerHTML = `<span class="badge red">CRITIQUE</span>`;
          console.log(`🚨 STATUT CRITIQUE: ${stockData.nom} - Stock: ${stockData.quantite_actuelle} kg, Seuil: ${stockData.seuil_critique} kg`);
        } else {
          const pourcentage = Math.round((stockData.quantite_actuelle / stockData.seuil_critique) * 100);
          statusCell.innerHTML = `<span class="badge green">OK (${pourcentage}%)</span>`;
          console.log(`✅ STATUT OK: ${stockData.nom} - Stock: ${stockData.quantite_actuelle} kg (${pourcentage}%)`);
        }
      }
    });
    
    // Après la mise à jour, vérifier tous les seuils
    setTimeout(checkAllThresholds, 100);
  }

  // Fonction pour mettre à jour le tableau des stocks avec recalcul du statut
  function updateStockTable(intrant, quantite) {
    const stockRows = document.querySelectorAll("#stockTableBody tr");
    
    stockRows.forEach(row => {
      const nameCell = row.cells[0]?.textContent;
      if (nameCell === intrant) {
        const stockCell = row.cells[2]; // Colonne "Stock Actuel"
        const currentStock = parseInt(stockCell.textContent.replace(/[^\d]/g, '')) || 0;
        const newStock = Math.max(0, currentStock - quantite);
        stockCell.textContent = `${newStock.toLocaleString("fr-FR")} kg`;
        
        console.log(`📦 Stock mis à jour pour ${intrant}: ${currentStock} → ${newStock} kg`);
      }
    });
    
    // Après la mise à jour, vérifier tous les seuils
    setTimeout(checkAllThresholds, 100);
  }

  // Fonction pour simuler la baisse dans le graphique
  function updateChartAfterConsumption(intrant, quantite) {
    const canvas = document.querySelector("#stockChart");
    if (!canvas || !window.Chart) return;
    
    const chart = Chart.getChart(canvas);
    if (!chart) return;
    
    // Trouver l'index de l'intrant dans le graphique
    const labels = chart.data.labels;
    const index = labels.findIndex(label => 
      label.toLowerCase().includes(intrant.toLowerCase())
    );
    
    if (index !== -1) {
      // Réduire la valeur dans le graphique
      const currentValue = chart.data.datasets[0].data[index];
      chart.data.datasets[0].data[index] = Math.max(0, currentValue - quantite);
      chart.update('active'); // Animation fluide
    }
  }

  // Fonction pour vérifier tous les seuils et mettre à jour les statuts
  function checkAllThresholds() {
    console.log("🔍 VÉRIFICATION AUTOMATIQUE DES SEUILS...");
    
    const stockRows = document.querySelectorAll("#stockTableBody tr");
    let criticalCount = 0;
    
    stockRows.forEach(row => {
      const nameCell = row.cells[0]?.textContent;
      const stockCell = row.cells[2]; // Colonne "Stock Actuel"
      const thresholdCell = row.cells[3]; // Colonne "Seuil Critique"
      const statusCell = row.cells[5]; // Colonne "Statut"
      
      if (!stockCell || !thresholdCell || !statusCell) return;
      
      const currentStock = parseInt(stockCell.textContent.replace(/[^\d]/g, '')) || 0;
      const threshold = parseInt(thresholdCell.textContent.replace(/[^\d]/g, '')) || 0;
      
      let statusText = "OK";
      let statusClass = "green";
      let pourcentage = 100;
      
      // Détection du seuil avec logique d'alerte
      if (currentStock <= threshold) {
        statusText = "CRITIQUE";
        statusClass = "red";
        pourcentage = Math.round((currentStock / threshold) * 100);
        criticalCount++;
        console.log(`🚨 ALERTE CRITIQUE: ${nameCell} - Stock: ${currentStock} kg, Seuil: ${threshold} kg`);
      } else if (currentStock <= threshold * 1.1) { // Moins de 10% d'écart
        statusText = "FAIBLE";
        statusClass = "orange";
        pourcentage = Math.round((currentStock / threshold) * 100);
        console.log(`⚠️ ALERTE FAIBLE: ${nameCell} - Stock: ${currentStock} kg, Seuil: ${threshold} kg`);
      } else {
        pourcentage = Math.round((currentStock / (threshold * 4)) * 100);
        console.log(`✅ STATUT NORMAL: ${nameCell} - Stock: ${currentStock} kg (${pourcentage}%)`);
      }
      
      statusCell.innerHTML = `<span class="badge ${statusClass}">${statusText}</span>`;
    });
    
    // Mettre à jour le compteur d'alertes critiques
    updateCriticalAlertsCounter(criticalCount);
    
    console.log(`📊 Vérification terminée - ${criticalCount} alerte(s) critique(s) trouvée(s)`);
  }

  // Fonction pour mettre à jour le compteur d'alertes critiques
  function updateCriticalAlertsCounter(count) {
    // Chercher la carte "Alertes Critiques" (ou similaire)
    const kpiCards = document.querySelectorAll(".kpi-value");
    
    kpiCards.forEach(card => {
      const parentCard = card.closest(".kpi-card");
      if (parentCard) {
        const title = parentCard.querySelector(".kpi-title")?.textContent;
        if (title && title.toLowerCase().includes("alerte")) {
          card.textContent = count;
          console.log(`🔢 Compteur d'alertes mis à jour: ${count}`);
          
          // Changer la couleur du compteur si des alertes existent
          if (count > 0) {
            card.style.color = "#ef4444"; // Rouge pour alertes
            card.style.fontWeight = "700";
          } else {
            card.style.color = ""; // Couleur par défaut
            card.style.fontWeight = "";
          }
        }
      }
    });
  }

  // Initialiser le formulaire après le chargement
  setTimeout(initConsumeForm, 300);
  
  // Initialiser le simulateur après le chargement
  setTimeout(initNeedsSimulator, 300);
  
  // Vérifier les seuils au chargement de la page
  setTimeout(() => {
    console.log("🚀 DÉMARRAGE VÉRIFICATION DES SEUILS...");
    checkAllThresholds();
  }, 500);
})();

