<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManagementController extends Controller
{
    // Affiche la liste des visites de terrain (Fichier: visits-control.blade.php)
    public function visites() {
        return view('visits-control');
    }

    // Affiche la gestion des stocks d'intrants (Fichier: stocks.blade.php)
    public function stocks() {
        return view('stocks');
    }

    // NOUVEAU : Affiche le catalogue des prix (Fichier: catalogue.blade.php)
    public function catalogue() {
        return view('catalogue');
    }

    // NOUVEAU : Affiche la supervision des agriculteurs (Fichier: supervision.blade.php)
    public function supervision() {
        return view('supervision');
    }
}

