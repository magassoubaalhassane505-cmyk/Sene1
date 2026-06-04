(function () {
  const AUTH_KEY = "senebi_auth_user";
  const USERS_KEY = "senebi_auth_users";

  const defaultUsers = [
    { id: "U-1", name: "Mimi", company: "SeneBI", email: "mimi.manager@senebi.ml", password: "manager123", role: "manager", blocked: false },
    { id: "U-3", name: "Sidi", company: "Sidi Agri", email: "sidi@sidi-agri.ml", password: "client123", role: "client", blocked: false },
  ];

  function normalizeRole(role) {
    return role === "admin" ? "manager" : role;
  }

  function loadUsers() {
    try {
      const raw = localStorage.getItem(USERS_KEY);
      if (!raw) {
        localStorage.setItem(USERS_KEY, JSON.stringify(defaultUsers));
        return [...defaultUsers];
      }
      const parsed = JSON.parse(raw);
      if (!Array.isArray(parsed) || !parsed.length) return [...defaultUsers];
      const normalized = parsed
        .map((u, index) => ({
          id: u.id || `U-${index + 1}`,
          name: u.name || u.username || "Utilisateur",
          company: u.company || "SeneBI",
          email: u.email || (u.username ? `${u.username}@senebi.local` : ""),
          password: u.password || "",
          role: normalizeRole(u.role || "client"),
          blocked: Boolean(u.blocked),
        }))
        .filter((u) => u.email)
        .map((u) => {
          if (normalizeEmail(u.email) === "mimi.manager@senebi.ml" && u.role === "manager") {
            return { ...u, name: "Mimi" };
          }
          return u;
        });
      const source = normalized.length ? normalized : [...defaultUsers];
      const byEmail = new Map(source.map((u) => [normalizeEmail(u.email), u]));
      for (const fallback of defaultUsers) {
        const key = normalizeEmail(fallback.email);
        if (!byEmail.has(key)) byEmail.set(key, { ...fallback });
      }
      return [...byEmail.values()];
    } catch {
      return [...defaultUsers];
    }
  }

  function saveUsers(users) {
    localStorage.setItem(USERS_KEY, JSON.stringify(users));
  }

  function roleLabel(role) {
    if (role === "manager") return "Manager";
    return "Client";
  }

  function roleHome(role) {
    if (normalizeRole(role) === "manager") return "/manager/dashboard";
    return "/client/dashboard";
  }

  function getAuth() {
    try {
      const parsed = JSON.parse(localStorage.getItem(AUTH_KEY) || "null");
      if (!parsed) return null;

      const normalizedManagerEmail = "mimi.manager@senebi.ml";

      if (Array.isArray(parsed)) {
        const currentPath = window.location.pathname;
        const currentRole = currentPath.includes("/manager/") ? "manager" : "client";
        const userForRole = parsed.find((user) => normalizeRole(user.role || "client") === currentRole);
        if (!userForRole) return null;
        if (normalizeEmail(userForRole.email) === normalizedManagerEmail && normalizeRole(userForRole.role) === "manager" && userForRole.name !== "Mimi") {
          const corrected = { ...userForRole, name: "Mimi" };
          setAuth(corrected);
          return corrected;
        }
        setAuth(userForRole);
        return userForRole;
      }

      if (normalizeEmail(parsed.email) === normalizedManagerEmail && normalizeRole(parsed.role || "client") === "manager" && parsed.name !== "Mimi") {
        const corrected = { ...parsed, name: "Mimi" };
        setAuth(corrected);
        return corrected;
      }

      return parsed;
    } catch {
      return null;
    }
  }

  function setAuth(user) {
    localStorage.setItem(AUTH_KEY, JSON.stringify(user));
  }

  function clearAuth() {
    localStorage.removeItem(AUTH_KEY);
  }

  function requireAuth() {
    const auth = getAuth();
    if (!auth) {
      window.location.href = "/";
      return null;
    }
    return auth;
  }

  function requireRole(allowedRoles, deniedMessage) {
    const auth = requireAuth();
    if (!auth) return null;
    if (!allowedRoles.includes(auth.role)) {
      alert(deniedMessage || "Acces refuse pour votre role.");
      window.location.href = roleHome(auth.role);
      return null;
    }
    return auth;
  }

  function generatePassword() {
    return `senebi-${Math.random().toString(36).slice(2, 8)}`;
  }

  function normalizeEmail(value) {
    return String(value || "").trim().toLowerCase();
  }

  function managerScopeLabel(user) {
    if (user.role !== "manager") return "";
    return `Manager rattache a ${user.company}`;
  }

  function setFeedback(el, text, isError) {
    if (!el) return;
    el.textContent = text;
    el.className = `form-feedback ${isError ? "error" : "success"}`;
  }

  function renderAdminStats(users) {
    const wrap = document.querySelector("#adminStats");
    if (!wrap) return;
    const totals = {
      all: users.length,
      clients: users.filter((u) => u.role === "client").length,
      managers: users.filter((u) => u.role === "manager").length,
      blocked: users.filter((u) => u.blocked).length,
    };
    wrap.innerHTML = `
      <article class="stat-tile"><span>Total comptes</span><strong>${totals.all}</strong></article>
      <article class="stat-tile"><span>Clients</span><strong>${totals.clients}</strong></article>
      <article class="stat-tile"><span>Managers</span><strong>${totals.managers}</strong></article>
      <article class="stat-tile"><span>Comptes bloques</span><strong>${totals.blocked}</strong></article>
    `;
  }

  function userInitial(name) {
    const c = String(name || "?").trim().charAt(0);
    return c ? c.toUpperCase() : "?";
  }

  function renderUsersList(users) {
    const list = document.querySelector("#usersList");
    if (!list) return;
    list.innerHTML = users
      .map((u) => {
        const roleTag = u.blocked
          ? '<span class="user-role-pill user-role-pill--blocked">Bloqué</span>'
          : `<span class="user-role-pill user-role-pill--${u.role}">${roleLabel(u.role)}</span>`;
        return `
        <article class="user-row">
          <div class="user-main">
            <div class="user-avatar" aria-hidden="true">${userInitial(u.name)}</div>
            <div class="user-meta">
              <strong>${u.name}</strong>
              <span class="user-email">${u.email}</span>
              <span class="user-company">${u.company}</span>
              ${u.role === "manager" ? `<span class="user-extra">${managerScopeLabel(u)}</span>` : ""}
            </div>
          </div>
          <div class="user-role-cell">${roleTag}</div>
          <div class="user-toolbar" role="toolbar" aria-label="Actions sur ce compte">
            <div class="toolbar-group">
              <button class="tool-btn tool-btn--neutral" type="button" data-action="reset-pass" data-id="${u.id}">
                Régénérer l’accès
              </button>
            </div>
            <div class="toolbar-sep" aria-hidden="true"></div>
            <div class="toolbar-group toolbar-group--risk">
              <button class="tool-btn tool-btn--warn" type="button" data-action="toggle-block" data-id="${u.id}">
                ${u.blocked ? "Débloquer" : "Bloquer"}
              </button>
              <button class="tool-btn tool-btn--danger" type="button" data-action="delete" data-id="${u.id}">Supprimer</button>
            </div>
          </div>
        </article>
      `;
      })
      .join("");
  }

  function initLoginPage() {
    if (document.body.dataset.serverSide === '1') {
      return;
    }

    const form = document.querySelector("#loginForm");
    if (!form) return;
    const feedback = document.querySelector("#loginFeedback");
    saveUsers(loadUsers());

    form.addEventListener("submit", (e) => {
      e.preventDefault();
      const email = normalizeEmail(document.querySelector("#email")?.value || "");
      const password = (document.querySelector("#password")?.value || "").trim();
      const users = loadUsers();
      const match = users.find((u) => normalizeEmail(u.email) === email && u.password === password);
      if (!match) {
        setFeedback(feedback, "Email ou mot de passe incorrect.", true);
        return;
      }
      if (match.blocked) {
        setFeedback(feedback, "Compte bloque. Contactez le manager.", true);
        return;
      }
      setAuth({ id: match.id, name: match.name, email: match.email, company: match.company, role: normalizeRole(match.role) });
      window.location.href = roleHome(match.role);
    });
  }

  function initPortalPage() {
    const auth = getAuth();
    const state = window.SeneBI?.loadState ? window.SeneBI.loadState() : null;
    if (state && window.SeneBI?.renderTopbar) window.SeneBI.renderTopbar(state);
    const welcome = document.querySelector("#welcomeText");
    const badge = document.querySelector("#roleBadge");
    const adminPanel = document.querySelector("#adminPanel");

    // Utiliser en priorité la source serveur pour refléter fidèlement les validations manager.
    // Le localStorage sert uniquement de secours si aucune donnée serveur n'est injectée.
    const localUsers = loadUsers();
    const approvedFromServer = (window.SeneBI?.approvedClients || []).map((u, index) => ({
      id: String(u.id || `SRV-${index + 1}`),
      name: u.name || "Client",
      company: u.company || "SeneBI",
      email: normalizeEmail(u.email || ""),
      // Le mot de passe réel n'est pas exposé côté serveur; il peut être régénéré via l'UI.
      password: "",
      role: normalizeRole(u.role || "client"),
      blocked: Boolean(u.blocked),
    })).filter((u) => u.email);

    let users = approvedFromServer.length ? approvedFromServer : localUsers;
    saveUsers(users);
    
    renderUsersList(users);
    renderAdminStats(users);

    if (auth) {
      if (welcome) welcome.textContent = `Bonjour ${auth.name}. Vous etes connecte en tant que ${roleLabel(auth.role)}.`;
      if (badge) {
        badge.textContent = roleLabel(auth.role);
        badge.className = `tag ${auth.role === "manager" ? "good" : "warn"}`;
      }

      if (auth.role === "manager") {
        const form = document.querySelector("#createUserForm");
        const feedback = document.querySelector("#adminFeedback");
        const list = document.querySelector("#usersList");
        if (form) {
          form.addEventListener("submit", (e) => {
            e.preventDefault();
            const name = (document.querySelector("#newName")?.value || "").trim();
            const company = (document.querySelector("#newCompany")?.value || "").trim();
            const email = normalizeEmail(document.querySelector("#newEmail")?.value || "");
            const role = (document.querySelector("#newRole")?.value || "client").trim();
            if (!name || !company || !email) return;
            const current = loadUsers();
            if (current.some((u) => normalizeEmail(u.email) === email)) {
              setFeedback(feedback, "Cet email existe deja.", true);
              return;
            }
            const password = generatePassword();
            current.push({ id: `U-${Date.now()}`, name, company, email, password, role, blocked: false });
            saveUsers(current);
            renderUsersList(current);
            renderAdminStats(current);
            form.reset();
            setFeedback(feedback, `Compte cree. Acces genere: ${email} / ${password}`, false);
          });
        }
        if (list && !list.dataset.bound) {
          list.dataset.bound = "1";
          list.addEventListener("click", (event) => {
            const btn = event.target.closest("button[data-action]");
            if (!btn) return;
            const action = btn.dataset.action;
            const id = btn.dataset.id;
            const current = loadUsers();
            const idx = current.findIndex((u) => u.id === id);
            if (idx < 0) return;
            const selected = current[idx];
            if (selected.role === "manager" && action !== "reset-pass") {
              setFeedback(feedback, "Le compte manager principal ne peut pas etre modifie ici.", true);
              return;
            }
            if (action === "toggle-block") {
              selected.blocked = !selected.blocked;
              saveUsers(current);
              renderUsersList(current);
              renderAdminStats(current);
              setFeedback(feedback, selected.blocked ? "Compte bloque avec succes." : "Compte debloque avec succes.", false);
              return;
            }
            if (action === "delete") {
              current.splice(idx, 1);
              saveUsers(current);
              renderUsersList(current);
              renderAdminStats(current);
              setFeedback(feedback, "Compte supprime avec succes.", false);
              return;
            }
            if (action === "reset-pass") {
              const next = generatePassword();
              selected.password = next;
              saveUsers(current);
              renderUsersList(current);
              renderAdminStats(current);
              setFeedback(feedback, `Nouveaux acces: ${selected.email} / ${next}`, false);
            }
          });
        }
      }
    }
  }

  window.SeneAuth = {
    AUTH_KEY,
    getAuth,
    clearAuth,
    requireAuth,
    requireRole,
    roleLabel,
  };

  document.addEventListener("DOMContentLoaded", () => {
    // Only initialize JavaScript auth if NOT using server-side auth
    if (document.body.dataset.serverSide === '1') {
      // Server-side auth enabled - don't interfere with forms
      return;
    }

    const page = document.body.dataset.page || "";
    if (page === "auth-login") initLoginPage();
    if (page === "auth-portal") initPortalPage();
  });
})();
