(function () {
  const serverHarvests = window.SeneBI_RENTABILITE?.harvests;
  const harvests = Array.isArray(serverHarvests) && serverHarvests.length > 0
    ? serverHarvests
    : [
        { date: "15/11/2025", parcel: "Parcelle Nord (Riz)", qtyKg: 22000, unitPrice: 250 },
        { date: "20/02/2026", parcel: "Parcelle Sud (Mais)", qtyKg: 9600, unitPrice: 180 },
        { date: "10/12/2025", parcel: "Parcelle Centre (Mais)", qtyKg: 18000, unitPrice: 180 },
        { date: "20/06/2025", parcel: "Parcelle Nord (Riz)", qtyKg: 20000, unitPrice: 245 },
        { date: "15/08/2025", parcel: "Parcelle Sud (Mais)", qtyKg: 8800, unitPrice: 175 },
      ];

  function million(value) {
    return Number(value || 0) / 1000000;
  }

  function fmtM(value) {
    return million(value).toLocaleString("fr-FR", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function fmtMoney(value) {
    return `${Number(value || 0).toLocaleString("fr-FR")} FCFA`;
  }

  function bindCalculator(state, onApply) {
    const openBtn = SeneBI.qs("#openCalculatorBtn");
    const closeBtn = SeneBI.qs("#closeCalculatorBtn");
    const applyBtn = SeneBI.qs("#applyCalculatorBtn");
    const panel = SeneBI.qs("#calculatorPanel");
    const form = SeneBI.qs("#calculatorForm");
    const areaEl = SeneBI.qs("#calcArea");
    const qtyEl = SeneBI.qs("#calcQty");
    const priceEl = SeneBI.qs("#calcPrice");
    const intrantsEl = SeneBI.qs("#calcIntrants");
    const otherEl = SeneBI.qs("#calcOther");

    const yieldOut = SeneBI.qs("#calcYield");
    const revenueOut = SeneBI.qs("#calcRevenue");
    const profitOut = SeneBI.qs("#calcProfit");
    const marginOut = SeneBI.qs("#calcMargin");
    const verdictOut = SeneBI.qs("#calcVerdict");
    const feedbackEl = SeneBI.qs("#calculatorFeedback");

    const sanityChecks = [
      { el: areaEl, max: 200, label: "surface" },
      { el: qtyEl, max: 1000000, label: "quantite recoltee" },
      { el: priceEl, max: 100000, label: "prix unitaire" },
      { el: intrantsEl, max: 1000000000, label: "couts intrants" },
      { el: otherEl, max: 1000000000, label: "autres couts" },
    ];

    const recalc = () => {
      const area = Number(areaEl?.value || 0);
      const qty = Number(qtyEl?.value || 0);
      const price = Number(priceEl?.value || 0);
      const intrants = Number(intrantsEl?.value || 0);
      const other = Number(otherEl?.value || 0);

      const valid = area > 0 && qty > 0 && price > 0;
      const yieldKgHa = area > 0 ? qty / area : 0;
      const revenue = qty * price;
      const totalCosts = intrants + other;
      const profit = revenue - totalCosts;
      const margin = revenue > 0 ? (profit / revenue) * 100 : 0;
      let sanityError = "";

      sanityChecks.forEach((f) => {
        if (!f.el) return;
        f.el.classList.remove("input-error");
        const val = Number(f.el.value || 0);
        if (val > f.max) {
          f.el.classList.add("input-error");
          sanityError = `Valeur trop elevee pour ${f.label}. Merci de verifier.`;
        }
      });

      if (yieldOut) yieldOut.textContent = `${Math.round(yieldKgHa).toLocaleString("fr-FR")} kg/ha`;
      if (revenueOut) revenueOut.textContent = fmtMoney(revenue);
      if (profitOut) profitOut.textContent = fmtMoney(profit);
      if (marginOut) marginOut.textContent = `${margin.toLocaleString("fr-FR", { minimumFractionDigits: 1, maximumFractionDigits: 1 })}%`;

      if (verdictOut) {
        let text = "A completer";
        let color = "#475569";
        let bg = "#e2e8f0";
        if (revenue > 0) {
          if (margin >= 25) {
            text = "Rentable";
            color = "#166534";
            bg = "#dcfce7";
          } else if (margin >= 10) {
            text = "A surveiller";
            color = "#92400e";
            bg = "#fef3c7";
          } else {
            text = "Non rentable";
            color = "#991b1b";
            bg = "#fee2e2";
          }
        }
        verdictOut.textContent = text;
        verdictOut.style.color = color;
        verdictOut.style.background = bg;
      }

      if (feedbackEl) {
        if (sanityError) {
          feedbackEl.textContent = sanityError;
          feedbackEl.className = "form-feedback error";
        } else if (feedbackEl.classList.contains("error")) {
          feedbackEl.textContent = "";
          feedbackEl.className = "form-feedback";
        }
      }

      return { valid: valid && !sanityError, revenue, totalCosts, profit, margin, sanityError };
    };

    if (panel && !panel.dataset.initialized) {
      panel.dataset.initialized = "1";
      panel.classList.remove("show");
      panel.setAttribute("aria-hidden", "true");
    }

    if (openBtn && panel && !openBtn.dataset.calcBound) {
      openBtn.dataset.calcBound = "1";
      openBtn.addEventListener("click", () => {
        panel.classList.toggle("show");
        const isOpen = panel.classList.contains("show");
        panel.setAttribute("aria-hidden", isOpen ? "false" : "true");
        if (isOpen) panel.scrollIntoView({ behavior: "smooth", block: "start" });
      });
    }

    if (closeBtn && panel && !closeBtn.dataset.calcBound) {
      closeBtn.dataset.calcBound = "1";
      closeBtn.addEventListener("click", () => {
        panel.classList.remove("show");
        panel.setAttribute("aria-hidden", "true");
      });
    }

    if (form && !form.dataset.bound) {
      form.dataset.bound = "1";
      [areaEl, qtyEl, priceEl, intrantsEl, otherEl].forEach((el) => {
        if (el) el.addEventListener("input", recalc);
      });
    }

    if (applyBtn && !applyBtn.dataset.bound) {
      applyBtn.dataset.bound = "1";
      applyBtn.addEventListener("click", async () => {
        const values = recalc();
        if (!values.valid) {
          if (feedbackEl) {
            feedbackEl.textContent =
              values.sanityError || "Renseigne au minimum la surface, la quantite recoltee et le prix unitaire.";
            feedbackEl.className = "form-feedback error";
          }
          return;
        }

        const business = SeneBI.getSeasonData(state).business;
        business.salesFcfa = Math.round(values.revenue);
        business.intrantsCostFcfa = Math.round(values.totalCosts);
        SeneBI.saveState(state);

        try {
          const apiBase = window.SeneBI_SERVER?.apiBase;
          const csrf = window.SeneBI_SERVER?.csrf;
          if (apiBase && csrf) {
            const calcArea = document.getElementById('calcArea');
            const calcPrice = document.getElementById('calcPrice');
            const calcIntrants = document.getElementById('calcIntrants');
            const calcOther = document.getElementById('calcOther');
            const calcCulture = document.getElementById('calcCulture');
            await fetch(`${apiBase}/rentabilites`, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
              },
              body: JSON.stringify({
                parcelle_nom: 'Calculateur rentabilite',
                culture: calcCulture?.value || 'Global',
                quantite: Number(calcArea?.value || 0),
                prix_unitaire: Number(calcPrice?.value || 0),
                couts_totaux: Number(calcIntrants?.value || 0) + Number(calcOther?.value || 0),
              }),
            });
          }
        } catch (error) {
          console.warn('Erreur lors de la sauvegarde de la rentabilite:', error);
        }

        if (feedbackEl) {
          feedbackEl.textContent = "Bilan mis a jour avec les valeurs du calculateur.";
          feedbackEl.className = "form-feedback success";
        }
        if (typeof onApply === "function") onApply();
      });
    }

    recalc();
  }

  function bindButtons() {
    const topExportBtn = SeneBI.qs("#exportPdfBtn");
    const bottomExportBtn = SeneBI.qs("#exportPdfBottomBtn");

    const handlePdf = (button) => {
      if (!button) return;
      const { jsPDF } = window.jspdf || {};
      if (!jsPDF || !SeneBI.exportStyledFinancialPdf) return;
      const originalText = button.textContent;
      button.disabled = true;
      button.textContent = "Generation PDF...";
      try {
        const state = SeneBI.loadState();
        SeneBI.exportStyledFinancialPdf({
          state,
          title: "Bilan financier",
          subtitle: `Rentabilite · Saison ${state.season} · ${new Date().toLocaleDateString("fr-FR", { dateStyle: "long" })}`,
          filename: `SeneBI_Bilan_${state.season}.pdf`,
          charts: [
            { canvas: SeneBI.qs("#profitChart"), title: "Structure du bilan (millions FCFA)", heightPt: 230 },
            { canvas: SeneBI.qs("#cultureChart"), title: "Indicateurs de culture (t/ha)", heightPt: 210 },
          ],
        });
      } finally {
        button.disabled = false;
        button.textContent = originalText;
      }
    };

    [topExportBtn, bottomExportBtn].forEach((btn) => {
      if (btn && !btn.dataset.bound) {
        btn.dataset.bound = "1";
        btn.addEventListener("click", () => handlePdf(btn));
      }
    });
  }

  function renderTable() {
    const tbody = SeneBI.qs("#harvestRows");
    if (!tbody) return;
    let total = 0;
    const rows = harvests.map((h) => {
      const revenue = h.qtyKg * h.unitPrice;
      total += revenue;
      const revenueStyle = revenue > 5000000 ? 'style="color: #27ae60; font-weight: bold;"' : '';
      return `<tr>
        <td>${h.date}</td>
        <td>${h.parcel}</td>
        <td>${Number(h.qtyKg).toLocaleString("fr-FR")}</td>
        <td>${Number(h.unitPrice).toLocaleString("fr-FR")} FCFA</td>
        <td class="money" ${revenueStyle}>${Number(revenue).toLocaleString("fr-FR")} FCFA</td>
      </tr>`;
    });
    rows.push(`<tr class="total-row"><td colspan="4" style="text-align:right;">Total:</td><td>${total.toLocaleString("fr-FR")} FCFA</td></tr>`);
    tbody.innerHTML = rows.join("");
  }

  function renderCharts(sales, cost, profit) {
    if (!window.Chart) return;
    const pCanvas = SeneBI.qs("#profitChart");
    const cCanvas = SeneBI.qs("#cultureChart");

    if (pCanvas) {
      const existing = Chart.getChart(pCanvas);
      if (existing) existing.destroy();
      // Utiliser les valeurs réelles directement sans conversion en millions
      const mSales = sales;
      const mCost = cost;
      const mProfit = profit;
      // Pas de yMax fixe - l'axe s'adapte automatiquement avec beginAtZero

      new Chart(pCanvas, {
        type: "bar",
        data: {
          labels: ["Revenus", "Couts", "Benefice"],
          datasets: [
            {
              data: [mSales, mCost, mProfit],
              backgroundColor: ["#16a34a", "#dc2626", "#4f46e5"],
              borderRadius: 10,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: "rgba(15, 23, 42, 0.92)",
              padding: 10,
              cornerRadius: 10,
              callbacks: {
                label(ctx) {
                  const v = ctx.parsed.y;
                  return `${ctx.label} : ${Number(v).toLocaleString('fr-FR')} FCFA`;
                },
              },
            },
          },
          scales: {
            y: {
              beginAtZero: true,
              // max: yMax, // Supprimé pour adaptation automatique
              grid: { color: "rgba(15,23,42,0.07)" },
              ticks: { callback: (v) => `${Number(v).toLocaleString('fr-FR')}` },
              title: { display: true, text: "FCFA", font: { size: 11, weight: "600" } },
            },
            x: {
              grid: { display: false },
              ticks: { font: { size: 12, weight: "700" }, maxRotation: 0, autoSkip: false },
            },
          },
        },
      });
    }

    if (cCanvas) {
      const existing = Chart.getChart(cCanvas);
      if (existing) existing.destroy();

      const serverCultureYields = window.SeneBI_RENTABILITE?.cultureYields;
      let cultureLabels, cultureVals;

      if (Array.isArray(serverCultureYields) && serverCultureYields.length > 0) {
        cultureLabels = serverCultureYields.map(c => c.culture);
        cultureVals = serverCultureYields.map(c => c.rendement);
      } else {
        cultureLabels = ["Riz", "Mais", "Coton"];
        cultureVals = [10.5, 6.4, 4.2];
      }

      const yMaxC = Math.ceil(Math.max(...cultureVals, 1) * 11) / 10;

      new Chart(cCanvas, {
        type: "bar",
        data: {
          labels: cultureLabels,
          datasets: [
            {
              label: "t/ha",
              data: cultureVals,
              backgroundColor: ["#16a34a", "#ea580c", "#6366f1"],
              borderRadius: 10,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: "rgba(15, 23, 42, 0.92)",
              padding: 10,
              cornerRadius: 10,
              callbacks: {
                title(items) {
                  return items[0]?.label ? `Culture : ${items[0].label}` : "";
                },
                label(ctx) {
                  return `Rendement : ${ctx.parsed.y} t/ha`;
                },
              },
            },
          },
          scales: {
            y: {
              beginAtZero: true,
              max: yMaxC,
              grid: { color: "rgba(15,23,42,0.07)" },
              title: { display: true, text: "t/ha", font: { size: 11, weight: "600" } },
            },
            x: {
              grid: { display: false },
              ticks: { font: { size: 12, weight: "700" }, maxRotation: 0, autoSkip: false },
              title: { display: true, text: "Culture", font: { size: 11, weight: "600" } },
            },
          },
        },
      });
    }
  }

  function render(state) {
    const business = SeneBI.getSeasonData(state).business;
    const profit = business.salesFcfa - business.intrantsCostFcfa;
    const margin = business.salesFcfa > 0 ? (profit / business.salesFcfa) * 100 : 0;

    const salesKpi = SeneBI.qs("#salesKpi");
    const costKpi = SeneBI.qs("#costKpi");
    const profitKpi = SeneBI.qs("#profitKpi");
    const marginKpi = SeneBI.qs("#marginKpi");
    const marginStatus = SeneBI.qs("#marginStatus");
    const compareEl = SeneBI.qs("#seasonCompareText");

    if (salesKpi) {
      const numberEl = salesKpi.querySelector('.number');
      if (numberEl) numberEl.textContent = fmtM(business.salesFcfa);
    }
    if (costKpi) {
      const numberEl = costKpi.querySelector('.number');
      if (numberEl) numberEl.textContent = fmtM(business.intrantsCostFcfa);
    }
    if (profitKpi) {
      const numberEl = profitKpi.querySelector('.number');
      if (numberEl) numberEl.textContent = fmtM(profit);
    }
    if (marginKpi) {
      const numberEl = marginKpi.querySelector('.number');
      if (numberEl) numberEl.textContent = `${margin.toLocaleString("fr-FR", { minimumFractionDigits: 1, maximumFractionDigits: 1 })}%`;
    }
    if (marginStatus) {
      if (margin >= 25) {
        marginStatus.textContent = "Tres rentable";
        marginStatus.className = "good";
      } else if (margin >= 10) {
        marginStatus.textContent = "Rentabilite moyenne";
        marginStatus.className = "";
      } else {
        marginStatus.textContent = "A ameliorer";
        marginStatus.className = "";
      }
    }

    if (compareEl) {
      const currentSeason = String(state.season || "");
      const previousSeason = String(Number(currentSeason) - 1);
      const prevData = state.bySeason?.[previousSeason]?.business;
      if (prevData) {
        const prevSales = Number(prevData.salesFcfa || 0);
        const prevCost = Number(prevData.intrantsCostFcfa || 0);
        const salesDelta = business.salesFcfa - prevSales;
        const costDelta = business.intrantsCostFcfa - prevCost;
        const salesPct = prevSales > 0 ? (salesDelta / prevSales) * 100 : 0;
        const costPct = prevCost > 0 ? (costDelta / prevCost) * 100 : 0;
        const salesWord = salesDelta >= 0 ? "hausse" : "baisse";
        const costWord = costDelta >= 0 ? "hausse" : "baisse";
        compareEl.textContent =
          `Par rapport a ${previousSeason}: ${salesWord} du chiffre d'affaires de ${Math.abs(salesPct).toLocaleString("fr-FR", {
            minimumFractionDigits: 1,
            maximumFractionDigits: 1,
          })}% et ${costWord} des couts de ${Math.abs(costPct).toLocaleString("fr-FR", { minimumFractionDigits: 1, maximumFractionDigits: 1 })}%.`;
      } else {
        compareEl.textContent = "Aucune saison precedente trouvee pour faire la comparaison.";
      }
    }

    renderCharts(business.salesFcfa, business.intrantsCostFcfa, profit);
    renderTable();
    bindButtons();
    bindCalculator(state, () => render(state));
  }

  // Fonction de recherche et filtrage combiné pour le tableau
  function initHarvestSearch() {
    const searchInput = document.getElementById('harvestSearch');
    const regionSelect = document.getElementById('regionSelectRent');
    const tableRows = document.querySelectorAll('#harvestRows tr');
    
    if (!searchInput || !regionSelect) return;
    
    // Fonction de filtrage combiné
    function filterTable() {
      const searchTerm = searchInput.value.toLowerCase().trim();
      const selectedRegion = regionSelect.value;
      
      tableRows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length >= 2) {
          const parcelle = cells[1].textContent.toLowerCase(); // Colonne Parcelle
          const quantite = cells[2].textContent.toLowerCase(); // Colonne Quantité (pour culture)
          
          // Vérifier le critère de recherche
          const matchesSearch = searchTerm === '' || 
                              parcelle.includes(searchTerm) || 
                              quantite.includes(searchTerm);
          
          // Vérifier le critère de région
          let matchesRegion = true;
          if (selectedRegion !== 'all') {
            // Mapper les codes de région aux noms de parcelles
            const regionMap = {
              'bko': 'Bamako',
              'kay': 'Kayes', 
              'kou': 'Koulikoro',
              'seg': 'Ségou',
              'sik': 'Sikasso',
              'mop': 'Mopti',
              'tom': 'Tombouctou',
              'gao': 'Gao',
              'kid': 'Kidal'
            };
            const regionName = regionMap[selectedRegion] || selectedRegion;
            matchesRegion = parcelle.includes(regionName.toLowerCase());
          }
          
          // Appliquer le filtrage combiné
          row.style.display = (matchesSearch && matchesRegion) ? '' : 'none';
        }
      });
    }
    
    // Écouter les changements sur la barre de recherche
    searchInput.addEventListener('input', filterTable);
    
    // Écouter les changements sur le menu déroulant des régions
    regionSelect.addEventListener('change', filterTable);
  }

  // Fonction de codage couleur pour la Marge Nette
  function updateMarginColorCoding() {
    const marginKpi = document.getElementById('marginKpi');
    const marginIcon = document.querySelector('.kpi-icon.purple');
    
    if (!marginKpi || !marginIcon) return;
    
    // Extraire la valeur numérique du pourcentage
    const marginText = marginKpi.textContent;
    const marginValue = parseFloat(marginText.replace('%', '').trim());
    
    if (isNaN(marginValue)) return;
    
    // Définir les couleurs pastel élégantes
    let bgColor, textColor;
    
    if (marginValue > 50) {
      // Succès - vert pastel
      bgColor = '#dcfce7'; // vert pastel très clair
      textColor = '#166534'; // vert foncé élégant
    } else if (marginValue >= 20) {
      // Vigilance - orange/jaune pastel
      bgColor = '#fef3c7'; // jaune pastel très clair
      textColor = '#92400e'; // orange foncé élégant
    } else {
      // Alerte - rouge pastel
      bgColor = '#fee2e2'; // rouge pastel très clair
      textColor = '#991b1b'; // rouge foncé élégant
    }
    
    // Appliquer les couleurs à l'icône
    marginIcon.style.background = bgColor;
    marginIcon.style.color = textColor;
    
    // Optionnel: mettre aussi le texte du pourcentage en couleur subtile
    marginKpi.style.color = textColor;
  }

  document.addEventListener("DOMContentLoaded", function () {
    const auth = SeneBI.requireRole(
      ["manager", "client"],
      "Acces refuse. La rentabilite detaillee est reservee aux clients et au manager."
    );
    if (!auth) return;
    const state = SeneBI.loadState();
    SeneBI.renderTopbar(state);
    render(state);
    initHarvestSearch(); // Initialiser la recherche
    updateMarginColorCoding(); // Initialiser le codage couleur
    window.addEventListener("senebi:seasonChanged", function () {
      SeneBI.renderTopbar(state);
      render(state);
      initHarvestSearch(); // Réinitialiser la recherche après rechargement
      updateMarginColorCoding(); // Réinitialiser le codage couleur après rechargement
    });
  });

  // Script pour le calculateur Pro avec listes déroulantes
  function initProCalculator() {
    // Récupérer tous les champs du calculateur
    const calcParcel = document.getElementById('calcParcel');
    const calcCulture = document.getElementById('calcCulture');
    const calcArea = document.getElementById('calcArea');
    const calcQty = document.getElementById('calcQty');
    const calcPrice = document.getElementById('calcPrice');
    const calcIntrants = document.getElementById('calcIntrants');
    const calcOther = document.getElementById('calcOther');
    
    // Récupérer les champs de résultats
    const calcYield = document.getElementById('calcYield');
    const calcRevenue = document.getElementById('calcRevenue');
    const calcProfit = document.getElementById('calcProfit');
    const calcMargin = document.getElementById('calcMargin');
    const calcVerdict = document.getElementById('calcVerdict');
    
    // Vérifier que tous les éléments existent
    if (!calcParcel || !calcCulture || !calcArea || !calcQty || !calcPrice || !calcIntrants || !calcOther || 
        !calcYield || !calcRevenue || !calcProfit || !calcMargin || !calcVerdict) {
      return;
    }
    
    // Dictionnaire intelligent des parcelles - Donnees reelles du serveur
    const serverParcelles = window.SeneBI_RENTABILITE?.parcellesData || [];
    const parcelleData = serverParcelles.length > 0
      ? serverParcelles.reduce((acc, p) => {
          const nom = p.nom || '';
          const baseName = nom.replace(/^Parcelle\s+/i, '').trim();
          acc[baseName] = {
            surface: Number(p.surface) || 0,
            intrants: 0,
            other: 0
          };
          return acc;
        }, {})
      : {
          'Parcelle Nord': { surface: 5.5, intrants: 2500000, other: 400000 },
          'Parcelle Centre': { surface: 3.2, intrants: 1800000, other: 300000 },
          'Parcelle Sud': { surface: 4.8, intrants: 3200000, other: 500000 }
        };
    
    // Fonction pour récupérer intelligemment les données de la parcelle
    function smartFillParcelleData(parcelleName, cultureName) {
      const data = parcelleData[parcelleName];
      if (data) {
        // Adapter les coûts selon la culture
        let adjustedIntrants = data.intrants;
        let adjustedOther = data.other;
        
        if (cultureName === 'Riz') {
          adjustedIntrants = data.intrants * 1.0; // Base
          adjustedOther = data.other * 1.0;
        } else if (cultureName === 'Maïs') {
          adjustedIntrants = data.intrants * 0.8; // 20% moins cher
          adjustedOther = data.other * 0.9;
        } else if (cultureName === 'Coton') {
          adjustedIntrants = data.intrants * 1.3; // 30% plus cher
          adjustedOther = data.other * 1.2;
        }
        
        calcArea.value = data.surface;
        calcIntrants.value = Math.round(adjustedIntrants);
        calcOther.value = Math.round(adjustedOther);
      }
    }
    
    // Fonction d'animation de compteur ultra-esthétique avec fondu
    function animateCounter(element, start, end, duration, suffix = '') {
      const startTime = performance.now();
      const isNegative = end < 0;
      const absEnd = Math.abs(end);
      
      // Réinitialiser l'animation de fondu
      element.style.opacity = '0';
      element.style.animation = 'none';
      
      // Forcer le reflow pour redémarrer l'animation
      element.offsetHeight;
      
      // Démarrer l'animation de fondu
      element.style.animation = `fadeInUp 0.6s ease forwards`;
      
      function updateCounter(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        // Easing function ultra-doux
        const easeOutQuart = 1 - Math.pow(1 - progress, 4);
        const currentValue = start + (absEnd - start) * easeOutQuart;
        
        // Formater le nombre avec style BI
        let displayValue;
        if (suffix.includes('FCFA')) {
          displayValue = Math.round(currentValue).toLocaleString('fr-FR');
        } else if (suffix.includes('%')) {
          displayValue = currentValue.toFixed(1);
        } else {
          displayValue = currentValue.toFixed(2);
        }
        
        element.textContent = (isNegative ? '-' : '') + displayValue + suffix;
        
        if (progress < 1) {
          requestAnimationFrame(updateCounter);
        }
      }
      
      requestAnimationFrame(updateCounter);
    }

    // Fonction pour mettre à jour les styles ultra-esthétiques des cartes
    function updateCardStyles(revenue, benefice, marge) {
      // Récupérer les cartes premium
      const yieldCard = document.getElementById('yieldCard');
      const revenueCard = document.getElementById('revenueCard');
      const profitCard = document.getElementById('profitCard');
      const marginCard = document.getElementById('marginCard');
      
      // Effet de surbrillance premium pour les cartes avec des valeurs
      if (revenue > 0) {
        revenueCard.style.transform = 'translateY(-2px) scale(1.02)';
        revenueCard.style.boxShadow = '0 8px 32px rgba(34, 197, 94, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.2)';
        revenueCard.style.background = 'linear-gradient(135deg, #bbf7d0 0%, #86efac 50%, #4ade80 100%)';
      }
      
      // Bénéfice positif avec style premium
      if (benefice > 0) {
        profitCard.style.transform = 'translateY(-2px) scale(1.02)';
        profitCard.style.boxShadow = '0 8px 32px rgba(16, 185, 129, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.2)';
        profitCard.style.background = 'linear-gradient(135deg, #bbf7d0 0%, #86efac 50%, #34d399 100%)';
        calcProfit.style.color = '#047857'; // Vert émeraude premium
        
        // Afficher le badge premium POSITIF
        const profitBadge = document.getElementById('profitBadge');
        profitBadge.style.display = 'inline-block';
        profitBadge.style.transform = 'scale(1.1)';
      } else if (benefice < 0) {
        profitCard.style.transform = 'translateY(-2px) scale(1.02)';
        profitCard.style.boxShadow = '0 8px 32px rgba(239, 68, 68, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.2)';
        profitCard.style.background = 'linear-gradient(135deg, #fecaca 0%, #f87171 50%, #ef4444 100%)';
        calcProfit.style.color = '#7f1d1d';
      }
      
      // Badge Excellent premium si marge > 30%
      const excellentBadge = document.getElementById('excellentBadge');
      if (marge > 30) {
        marginCard.style.transform = 'translateY(-2px) scale(1.02)';
        marginCard.style.boxShadow = '0 8px 32px rgba(245, 158, 11, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.2)';
        marginCard.style.background = 'linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%)';
        excellentBadge.style.display = 'inline-block';
        excellentBadge.style.transform = 'scale(1.1)';
      } else if (marge >= 10) {
        marginCard.style.transform = 'translateY(-1px)';
        marginCard.style.boxShadow = '0 6px 24px rgba(245, 158, 11, 0.2)';
        marginCard.style.background = 'linear-gradient(135deg, #fde68a 0%, #fcd34d 50%, #fbbf24 100%)';
        excellentBadge.style.display = 'none';
      } else if (marge < 10) {
        marginCard.style.transform = 'translateY(-1px)';
        marginCard.style.boxShadow = '0 6px 24px rgba(239, 68, 68, 0.2)';
        marginCard.style.background = 'linear-gradient(135deg, #fde68a 0%, #fbbf24 50%, #f59e0b 100%)';
        excellentBadge.style.display = 'none';
      }
    }

    // Fonction pour mettre à jour le Badge Premium Verdict
    function updateVerdictStyling(marge, verdictText, verdictColor) {
      const verdictContainer = document.getElementById('calcVerdictContainer');
      const verdictIcon = document.getElementById('verdictIcon');
      
      // Mettre à jour le texte premium
      calcVerdict.textContent = verdictText;
      calcVerdict.style.color = 'white';
      calcVerdict.style.fontWeight = '900';
      calcVerdict.style.fontSize = '24px';
      
      // Styliser le Badge Premium selon la marge avec impact maximal
      if (marge > 30) {
        verdictContainer.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%)';
        verdictContainer.style.boxShadow = '0 12px 48px rgba(16, 185, 129, 0.5), inset 0 2px 0 rgba(255, 255, 255, 0.3)';
        verdictContainer.style.transform = 'scale(1.05)';
        verdictIcon.textContent = '🎉';
        verdictIcon.style.fontSize = '36px';
        verdictIcon.style.color = 'white';
        verdictIcon.style.textShadow = '0 4px 8px rgba(0,0,0,0.3)';
        
        // Mettre à jour le label premium
        const verdictLabel = verdictContainer.querySelector('span');
        if (verdictLabel) {
          verdictLabel.textContent = 'EXCELLENCE RENTABILITÉ';
          verdictLabel.style.color = 'rgba(255, 255, 255, 0.9)';
        }
      } else if (marge >= 10) {
        verdictContainer.style.background = 'linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%)';
        verdictContainer.style.boxShadow = '0 12px 48px rgba(59, 130, 246, 0.5), inset 0 2px 0 rgba(255, 255, 255, 0.3)';
        verdictContainer.style.transform = 'scale(1.02)';
        verdictIcon.textContent = '✅';
        verdictIcon.style.fontSize = '32px';
        verdictIcon.style.color = 'white';
        verdictIcon.style.textShadow = '0 4px 8px rgba(0,0,0,0.3)';
        
        // Mettre à jour le label premium
        const verdictLabel = verdictContainer.querySelector('span');
        if (verdictLabel) {
          verdictLabel.textContent = 'BONNE RENTABILITÉ';
          verdictLabel.style.color = 'rgba(255, 255, 255, 0.9)';
        }
      } else if (marge >= 0) {
        verdictContainer.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #b91c1c 100%)';
        verdictContainer.style.boxShadow = '0 12px 48px rgba(239, 68, 68, 0.6), inset 0 2px 0 rgba(255, 255, 255, 0.2)';
        verdictContainer.style.transform = 'scale(1.08)'; // Plus grand pour alerter
        verdictIcon.textContent = '⚠️';
        verdictIcon.style.fontSize = '38px';
        verdictIcon.style.color = 'white';
        verdictIcon.style.textShadow = '0 4px 12px rgba(0,0,0,0.4)';
        verdictIcon.style.animation = 'pulse 2s infinite';
        
        // Mettre à jour le label premium avec alerte
        const verdictLabel = verdictContainer.querySelector('span');
        if (verdictLabel) {
          verdictLabel.textContent = 'ALERTE RENTABILITÉ';
          verdictLabel.style.color = 'rgba(255, 255, 255, 0.95)';
          verdictLabel.style.fontWeight = '800';
        }
      } else {
        verdictContainer.style.background = 'linear-gradient(135deg, #dc2626 0%, #b91c1c 50%, #991b1b 100%)';
        verdictContainer.style.boxShadow = '0 16px 64px rgba(220, 38, 38, 0.7), inset 0 2px 0 rgba(255, 255, 255, 0.1)';
        verdictContainer.style.transform = 'scale(1.12)'; // Très grand pour urgence
        verdictIcon.textContent = '🚨';
        verdictIcon.style.fontSize = '42px';
        verdictIcon.style.color = 'white';
        verdictIcon.style.textShadow = '0 4px 16px rgba(0,0,0,0.5)';
        verdictIcon.style.animation = 'pulse 1s infinite';
        
        // Mettre à jour le label premium avec urgence
        const verdictLabel = verdictContainer.querySelector('span');
        if (verdictLabel) {
          verdictLabel.textContent = 'URGENCE PERTE';
          verdictLabel.style.color = 'rgba(255, 255, 255, 1)';
          verdictLabel.style.fontWeight = '900';
          verdictLabel.style.animation = 'blink 1s infinite';
        }
      }
    }

    // Fonction pour effectuer tous les calculs en temps réel avec animations
    function performRealTimeCalculations() {
      // Récupérer toutes les valeurs
      const area = parseFloat(calcArea.value) || 0;
      const qty = parseFloat(calcQty.value) || 0;
      const price = parseFloat(calcPrice.value) || 0;
      const intrants = parseFloat(calcIntrants.value) || 0;
      const other = parseFloat(calcOther.value) || 0;
      
      // Calculs complets
      const rendement = area > 0 ? qty / area : 0;
      const revenueTotal = qty * price;
      const beneficeNet = revenueTotal - intrants - other;
      const margeNette = revenueTotal > 0 ? (beneficeNet / revenueTotal) * 100 : 0;
      
      // Animer les compteurs
      animateCounter(calcYield, 0, rendement, 800, ' kg/ha');
      animateCounter(calcRevenue, 0, revenueTotal, 1000, ' FCFA');
      animateCounter(calcProfit, 0, beneficeNet, 1200, ' FCFA');
      animateCounter(calcMargin, 0, margeNette, 900, '%');
      
      // Mettre à jour les styles des cartes
      updateCardStyles(revenueTotal, beneficeNet, margeNette);
      
      // Déterminer le verdict et les couleurs
      let verdictText, verdictColor;
      
      if (margeNette > 30) {
        verdictText = '🎉 Excellent ! Très rentable';
        verdictColor = '#166534';
      } else if (margeNette >= 10) {
        verdictText = '✅ Bon ! Rentable';
        verdictColor = '#92400e';
      } else if (margeNette >= 0) {
        verdictText = '⚠️ Attention ! Marge faible';
        verdictColor = '#991b1b';
      } else {
        verdictText = '🚨 Perte ! Non rentable';
        verdictColor = '#7f1d1d';
      }
      
      // Mettre à jour le verdict stylisé
      updateVerdictStyling(margeNette, verdictText, verdictColor);
    }
    
    // Ajouter les écouteurs d'événements sur TOUS les champs pour le calcul en temps réel
    calcParcel.addEventListener('change', function() {
      const parcelleName = this.value;
      const cultureName = calcCulture.value;
      if (parcelleName && cultureName) {
        smartFillParcelleData(parcelleName, cultureName);
      }
      performRealTimeCalculations();
    });
    
    calcCulture.addEventListener('change', function() {
      const parcelleName = calcParcel.value;
      const cultureName = this.value;
      if (parcelleName && cultureName) {
        smartFillParcelleData(parcelleName, cultureName);
      }
      performRealTimeCalculations();
    });
    
    calcArea.addEventListener('input', performRealTimeCalculations);
    calcQty.addEventListener('input', performRealTimeCalculations);
    calcPrice.addEventListener('input', performRealTimeCalculations);
    calcIntrants.addEventListener('input', performRealTimeCalculations);
    calcOther.addEventListener('input', performRealTimeCalculations);
    
    // Initialiser avec les valeurs par défaut si une parcelle est déjà sélectionnée
    if (calcParcel.value && calcCulture.value) {
      smartFillParcelleData(calcParcel.value, calcCulture.value);
    }
    performRealTimeCalculations();
  }

  // Fonction pour rafraîchir tout le dashboard
  function refreshDashboard() {
    // Récupérer les données du calculateur
    const calcArea = document.getElementById('calcArea');
    const calcQty = document.getElementById('calcQty');
    const calcPrice = document.getElementById('calcPrice');
    const calcIntrants = document.getElementById('calcIntrants');
    const calcOther = document.getElementById('calcOther');
    const calcCulture = document.getElementById('calcCulture');
    
    const area = parseFloat(calcArea.value) || 0;
    const qty = parseFloat(calcQty.value) || 0;
    const price = parseFloat(calcPrice.value) || 0;
    const intrants = parseFloat(calcIntrants.value) || 0;
    const other = parseFloat(calcOther.value) || 0;
    const culture = calcCulture.value || 'Inconnue';
    
    // Calculs cohérents avec le calculateur
    const revenueTotal = qty * price;
    const beneficeNet = revenueTotal - intrants - other;
    const margeNette = revenueTotal > 0 ? (beneficeNet / revenueTotal) * 100 : 0;
    
    // Mettre à jour les cartes KPI
    const salesKpi = document.getElementById('salesKpi');
    const costKpi = document.getElementById('costKpi');
    const profitKpi = document.getElementById('profitKpi');
    const marginKpi = document.getElementById('marginKpi');
    const marginStatus = document.getElementById('marginStatus');
    
    if (salesKpi) salesKpi.textContent = (revenueTotal / 1000000).toFixed(2);
    if (costKpi) costKpi.textContent = ((intrants + other) / 1000000).toFixed(2);
    if (profitKpi) profitKpi.textContent = (beneficeNet / 1000000).toFixed(2);
    if (marginKpi) marginKpi.textContent = `${margeNette.toFixed(1)}%`;
    
    // Mettre à jour le statut de marge
    if (marginStatus) {
      if (margeNette > 30) {
        marginStatus.textContent = 'Très rentable';
        marginStatus.className = 'good';
      } else if (margeNette >= 10) {
        marginStatus.textContent = 'Rentable';
        marginStatus.className = 'good';
      } else if (margeNette >= 0) {
        marginStatus.textContent = 'Faible marge';
        marginStatus.className = '';
      } else {
        marginStatus.textContent = 'Perte';
        marginStatus.className = 'bad';
      }
    }
    
    // Mettre à jour les graphiques (hauteurs des barres CSS)
    updateChartBars(revenueTotal, intrants + other, beneficeNet);
    
    // Mettre à jour le tableau des récoltes
    updateHarvestTable(culture, qty, price, revenueTotal);
    
    // Appliquer le codage couleur sur la marge
    updateMarginColorCoding();
  }
  
  // Fonction pour mettre à jour les barres des graphiques
  function updateChartBars(revenue, costs, profit) {
    // Calculer les hauteurs relatives (max 100px)
    const maxValue = Math.max(revenue, costs, Math.abs(profit), 1);
    const revenueHeight = (revenue / maxValue) * 100;
    const costsHeight = (costs / maxValue) * 100;
    const profitHeight = Math.abs(profit / maxValue) * 100;
    
    // Mettre à jour les barres CSS si elles existent
    const chartBars = document.querySelectorAll('.chart-bar');
    if (chartBars.length >= 3) {
      chartBars[0].style.height = `${revenueHeight}px`; // Revenus
      chartBars[1].style.height = `${costsHeight}px`;    // Coûts
      chartBars[2].style.height = `${profitHeight}px`;   // Bénéfice
      chartBars[2].style.background = profit >= 0 ? '#10b981' : '#ef4444'; // Vert/Rouge
    }
  }
  
  // Fonction pour mettre à jour le tableau des récoltes
  function updateHarvestTable(culture, qty, price, revenue) {
    const tbody = document.getElementById('harvestRows');
    if (!tbody) return;
    
    // Ajouter une nouvelle ligne au tableau
    const today = new Date().toLocaleDateString('fr-FR');
    const newRow = document.createElement('tr');
    
    // Style pour les revenus élevés
    const revenueStyle = revenue > 5000000 ? 'style="color: #27ae60; font-weight: bold;"' : '';
    
    newRow.innerHTML = `
      <td>${today}</td>
      <td>Nouvelle parcelle (${culture})</td>
      <td>${Number(qty).toLocaleString("fr-FR")}</td>
      <td>${Number(price).toLocaleString("fr-FR")} FCFA</td>
      <td class="money" ${revenueStyle}>${Number(revenue).toLocaleString("fr-FR")} FCFA</td>
    `;
    
    // Insérer avant la ligne de total
    const totalRow = tbody.querySelector('.total-row');
    if (totalRow) {
      tbody.insertBefore(newRow, totalRow);
    } else {
      tbody.appendChild(newRow);
    }
    
    // Mettre à jour le total
    updateTableTotal();
  }
  
  // Fonction pour mettre à jour le total du tableau
  function updateTableTotal() {
    const tbody = document.getElementById('harvestRows');
    if (!tbody) return;
    
    const rows = tbody.querySelectorAll('tr:not(.total-row)');
    let total = 0;
    
    rows.forEach(row => {
      const cells = row.querySelectorAll('td');
      if (cells.length >= 5) {
        const revenueText = cells[4].textContent.replace(/[^\d]/g, '');
        const revenue = parseFloat(revenueText) || 0;
        total += revenue;
      }
    });
    
    // Mettre à jour la ligne de total
    const totalRow = tbody.querySelector('.total-row');
    if (totalRow) {
      const totalCell = totalRow.querySelector('td:last-child');
      if (totalCell) {
        totalCell.textContent = `${total.toLocaleString('fr-FR')} FCFA`;
      }
    }
  }
  
  // Fonction pour afficher le message de succès
  function showSuccessMessage() {
    const feedback = document.getElementById('calculatorFeedback');
    if (feedback) {
      feedback.textContent = 'Données synchronisées avec succès !';
      feedback.style.background = '#dcfce7';
      feedback.style.color = '#166534';
      feedback.style.padding = '12px 16px';
      feedback.style.borderRadius = '8px';
      feedback.style.marginTop = '12px';
      feedback.style.display = 'block';
      
      // Masquer après 3 secondes
      setTimeout(() => {
        feedback.style.display = 'none';
      }, 3000);
    }
  }

  // Ajouter l'écouteur sur le bouton "Appliquer au bilan"
  const applyBtn = document.getElementById('applyCalculatorBtn');
  if (applyBtn) {
    applyBtn.addEventListener('click', function() {
      refreshDashboard();
      showSuccessMessage();
    });
  }

  // Initialiser le calculateur Pro
  setTimeout(initProCalculator, 500);
})();

