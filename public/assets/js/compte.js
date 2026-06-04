(function () {
  const USERS_KEY = "senebi_auth_users";
  const PREFS_KEY = "senebi_notification_prefs_v1";
  const STATE_KEY = "senebi_state_v1";

  const defaultPrefs = {
    emailAlerts: true,
    stockAlerts: true,
    parcelReminders: true,
    weeklyDigest: false,
    smsAlerts: false,
  };

  function normalizeEmail(value) {
    return String(value || "")
      .trim()
      .toLowerCase();
  }

  function roleLabel(role) {
    if (role === "admin") return "Manager";
    if (role === "manager") return "Manager";
    return "Client";
  }

  function roleClass(role) {
    if (role === "manager") return "role-pill role-pill--manager";
    return "role-pill role-pill--client";
  }

  const defaultUsers = [
    { id: "U-1", name: "Mimi", company: "SeneBI", email: "mimi.manager@senebi.ml", password: "manager123", role: "manager", blocked: false },
    { id: "U-3", name: "Sidi", company: "Sidi Agri", email: "sidi@sidi-agri.ml", password: "client123", role: "client", blocked: false },
  ];

  function loadUsers() {
    try {
      const raw = localStorage.getItem(USERS_KEY);
      if (!raw) {
        saveUsers(defaultUsers);
        return [...defaultUsers];
      }
      const parsed = JSON.parse(raw);
      if (!Array.isArray(parsed) || !parsed.length) {
        saveUsers(defaultUsers);
        return [...defaultUsers];
      }
      const corrected = parsed.map((u) => {
        if (normalizeEmail(u.email) === "mimi.manager@senebi.ml" && u.role === "manager") {
          return { ...u, name: "Mimi" };
        }
        return u;
      });
      if (JSON.stringify(corrected) !== JSON.stringify(parsed)) {
        saveUsers(corrected);
      }
      return corrected;
    } catch {
      saveUsers(defaultUsers);
      return [...defaultUsers];
    }
  }

  function saveUsers(users) {
    localStorage.setItem(USERS_KEY, JSON.stringify(users));
  }

  function prefsKeyFor(email) {
    return normalizeEmail(email);
  }

  function loadPrefs(email) {
    const key = prefsKeyFor(email);
    try {
      const raw = localStorage.getItem(PREFS_KEY);
      const all = raw ? JSON.parse(raw) : {};
      return { ...defaultPrefs, ...(all[key] || {}) };
    } catch {
      return { ...defaultPrefs };
    }
  }

  function savePrefs(email, prefs) {
    const key = prefsKeyFor(email);
    try {
      const raw = localStorage.getItem(PREFS_KEY);
      const all = raw ? JSON.parse(raw) : {};
      all[key] = prefs;
      localStorage.setItem(PREFS_KEY, JSON.stringify(all));
    } catch {
      localStorage.setItem(PREFS_KEY, JSON.stringify({ [key]: prefs }));
    }
  }

  function setFeedback(el, text, kind) {
    if (!el) return;
    el.textContent = text || "";
    el.className = "form-feedback" + (kind === "ok" ? " ok" : kind === "err" ? " err" : "");
  }

  function downloadJson(filename, payload) {
    const blob = new Blob([JSON.stringify(payload, null, 2)], { type: "application/json" });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    a.remove();
    URL.revokeObjectURL(url);
  }

  function initial(name) {
    const c = String(name || "?").trim().charAt(0);
    return c ? c.toUpperCase() : "?";
  }

  document.addEventListener("DOMContentLoaded", function () {
    const auth = SeneBI.requireRole(["manager", "client"], "Connexion requise.");
    if (!auth) return;

    const state = SeneBI.loadState();
    SeneBI.renderTopbar(state);

    const back = SeneBI.qs("#compteBackLink");
    if (back) {
      const home = auth.role === "manager" ? "/secure-portal" : "/client/dashboard";
      back.href = home;
    }

    SeneBI.qs("#fieldName").textContent = auth.name || "—";
    SeneBI.qs("#fieldEmail").textContent = auth.email || "—";
    SeneBI.qs("#fieldCompany").textContent = auth.company || "—";

    const roleEl = SeneBI.qs("#fieldRole");
    if (roleEl) {
      roleEl.textContent = roleLabel(auth.role);
      roleEl.className = roleClass(auth.role);
    }

    const av = SeneBI.qs("#profileAvatar");
    if (av) av.textContent = initial(auth.name);

    const prefs = loadPrefs(auth.email);
    
    // Vérifier que les éléments existent avant de les modifier
    const prefEmail = SeneBI.qs("#prefEmail");
    const prefStock = SeneBI.qs("#prefStock");
    const prefParcel = SeneBI.qs("#prefParcel");
    const prefDigest = SeneBI.qs("#prefDigest");
    const prefSms = SeneBI.qs("#prefSms");
    
    if (prefEmail) prefEmail.checked = !!prefs.emailAlerts;
    if (prefStock) prefStock.checked = !!prefs.stockAlerts;
    if (prefParcel) prefParcel.checked = !!prefs.parcelReminders;
    if (prefDigest) prefDigest.checked = !!prefs.weeklyDigest;
    if (prefSms) prefSms.checked = !!prefs.smsAlerts;

    const pwdForm = SeneBI.qs("#passwordForm");
    if (pwdForm && !pwdForm.dataset.bound) {
      pwdForm.dataset.bound = "1";
      pwdForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const cur = SeneBI.qs("#currentPassword")?.value || "";
        const neu = SeneBI.qs("#newPassword")?.value || "";
        const conf = SeneBI.qs("#confirmPassword")?.value || "";
        const fb = SeneBI.qs("#passwordFeedback");

        if (neu.length < 6) {
          setFeedback(fb, "Le nouveau mot de passe doit contenir au moins 6 caractères.", "err");
          return;
        }
        if (neu !== conf) {
          setFeedback(fb, "La confirmation ne correspond pas au nouveau mot de passe.", "err");
          return;
        }

        // Vérifier si on est en mode serveur
        if (document.body.dataset.serverSide === '1') {
          try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            
            // Déterminer la route selon la page (manager ou client)
            const isClientPage = document.body.dataset.page === 'compte-client';
            const apiUrl = isClientPage ? '/client/mon-compte/password' : '/manager/compte/password';
            
            const response = await fetch(apiUrl, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
              },
              body: JSON.stringify({
                current_password: cur,
                new_password: neu,
                confirm_password: conf
              })
            });

            const result = await response.json();

            if (result.success) {
              setFeedback(fb, result.message, "ok");
              pwdForm.reset();
            } else {
              setFeedback(fb, result.error || "Erreur lors de la mise à jour du mot de passe", "err");
            }
          } catch (error) {
            setFeedback(fb, "Erreur: " + error.message, "err");
          }
        } else {
          // Mode local (localStorage)
          const users = loadUsers();
          const emailKey = normalizeEmail(auth.email);
          const idx = users.findIndex((u) => normalizeEmail(u.email) === emailKey);
          if (idx < 0) {
            setFeedback(
              fb,
              "Compte introuvable dans la liste locale. Utilisez un compte cree depuis le portail manager.",
              "err"
            );
            return;
          }
          if (users[idx].password !== cur) {
            setFeedback(fb, "Mot de passe actuel incorrect.", "err");
            return;
          }

          users[idx].password = neu;
          saveUsers(users);
          pwdForm.reset();
          setFeedback(fb, "Mot de passe mis à jour avec succès.", "ok");
        }
      });
    }

    const prefsForm = SeneBI.qs("#prefsForm");
    if (prefsForm && !prefsForm.dataset.bound) {
      prefsForm.dataset.bound = "1";
      prefsForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const next = {
          emailAlerts: SeneBI.qs("#prefEmail").checked,
          stockAlerts: SeneBI.qs("#prefStock").checked,
          parcelReminders: SeneBI.qs("#prefParcel").checked,
          weeklyDigest: SeneBI.qs("#prefDigest").checked,
          smsAlerts: SeneBI.qs("#prefSms").checked,
        };
        savePrefs(auth.email, next);
        setFeedback(SeneBI.qs("#prefsFeedback"), "Préférences enregistrées sur cet appareil.", "ok");
      });
    }

    const exportBtn = SeneBI.qs("#exportDataBtn");
    const importInput = SeneBI.qs("#importDataFile");
    const backupFb = SeneBI.qs("#backupFeedback");

    if (exportBtn && !exportBtn.dataset.bound) {
      exportBtn.dataset.bound = "1";
      exportBtn.addEventListener("click", () => {
        const state = SeneBI.loadState();
        const payload = {
          app: "SeneBI",
          version: 1,
          exportedAt: new Date().toISOString(),
          state,
        };
        const dateStamp = new Date().toISOString().slice(0, 10);
        downloadJson(`senebi-sauvegarde-${dateStamp}.json`, payload);
        setFeedback(backupFb, "Sauvegarde exportee avec succes.", "ok");
      });
    }

    if (importInput && !importInput.dataset.bound) {
      importInput.dataset.bound = "1";
      importInput.addEventListener("change", async (event) => {
        const file = event.target?.files?.[0];
        if (!file) return;
        try {
          const text = await file.text();
          const parsed = JSON.parse(text);
          const incomingState = parsed?.state;
          if (!incomingState || !incomingState.bySeason || !incomingState.season) {
            setFeedback(backupFb, "Fichier invalide: sauvegarde SeneBI non reconnue.", "err");
            importInput.value = "";
            return;
          }
          localStorage.setItem(STATE_KEY, JSON.stringify(incomingState));
          setFeedback(backupFb, "Sauvegarde importee. Rechargement en cours...", "ok");
          setTimeout(() => window.location.reload(), 500);
        } catch {
          setFeedback(backupFb, "Import impossible: fichier JSON incorrect.", "err");
        } finally {
          importInput.value = "";
        }
      });
    }

    window.addEventListener("senebi:seasonChanged", () => {
      SeneBI.renderTopbar(state);
    });
  });
})();
