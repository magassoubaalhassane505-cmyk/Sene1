// Core partagé SeneBI (sans modules) — utilisable par chaque page *.js
(function () {
  const STORAGE_KEY = "senebi_state_v1";
  const AUTH_KEY = "senebi_auth_user";

  function serverAuth() {
    // When Laravel session auth is used, pages inject the current user into window.__SENEBI_AUTH__.
    // This prevents client-side redirects based on localStorage when server-side auth is active.
    const a = window.__SENEBI_AUTH__;
    if (!a || typeof a !== "object") return null;
    const role = a.role === "admin" ? "manager" : a.role;
    return { ...a, role };
  }

  function deepClone(obj) {
    return JSON.parse(JSON.stringify(obj));
  }

  const DefaultState = {
    season: "2026",
    seasons: ["2025", "2026"],
    capacity: { ureeKg: 5000, npkKg: 4500, semencesKg: 2500 },
    bySeason: {
      "2025": {
        pricesCereals: [
          { month: "Jan", price: 320 }, { month: "Fév", price: 325 }, { month: "Mar", price: 332 },
          { month: "Avr", price: 335 }, { month: "Mai", price: 342 }, { month: "Jun", price: 350 },
          { month: "Jul", price: 346 }, { month: "Aoû", price: 355 }, { month: "Sep", price: 360 },
          { month: "Oct", price: 365 }, { month: "Nov", price: 372 }, { month: "Déc", price: 378 },
        ],
        cultures: [{ name: "Riz", percent: 44 }, { name: "Maïs", percent: 33 }, { name: "Coton", percent: 23 }],
        parcels: [
          { id: "P-101", name: "Parcelle A", areaHa: 6.0, culture: "Riz", status: "En culture" },
          { id: "P-102", name: "Parcelle B", areaHa: 4.5, culture: "Maïs", status: "Récoltée" },
          { id: "P-103", name: "Parcelle C", areaHa: 3.2, culture: "Coton", status: "En jachère" },
          { id: "P-104", name: "Parcelle D", areaHa: 5.1, culture: "Riz", status: "En culture" },
        ],
        harvests: [{ id: "H-1", parcelId: "P-102", date: "2025-10-18", quantityKg: 24600 }],
        inventory: { ureeKg: 1800, npkKg: 1600, semencesKg: 820 },
        consumptionHistory: [
          { id: "C-1", parcelId: "P-101", item: "Urée", quantityKg: 300, date: "2025-05-12" },
          { id: "C-2", parcelId: "P-104", item: "NPK", quantityKg: 260, date: "2025-06-01" },
          { id: "C-3", parcelId: "P-101", item: "Semences", quantityKg: 120, date: "2025-04-22" },
        ],
        business: { salesFcfa: 14200000, intrantsCostFcfa: 6100000, exportsFcfa: 3200000 },
      },
      "2026": {
        pricesCereals: [
          { month: "Jan", price: 345 }, { month: "Fév", price: 352 }, { month: "Mar", price: 360 },
          { month: "Avr", price: 358 }, { month: "Mai", price: 366 }, { month: "Jun", price: 370 },
          { month: "Jul", price: 375 }, { month: "Aoû", price: 378 }, { month: "Sep", price: 382 },
          { month: "Oct", price: 385 }, { month: "Nov", price: 388 }, { month: "Déc", price: 392 },
        ],
        cultures: [{ name: "Riz", percent: 39 }, { name: "Maïs", percent: 36 }, { name: "Coton", percent: 25 }],
        parcels: [
          { id: "P-201", name: "Parcelle Alpha", areaHa: 10.5, culture: "Riz", status: "Récoltée" },
          { id: "P-202", name: "Parcelle Beta", areaHa: 5.0, culture: "Maïs", status: "Récoltée" },
          { id: "P-203", name: "Parcelle Gamma", areaHa: 2.9, culture: "Coton", status: "En jachère" },
        ],
        harvests: [
          { id: "H-2", parcelId: "P-201", date: "2026-11-03", quantityKg: 52900 },
          { id: "H-3", parcelId: "P-202", date: "2026-10-22", quantityKg: 25500 },
        ],
        inventory: { ureeKg: 520, npkKg: 900, semencesKg: 240 },
        consumptionHistory: [
          { id: "C-4", parcelId: "P-201", item: "Urée", quantityKg: 520, date: "2026-05-09" },
          { id: "C-5", parcelId: "P-202", item: "NPK", quantityKg: 410, date: "2026-05-20" },
          { id: "C-6", parcelId: "P-201", item: "Semences", quantityKg: 180, date: "2026-04-15" },
        ],
        business: { salesFcfa: 17000000, intrantsCostFcfa: 6900000, exportsFcfa: 4100000 },
      },
    },
  };

  function loadState() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      const parsed = raw ? JSON.parse(raw) : {};
      const state = {
        ...deepClone(DefaultState),
        ...parsed,
        bySeason: { ...deepClone(DefaultState.bySeason), ...(parsed.bySeason || {}) },
      };

      // Fusionner les donnees reelles du serveur pour la page Rentabilite
      if (window.SeneBI_RENTABILITE && typeof window.SeneBI_RENTABILITE.business === 'object') {
        const season = state.season || Object.keys(state.bySeason || DefaultState.bySeason)[0] || '2025';
        if (!state.bySeason[season]) {
          state.bySeason[season] = deepClone(DefaultState.bySeason[season] || {});
        }
        state.bySeason[season].business = {
          ...(state.bySeason[season].business || {}),
          ...window.SeneBI_RENTABILITE.business,
        };
      }

      return state;
    } catch {
      return deepClone(DefaultState);
    }
  }

  function saveState(state) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
  }

  function getSeasonData(state) {
    return state.bySeason[state.season];
  }

  function qs(sel, root = document) {
    return root.querySelector(sel);
  }

  function fmtInt(n) {
    return Number(n || 0).toLocaleString("fr-FR");
  }

  function getAuth() {
    const srv = serverAuth();
    if (srv) return srv;
    try {
      const parsed = JSON.parse(localStorage.getItem(AUTH_KEY) || "null");
      if (!parsed) return null;
      if (parsed.role === "admin") parsed.role = "manager";
      return parsed;
    } catch {
      return null;
    }
  }

  function roleHome(role) {
    if (role === "manager") return "/manager/dashboard";
    return "/client/dashboard";
  }

  function goToLogin() {
    window.location.href = "/";
  }

  function goToRoleHome(role) {
    window.location.href = roleHome(role);
  }

  function goToForbidden(message) {
    const encoded = encodeURIComponent(message || "Acces refuse.");
    window.location.href = `/403?message=${encoded}`;
  }

  function requireRole(allowedRoles, deniedMessage) {
    const auth = getAuth();
    if (!auth) {
      goToLogin();
      return null;
    }
    if (!allowedRoles.includes(auth.role)) {
      goToForbidden(deniedMessage || "Acces refuse.");
      return null;
    }
    return auth;
  }

  function bindTopbarAuth(auth) {
    const authPills = qs("#authPills");
    if (!authPills) return;
    if (!auth) {
      authPills.hidden = true;
      return;
    }
    authPills.hidden = false;
    const nameEl = qs("#authUserName");
    const portalBtn = qs("#portalBtn");
    const logoutBtn = qs("#globalLogoutBtn");
    if (nameEl) {
      nameEl.textContent = auth.name || auth.email || "Utilisateur";
      if (nameEl.tagName === "A") {
        const acc = auth.role === "manager" ? "/manager/compte" : "/client/mon-compte";
        nameEl.setAttribute("href", acc);
        nameEl.setAttribute("title", "Profil et paramètres du compte");
      }
    }
    if (portalBtn) {
      portalBtn.href = "/secure-portal";
    }
    if (logoutBtn && !logoutBtn.dataset.bound) {
      logoutBtn.dataset.bound = "1";
      logoutBtn.addEventListener("click", () => {
        localStorage.removeItem(AUTH_KEY);
        goToLogin();
      });
    }
  }

  function safePdfText(value) {
    // jsPDF may render narrow/non-breaking spaces as garbled symbols.
    return String(value ?? "")
      .replace(/\u202f|\u00a0/g, " ")
      .replace(/[’]/g, "'");
  }

  function fmtMoneyPdf(value) {
    const n = Number(value || 0);
    return safePdfText(`${n.toLocaleString("fr-FR")} FCFA`);
  }

  function fmtMillionsPdf(value) {
    const inMillions = Number(value || 0) / 1000000;
    return safePdfText(`${inMillions.toLocaleString("fr-FR", { minimumFractionDigits: 2, maximumFractionDigits: 2 })} M FCFA`);
  }

  /**
   * PDF bilan / rapport avec en-tête, cartes KPI et graphiques (jsPDF).
   * @param {{ state?: object, title: string, subtitle?: string, filename: string, charts?: { canvas: HTMLCanvasElement|null, title: string, heightPt?: number }[] }} options
   */
  function exportStyledFinancialPdf(options) {
    const { jsPDF } = window.jspdf || {};
    if (!jsPDF || !options) return false;
    const state = options.state || loadState();
    const business = getSeasonData(state).business;
    const profit = Number(business.salesFcfa || 0) - Number(business.intrantsCostFcfa || 0);
    const marginPct = business.salesFcfa > 0 ? (profit / business.salesFcfa) * 100 : 0;

    const doc = new jsPDF({ unit: "pt", format: "a4" });
    const pageW = doc.internal.pageSize.getWidth();
    const pageH = doc.internal.pageSize.getHeight();
    const m = 44;
    const accentLight = [18, 191, 138];
    const slate = [51, 65, 85];
    const muted = [100, 116, 139];

    const drawPageHeaderBand = () => {
      doc.setFillColor(...accentLight);
      doc.rect(0, 0, pageW, 5, "F");
    };

    let y = m;
    drawPageHeaderBand();

    doc.setFillColor(248, 250, 252);
    doc.setDrawColor(226, 232, 240);
    doc.setLineWidth(0.5);
    doc.roundedRect(m, y, pageW - 2 * m, 88, 5, 5, "FD");

    doc.setTextColor(...slate);
    doc.setFont("helvetica", "bold");
    doc.setFontSize(20);
    doc.text(safePdfText(options.title || "SeneBI"), m + 20, y + 34);

    doc.setFont("helvetica", "normal");
    doc.setFontSize(10);
    doc.setTextColor(...muted);
    const sub =
      options.subtitle ||
      `Saison ${state.season} · ${new Date().toLocaleDateString("fr-FR", { dateStyle: "long" })}`;
    doc.text(safePdfText(sub), m + 20, y + 54);

    doc.setFontSize(8.5);
    doc.setTextColor(148, 163, 184);
    doc.text("Document genere par SeneBI - Business Intelligence agricole", m + 20, y + 74);

    y += 104;

    const kpis = [
      { label: "Chiffre d'affaires", value: fmtMillionsPdf(business.salesFcfa), stripe: [16, 185, 129] },
      { label: "Couts intrants", value: fmtMillionsPdf(business.intrantsCostFcfa), stripe: [239, 68, 68] },
      { label: "Benefice net", value: fmtMillionsPdf(profit), stripe: [99, 102, 241] },
      {
        label: "Marge nette",
        value: `${marginPct.toLocaleString("fr-FR", { minimumFractionDigits: 1, maximumFractionDigits: 1 })}%`,
        stripe: [124, 58, 237],
      },
    ];

    const gap = 11;
    const boxW = (pageW - 2 * m - 3 * gap) / 4;
    let x = m;
    kpis.forEach((k) => {
      doc.setFillColor(255, 255, 255);
      doc.setDrawColor(226, 232, 240);
      doc.roundedRect(x, y, boxW, 66, 4, 4, "FD");
      doc.setFillColor(...k.stripe);
      doc.rect(x, y, 5, 66, "F");
      doc.setFont("helvetica", "normal");
      doc.setFontSize(8);
      doc.setTextColor(...muted);
      doc.text(safePdfText(k.label), x + 14, y + 22);
      doc.setFont("helvetica", "bold");
      doc.setFontSize(10.5);
      doc.setTextColor(30, 41, 59);
      const lines = doc.splitTextToSize(safePdfText(k.value), boxW - 22);
      doc.text(lines, x + 14, y + 42);
      x += boxW + gap;
    });

    y += 82;

    const charts = options.charts || [];
    charts.forEach((ch, idx) => {
      const cw = pageW - 2 * m;
      const chH = ch.heightPt || 210;
      const blockH = chH + 36;
      if (y + blockH > pageH - m - 28) {
        doc.addPage();
        y = m;
        drawPageHeaderBand();
      }

      doc.setFont("helvetica", "bold");
      doc.setFontSize(11.5);
      doc.setTextColor(...slate);
      doc.text(safePdfText(ch.title || `Graphique ${idx + 1}`), m, y);
      y += 18;

      if (ch.canvas) {
        try {
          const img = ch.canvas.toDataURL("image/png", 1.0);
          doc.setFillColor(255, 255, 255);
          doc.setDrawColor(226, 232, 240);
          doc.roundedRect(m, y, cw, chH, 4, 4, "FD");
          doc.addImage(img, "PNG", m + 10, y + 10, cw - 20, chH - 20);
        } catch {
          doc.setFont("helvetica", "italic");
          doc.setFontSize(9);
          doc.setTextColor(...muted);
          doc.text("Graphique non disponible.", m + 10, y + 24);
        }
      }
      y += chH + 22;
    });

    const totalPages = doc.internal.getNumberOfPages();
    for (let i = 1; i <= totalPages; i++) {
      doc.setPage(i);
      doc.setFont("helvetica", "normal");
      doc.setFontSize(8);
      doc.setTextColor(148, 163, 184);
      doc.text(`SeneBI · Page ${i} / ${totalPages}`, pageW / 2, pageH - 24, { align: "center" });
      doc.text("Confidentiel — usage interne", m, pageH - 24);
    }

    doc.save(options.filename || `SeneBI_${state.season}.pdf`);
    return true;
  }

  function renderTopbar(state) {
    const current = document.body.dataset.page || "dashboard";
    const nav = qs("[data-senebi-nav]");
    if (!nav) return;

    const auth = getAuth();
    
    // Navigation différente selon le rôle
    let links = [];
    
    if (auth && auth.role === "manager") {
      // Navigation Manager : Dashboard | Supervision | Visites
      links = [
        { href: "/manager/dashboard", label: "Dashboard", key: "dashboard", icon: "dashboard" },
        { href: "/manager/supervision", label: "Supervision", key: "supervision", icon: "supervision" },
        { href: "/manager/visites", label: "Visites", key: "visits", icon: "visits" },
      ];
    } else {
      // Navigation Client : Dashboard | Parcelles | Stocks | Rentabilité
      links = [
        { href: "/client/dashboard", label: "Dashboard", key: "dashboard", icon: "dashboard" },
        { href: "/client/parcelles", label: "Parcelles", key: "parcels", icon: "parcels" },
        { href: "/manager/stocks", label: "Stocks", key: "stocks", icon: "stocks" },
        { href: "/client/rentabilite", label: "Rentabilité", key: "business", icon: "business" },
      ];
    }
    const navLinks = links;
    const isIndex = location.pathname.replace(/\\/g, "/").toLowerCase().endsWith("/index.html") || location.pathname.endsWith("/") || location.pathname.toLowerCase().endsWith("\\index.html") || location.pathname.replace(/\\/g, "/").toLowerCase().endsWith("/dashboard.html");
    const isPages = location.pathname.replace(/\\/g, "/").toLowerCase().includes("/pages/");
    const adjusted = navLinks.map((l) => {
      if (isIndex && l.href.startsWith("../")) {
        return { ...l, href: l.href.replace("../", "") };
      }
      if (isPages && l.href.startsWith("pages/")) {
        return { ...l, href: l.href.replace("pages/", "") };
      }
      return l;
    });

    const icon = (name) => {
      const common = 'fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"';
      if (name === "dashboard") return `<svg viewBox="0 0 24 24" ${common}><path d="M3 13h8V3H3v10z"/><path d="M13 21h8V11h-8v10z"/><path d="M13 3h8v6h-8V3z"/><path d="M3 17h8v4H3v-4z"/></svg>`;
      if (name === "parcels") return `<svg viewBox="0 0 24 24" ${common}><path d="M3 6l9-3 9 3v12l-9 3-9-3V6z"/><path d="M12 3v18"/><path d="M3 6l9 3 9-3"/></svg>`;
      if (name === "stocks") return `<svg viewBox="0 0 24 24" ${common}><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4a2 2 0 0 0 1-1.73z"/><path d="M3.3 7l8.7 5 8.7-5"/><path d="M12 22V12"/></svg>`;
      if (name === "business") return `<svg viewBox="0 0 24 24" ${common}><path d="M12 1v22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/></svg>`;
      if (name === "supervision") return `<svg viewBox="0 0 24 24" ${common}><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>`;
      if (name === "visits") return `<svg viewBox="0 0 24 24" ${common}><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>`;
      return `<svg viewBox="0 0 24 24" ${common}><path d="M12 1v22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/></svg>`;
    };

    nav.innerHTML = adjusted
      .map((l) => {
        const active = (current === "dashboard" && l.key === "dashboard") || 
                   (current === "client-dashboard" && l.key === "dashboard") || 
                   current === l.key;
        return `<a href="${l.href}" class="${active ? "active" : ""}">${icon(l.icon)}<span>${l.label}</span></a>`;
      })
      .join("");

    // Le filtre par saison a été supprimé du header
    // Les données de saison restent disponibles pour les rapports PDF
    bindTopbarAuth(getAuth());
  }

  const existingSeneBI = window.SeneBI || {};
  window.SeneBI = {
    ...existingSeneBI,
    DefaultState,
    loadState,
    saveState,
    getSeasonData,
    qs,
    fmtInt,
    renderTopbar,
    getAuth,
    requireRole,
    goToLogin,
    goToRoleHome,
    exportStyledFinancialPdf,
  };
})();

