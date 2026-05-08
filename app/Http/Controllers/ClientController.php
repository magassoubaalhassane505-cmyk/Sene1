<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Affiche le tableau de bord de l'agriculteur (Sidi)
    public function clientDashboard() {
        return view('client-dashboard');
    }

    // Affiche le calculateur de rentabilité
    public function rentabilite() {
        return view('rentabilite');
    }

    // Affiche la gestion des parcelles
    public function parcelles() {
        return view('parcelles');
    }

    // Affiche la gestion des stocks du client
    public function stocks() {
        return view('stocks');
    }

    // Affiche le profil et les informations du compte
    public function compte() {
        return view('compte');
    }
}