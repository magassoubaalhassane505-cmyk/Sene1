<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ClientApiController;
use App\Http\Controllers\PublicController;

// --- PARTIE PUBLIQUE (Accessible sans connexion) ---
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/solutions', [PublicController::class, 'solutions'])->name('public.solutions');
Route::redirect('/fonctionnalites', '/solutions', 301);
Route::redirect('/services', '/solutions', 301);
Route::redirect('/business-intelligence', '/solutions', 301);
Route::get('/a-propos', [PublicController::class, 'about'])->name('public.about');
Route::get('/contact', [PublicController::class, 'contact'])->name('public.contact');
Route::get('/faq', [PublicController::class, 'faq'])->name('public.faq');
Route::get('/connexion', [PublicController::class, 'login'])->name('public.login');
Route::get('/inscription', [PublicController::class, 'register'])->name('public.register');

// --- ACCUEIL & PORTAIL (STRUCTURE ORIGINALE) ---
Route::get('/index', [DashboardController::class, 'index'])->name('home.old');
Route::get('/secure-portal', [AuthController::class, 'portal'])->middleware('public.portal')->name('secure.portal');

// --- AUTHENTIFICATION (connexion unique) ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Rétrocompatibilité : anciennes URLs
Route::get('/login-manager', [AuthController::class, 'loginManager'])->name('login.manager');
Route::post('/login-manager', [AuthController::class, 'login'])->name('login.manager.post');
Route::get('/login-client', [AuthController::class, 'loginClient'])->name('login.client');
Route::post('/login-client', [AuthController::class, 'login'])->name('login.client.post');
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/password/forgot', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('/password/forgot', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword'])->name('password.update');

// --- ESPACE MANAGER (ADMINISTRATION - STRUCTURE PRINCIPALE) ---
Route::prefix('manager')->middleware('auth')->group(function () {
    
    // Dashboard Principal Manager (dashboard.html)
    Route::get('/dashboard', [DashboardController::class, 'managerDashboard'])->name('manager.dashboard');
    
    // Supervision des Agriculteurs (supervision.html)
    Route::get('/supervision', [ManagementController::class, 'supervision'])->name('manager.supervision');
    
    // Catalogue des Intrants (catalogue.html)
    Route::get('/catalogue', [ManagementController::class, 'catalogue'])->name('manager.catalogue');
    Route::post('/catalogue/update', [ManagementController::class, 'updateCatalogue'])->name('manager.catalogue.update');
    
    // Gestion des Stocks (stocks.html)
    Route::get('/stocks', [ManagementController::class, 'stocks'])->name('manager.stocks');
    
    // Contrôle des Visites (visits-control.html)
    Route::get('/visites', [ManagementController::class, 'visites'])->name('manager.visites');
    Route::post('/visites', [ManagementController::class, 'storeVisite'])->name('manager.visites.store')->middleware('auth');
    
    // Compte Manager (compte.html)
    Route::get('/compte', [ManagementController::class, 'compte'])->name('manager.compte');
    Route::post('/compte/password', [ManagementController::class, 'updatePassword'])->name('manager.compte.password');

    // Approbation des clients inscrits
    Route::post('/clients/approve/{user}', [ManagementController::class, 'approveClient'])->name('manager.clients.approve');

    // Rejet des clients inscrits
    Route::post('/clients/reject/{user}', [ManagementController::class, 'rejectClient'])->name('manager.clients.reject');

    // Suppression définitive des utilisateurs
    Route::delete('/users/{user}', [ManagementController::class, 'destroyUser'])->name('manager.users.destroy')->middleware('auth');
});

// --- ESPACE CLIENT (AGRICULTEURS - STRUCTURE CLIENT) ---
Route::prefix('client')->middleware('auth')->group(function () {
    
    // Dashboard Client (client-dashboard.html)
    Route::get('/dashboard', [ClientController::class, 'clientDashboard'])->name('client.dashboard');
    
    // Calculateur de Rentabilité (rentabilite.html)
    Route::get('/rentabilite', [ClientController::class, 'rentabilite'])->name('client.rentabilite');
    
    // Gestion des Parcelles (parcelles.html)
    Route::get('/parcelles', [ClientController::class, 'parcelles'])->name('client.parcelles');

    // Gestion des Stocks (stocks.html)
    Route::get('/stocks', [ClientController::class, 'stocks'])->name('client.stocks');
    
    // Profil & Compte Client (compte.html)
    Route::get('/mon-compte', [ClientController::class, 'compte'])->name('client.compte');
    Route::post('/mon-compte/password', [ClientController::class, 'updatePassword'])->name('client.compte.password');

    Route::prefix('api')->group(function () {
        Route::get('/parcelles', [ClientApiController::class, 'parcellesIndex']);
        Route::post('/parcelles', [ClientApiController::class, 'parcellesStore']);
        Route::put('/parcelles/{parcelle}', [ClientApiController::class, 'parcellesUpdate']);
        Route::delete('/parcelles/{parcelle}', [ClientApiController::class, 'parcellesDestroy']);
        Route::get('/stocks', [ClientApiController::class, 'stocksIndex']);
        Route::get('/stocks/mouvements', [ClientApiController::class, 'stocksMouvementsIndex']);
        Route::put('/stocks/{stock}', [ClientApiController::class, 'stocksUpdate']);
        Route::post('/consommation', [ClientApiController::class, 'storeConsommation']);
        Route::post('/stocks/entree', [ClientApiController::class, 'addStockEntree']);
    })->middleware('auth');
});

// --- PAGES AUTONOMES (SANS PREFIX) ---
// Ces pages sont désormais accessibles via les routes préfixées manager/* ou client/*.
// Les définitions de routes directes sans préfixe ont été supprimées pour éviter les doublons.

// --- ERREURS & AUTRES ---
Route::get('/403', [AuthController::class, 'error403'])->name('error.403');