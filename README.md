# Dashboard BI — SeneBI

Tableau de bord **Business Intelligence** pour la gestion agricole **SeneBI** (Sénégal) : supervision des agriculteurs, stocks d’intrants, parcelles, visites terrain et rentabilité.

Application **Laravel 13** (PHP 8.3+) avec authentification par rôles (**manager/admin** et **client**).

---

## Fonctionnalités

| Espace | Rôle | Pages principales |
|--------|------|-------------------|
| **Manager** | `admin` / `manager` | Dashboard, supervision clients, catalogue intrants, stocks, contrôle des visites, compte |
| **Client** | `client` (compte approuvé) | Dashboard, parcelles, stocks, calculateur de rentabilité, mon compte |
| **Public** | — | Connexion, inscription, mot de passe oublié, portail sécurisé |

Les anciennes URLs `/login-manager` et `/login-client` redirigent vers une **connexion unique** : `/login`.

---

## Prérequis

- **PHP** ≥ 8.3 (extensions : `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`)
- **Composer** 2.x
- **MySQL** 8+ (ou MariaDB) — base recommandée : `senebi`
- **Node.js** 18+ et **npm** (assets front, optionnel en dev si vous utilisez les fichiers déjà compilés dans `public/`)

Alternative locale : **WAMP**, **XAMPP**, **Laragon** ou **Docker** avec MySQL sur `127.0.0.1:3306`.

---

## Installation (après `git clone`)

```bash
cd Dashboard_BI

# 1. Dépendances PHP
composer install

# 2. Environnement
cp .env.example .env
php artisan key:generate

# 3. Base de données — créer la base puis adapter .env :
#    DB_DATABASE=senebi
#    DB_USERNAME=root
#    DB_PASSWORD=votre_mot_de_passe

# 4. Schéma + comptes de démo
php artisan migrate
php artisan db:seed

# 5. (Optionnel) Assets front
npm install
npm run build

# 6. Lancer l’application
php artisan serve
```

Ouvrir : **http://127.0.0.1:8000**

Raccourci tout-en-un (si disponible) :

```bash
composer run setup
php artisan serve
```

---

## Configuration `.env`

Extrait utile (voir `.env.example` pour la liste complète) :

```env
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=senebi
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
```

Après modification de `.env` :

```bash
php artisan config:clear
php artisan migrate
```

---

## Comptes de démonstration

Ces identifiants sont créés par `php artisan db:seed` (`DatabaseSeeder` + `DemoUsersSeeder`).

> **Pour les tests client**, utilisez les comptes **`.ml`** : ils sont `approved` et `is_active`.  
> Les comptes **`.sn`** du seeder principal peuvent rester en `pending` et **ne pourront pas se connecter** tant qu’un manager ne les a pas approuvés.

### Manager (administration)

| Champ | Valeur |
|-------|--------|
| **URL** | http://127.0.0.1:8000/login |
| **Email** | `mimi.manager@senebi.ml` |
| **Mot de passe** | `manager123` |
| **Rôle** | `admin` |
| **Après connexion** | http://127.0.0.1:8000/manager/dashboard |

Compte alternatif (même mot de passe) : `mimi.manager@senebi.sn` — accès manager OK ; préférer `.ml` pour cohérence avec les données de démo récentes.

### Client (agriculteur)

| Champ | Valeur |
|-------|--------|
| **URL** | http://127.0.0.1:8000/login |
| **Email** | `sidi@sidi-agri.ml` |
| **Mot de passe** | `client123` |
| **Rôle** | `client` |
| **Statut** | `approved` |
| **Après connexion** | http://127.0.0.1:8000/client/dashboard |

> Ne pas utiliser `sidi@sidi-agri.sn` pour un test rapide sans approbation manager : statut souvent `pending`.

### Inscription d’un nouveau client

1. http://127.0.0.1:8000/register  
2. Le compte est créé en **`pending`** / inactif.  
3. Un **manager** se connecte → **Supervision** → approuve le client.  
4. Le client peut alors se connecter sur `/login`.

---

## URLs utiles

| Page | URL |
|------|-----|
| Accueil (redirection) | `/` → `/login` |
| Connexion | `/login` |
| Inscription | `/register` |
| Mot de passe oublié | `/password/forgot` |
| Portail manager | `/secure-portal` |
| Dashboard manager | `/manager/dashboard` |
| Supervision clients | `/manager/supervision` |
| Dashboard client | `/client/dashboard` |
| Santé Laravel | `/up` |

---

## Rôles et règles de connexion

- **Manager / admin** → redirection vers `manager/dashboard`.
- **Client** → redirection vers `client/dashboard` **uniquement** si `status = approved` et `is_active = true`.
- **Client en attente** → message : *« Votre compte est en attente de validation par un manager. »*
- **Client refusé** → message d’inscription refusée.

---

## Commandes Artisan utiles

```bash
# Réinitialiser la base et recharger les démos
php artisan migrate:fresh --seed

# Normaliser les emails en minuscules (si besoin)
php artisan senebi:normalize-emails

# Réinitialiser le mot de passe d’un utilisateur (si la commande est présente)
php artisan senebi:reset-user-password {email} {nouveau_mot_de_passe}
```

---

## Dépannage rapide

| Problème | Piste de solution |
|----------|-------------------|
| `SQLSTATE[HY000] [1049] Unknown database` | Créer la base `senebi` dans MySQL/phpMyAdmin |
| `No application encryption key` | `php artisan key:generate` |
| `419 Page Expired` à la connexion | Vider les cookies, vérifier `SESSION_DRIVER=database` et tables migrées |
| Identifiants incorrects | Relancer `php artisan db:seed` ou `migrate:fresh --seed` |
| Client bloqué « en attente » | Se connecter en manager → approuver le compte dans Supervision |
| Port 8000 occupé | `php artisan serve --port=8080` |

---

## Stack technique

- **Backend** : Laravel 13, Eloquent, sessions en base
- **Frontend** : Blade, JavaScript (`public/assets/js/`), CSS (`public/assets/css/`)
- **Base** : MySQL (migrations dans `database/migrations/`)
- **Tests** : scripts PHP/Python à la racine du projet (développement local)

---

## Structure du dépôt (aperçu)

```
app/Http/Controllers/   # Auth, Manager, Client, API parcelles/stocks
resources/views/        # Pages Blade (login, dashboards, etc.)
public/assets/          # JS/CSS statiques
database/seeders/       # Comptes de démo (DemoUsersSeeder.php)
routes/web.php          # Routes manager/* et client/*
```

---

## Licence

Projet académique / challenge BI — voir le dépôt parent pour la licence du groupe.

---

**Besoin d’un compte frais ?** Après clone : `php artisan migrate:fresh --seed`, puis connectez-vous avec **`mimi.manager@senebi.ml`** / **`manager123`** ou **`sidi@sidi-agri.ml`** / **`client123`**.
