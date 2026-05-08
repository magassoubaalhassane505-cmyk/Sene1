<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ManagementController;

/*
|--------------------------------------------------------------------------
| Web Routes - Projet SeneBI (Version Corrigée)
|--------------------------------------------------------------------------
| Structure respectant l'ordre original de l'application
*/

// --- ACCUEIL & PORTAIL (STRUCTURE ORIGINALE) ---
// Page d'accueil principale : redirige vers la connexion client
Route::get('/', [AuthController::class, 'loginClient'])->name('welcome');
Route::get('/index', [DashboardController::class, 'index'])->name('home');
Route::get('/secure-portal', [AuthController::class, 'portal'])->name('secure.portal');

// --- AUTHENTIFICATION (PAGES DE CONNEXION) ---
Route::get('/login-manager', [AuthController::class, 'login'])->name('login.manager');
Route::post('/login-manager', [AuthController::class, 'authenticate'])->name('login.manager.post');
Route::get('/login-client', [AuthController::class, 'loginClient'])->name('login.client');
Route::post('/login-client', [AuthController::class, 'authenticateClient'])->name('login.client.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- ESPACE MANAGER (ADMINISTRATION - STRUCTURE PRINCIPALE) ---
Route::prefix('manager')->group(function () {
    
    // Dashboard Principal Manager (dashboard.html)
    Route::get('/dashboard', [DashboardController::class, 'managerDashboard'])->name('manager.dashboard');
    
    // Supervision des Agriculteurs (supervision.html)
    Route::get('/supervision', [ManagementController::class, 'supervision'])->name('manager.supervision');
    
    // Catalogue des Intrants (catalogue.html)
    Route::get('/catalogue', [ManagementController::class, 'catalogue'])->name('manager.catalogue');
    
    // Gestion des Stocks (stocks.html)
    Route::get('/stocks', [ManagementController::class, 'stocks'])->name('manager.stocks');
    
    // Contrôle des Visites (visits-control.html)
    Route::get('/visites', [ManagementController::class, 'visites'])->name('manager.visites');
    
    // Compte Manager (compte.html)
    Route::get('/compte', [ManagementController::class, 'compte'])->name('manager.compte');
});

// --- ESPACE CLIENT (AGRICULTEURS - STRUCTURE CLIENT) ---
Route::prefix('client')->group(function () {
    
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
});

// --- PAGES AUTONOMES (SANS PREFIX) ---
// Ces pages sont désormais accessibles via les routes préfixées manager/* ou client/*.
// Les définitions de routes directes sans préfixe ont été supprimées pour éviter les doublons.

// --- ERREURS & AUTRES ---
Route::get('/403', [AuthController::class, 'error403'])->name('error.403');