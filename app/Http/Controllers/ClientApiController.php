<?php

namespace App\Http\Controllers;

use App\Models\Parcelle;
use App\Models\Stock;
use App\Models\StockMouvement;
use App\Models\IntrantConsomme;
use App\Models\Intrant;
use App\Models\Recolte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientApiController extends Controller
{
    public function parcellesIndex()
    {
        $parcelles = Auth::user()->parcelles()->orderBy('nom')->get();

        return response()->json(['data' => $parcelles]);
    }

    public function parcellesStore(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'surface' => 'required|numeric|min:0.01',
            'culture' => 'required|string|max:255',
            'statut' => 'nullable|string|max:100',
        ]);

        $parcelle = Auth::user()->parcelles()->create([
            'nom' => $data['nom'],
            'region' => $data['region'],
            'surface' => $data['surface'],
            'culture' => $data['culture'],
        ]);

        \App\Models\Notification::notifyUser(
            Auth::id(),
            'parcelle',
            'Nouvelle parcelle ajoutée',
            "La parcelle {$data['nom']} ({$data['culture']}) a été ajoutée à vos parcelles.",
            'success',
            url('/client/parcelles')
        );

        \App\Models\Notification::notifyManager(
            'parcelle',
            'Nouvelle parcelle créée',
            "L'agriculteur " . Auth::user()->name . " a ajouté la parcelle {$data['nom']} ({$data['culture']}) - {$data['region']}.",
            'info',
            url('/manager/supervision')
        );

        return response()->json(['data' => $parcelle], 201);
    }

    public function parcellesUpdate(Request $request, Parcelle $parcelle)
    {
        $this->authorizeParcelle($parcelle);

        $data = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'region' => 'sometimes|string|max:255',
            'surface' => 'sometimes|numeric|min:0.01',
            'culture' => 'sometimes|string|max:255',
        ]);

        $parcelle->update($data);

        return response()->json(['data' => $parcelle->fresh()]);
    }

    public function parcellesDestroy(Parcelle $parcelle)
    {
        $this->authorizeParcelle($parcelle);
        $parcelle->delete();

        return response()->json(['ok' => true]);
    }

    public function stocksIndex()
    {
        $user = Auth::user();
        $this->ensureDefaultStocks($user);

        $stocks = Stock::where('user_id', $user->id)->orderBy('nom')->get();

        return response()->json(['data' => $stocks]);
    }

    public function stocksMouvementsIndex()
    {
        $user = Auth::user();
        $mouvements = StockMouvement::where('user_id', $user->id)
            ->with('stock')
            ->orderBy('date_mouvement', 'desc')
            ->limit(100)
            ->get();

        return response()->json(['data' => $mouvements]);
    }

    public function stocksUpdate(Request $request, Stock $stock)
    {
        $this->authorizeStock($stock);

        $data = $request->validate([
            'quantite_actuelle' => 'required|numeric|min:0',
            'seuil_critique' => 'nullable|numeric|min:0',
            'cout_unitaire' => 'nullable|numeric|min:0',
        ]);

        $quantiteAvant = $stock->quantite_actuelle;
        $stock->update($data);

        // Enregistrer l'ajustement
        StockMouvement::create([
            'user_id' => Auth::id(),
            'stock_id' => $stock->id,
            'type' => 'ajustement',
            'description' => 'Ajustement manuel du stock',
            'quantite' => abs($data['quantite_actuelle'] - $quantiteAvant),
            'quantite_avant' => $quantiteAvant,
            'quantite_apres' => $stock->quantite_actuelle,
            'date_mouvement' => now(),
        ]);

        return response()->json(['data' => $stock->fresh()]);
    }

    protected function authorizeParcelle(Parcelle $parcelle): void
    {
        if ($parcelle->user_id !== Auth::id()) {
            abort(403);
        }
    }

    protected function authorizeStock(Stock $stock): void
    {
        if ($stock->user_id !== Auth::id()) {
            abort(403);
        }
    }

    protected function ensureDefaultStocks($user): void
    {
        if (Stock::where('user_id', $user->id)->exists()) {
            return;
        }

        $defaults = [
            ['nom' => 'Urée', 'type' => 'Engrais', 'quantite_actuelle' => 520, 'seuil_critique' => 500, 'cout_unitaire' => 15000],
            ['nom' => 'NPK', 'type' => 'Engrais', 'quantite_actuelle' => 900, 'seuil_critique' => 450, 'cout_unitaire' => 18000],
            ['nom' => 'Semences', 'type' => 'Semence', 'quantite_actuelle' => 240, 'seuil_critique' => 100, 'cout_unitaire' => 800],
        ];

        foreach ($defaults as $row) {
            Stock::create([...$row, 'user_id' => $user->id]);
        }
    }

    public function storeConsommation(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validate([
                'region' => 'required|string',
                'parcelle' => 'required|string',
                'date' => 'required|date',
                'intrant' => 'required|string',
                'quantite' => 'required|numeric|min:0.01',
            ]);

            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Utilisateur non connecté'], 401);
            }

            // Récupérer les stocks existants
            $stocks = Stock::where('user_id', $user->id)->get();

            // Créer des stocks par défaut si l'utilisateur n'en a pas ou ajouter les manquants
            $defaults = [
                ['nom' => 'Urée', 'type' => 'Engrais', 'quantite_actuelle' => 520, 'seuil_critique' => 500, 'cout_unitaire' => 15000],
                ['nom' => 'NPK', 'type' => 'Engrais', 'quantite_actuelle' => 900, 'seuil_critique' => 450, 'cout_unitaire' => 18000],
                ['nom' => 'Semence Maïs', 'type' => 'Semence', 'quantite_actuelle' => 240, 'seuil_critique' => 100, 'cout_unitaire' => 800],
                ['nom' => 'Semence Coton', 'type' => 'Semence', 'quantite_actuelle' => 1250, 'seuil_critique' => 500, 'cout_unitaire' => 1200],
                ['nom' => 'Semence Riz', 'type' => 'Semence', 'quantite_actuelle' => 600, 'seuil_critique' => 300, 'cout_unitaire' => 1000],
            ];
            
            foreach ($defaults as $default) {
                $existingStock = $stocks->firstWhere('nom', $default['nom']);
                if (!$existingStock) {
                    Stock::create([...$default, 'user_id' => $user->id]);
                }
            }
            
            // Recharger les stocks après création
            $stocks = Stock::where('user_id', $user->id)->get();

            // Trouver le stock correspondant à l'intrant
            $stock = Stock::where('user_id', $user->id)
                ->where('nom', $data['intrant'])
                ->first();

            if (!$stock) {
                return response()->json(['error' => 'Intrant non trouvé'], 404);
            }

            // Vérifier si la quantité est suffisante
            if ($stock->quantite_actuelle < $data['quantite']) {
                return response()->json(['error' => 'Quantité insuffisante en stock'], 400);
            }

            $quantiteAvant = $stock->quantite_actuelle;

            // Déduire la quantité du stock
            $stock->quantite_actuelle -= $data['quantite'];
            $stock->save();

            // Enregistrer le mouvement de stock
            StockMouvement::create([
                'user_id' => Auth::id(),
                'stock_id' => $stock->id,
                'type' => 'utilisation',
                'description' => "Utilisation pour parcelle {$data['parcelle']} ({$data['region']})",
                'quantite' => $data['quantite'],
                'quantite_avant' => $quantiteAvant,
                'quantite_apres' => $stock->quantite_actuelle,
                'date_mouvement' => $data['date'],
            ]);

            // Vérifier si le stock est critique
            $estCritique = $stock->quantite_actuelle <= $stock->seuil_critique;

            if ($estCritique) {
                \App\Models\Notification::notifyUser(
                    $user->id,
                    'stock',
                    'Stock critique : ' . $stock->nom,
                    "Votre stock de {$stock->nom} est descendu à {$stock->quantite_actuelle} kg (seuil : {$stock->seuil_critique} kg).",
                    'danger',
                    url('/client/stocks')
                );

                \App\Models\Notification::notifyManager(
                    'stock',
                    'Stock critique détecté',
                    "L'agriculteur " . $user->name . " a un stock critique de {$stock->nom} ({$stock->quantite_actuelle} kg restant).",
                    'danger',
                    url('/manager/stocks')
                );
            } elseif ($data['quantite'] >= 500) {
                \App\Models\Notification::notifyUser(
                    $user->id,
                    'consommation',
                    'Consommation importante enregistree',
                    "Vous avez utilise {$data['quantite']} kg de {$stock->nom} pour la parcelle {$data['parcelle']}.",
                    'info',
                    url('/client/stocks')
                );

                \App\Models\Notification::notifyManager(
                    'consommation',
                    'Consommation importante',
                    "L'agriculteur " . $user->name . " a consommé {$data['quantite']} kg de {$stock->nom}.",
                    'info',
                    url('/manager/stocks')
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Consommation enregistrée avec succès',
                'stock' => [
                    'id' => $stock->id,
                    'nom' => $stock->nom,
                    'quantite_actuelle' => $stock->quantite_actuelle,
                    'seuil_critique' => $stock->seuil_critique,
                    'est_critique' => $estCritique,
                ],
            ]);
        });
    }

    public function addStockEntree(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validate([
                'stock_id' => 'required|exists:stocks,id',
                'quantite' => 'required|numeric|min:0.01',
                'cout_unitaire' => 'nullable|numeric|min:0',
                'cout_total' => 'nullable|numeric|min:0',
                'description' => 'nullable|string',
                'date' => 'required|date',
            ]);

            $stock = Stock::findOrFail($data['stock_id']);
            $this->authorizeStock($stock);

            $quantiteAvant = $stock->quantite_actuelle;
            $stock->quantite_actuelle += $data['quantite'];

            if (!empty($data['cout_unitaire']) || !empty($data['cout_total'])) {
                $coutEntree = !empty($data['cout_total']) ? $data['cout_total'] / $data['quantite'] : $data['cout_unitaire'];
                $valeurAvant = $quantiteAvant * $stock->cout_unitaire;
                $valeurAjoutee = $coutEntree * $data['quantite'];
                $stock->cout_unitaire = ($valeurAvant + $valeurAjoutee) / $stock->quantite_actuelle;
            }

            $stock->save();

            StockMouvement::create([
                'user_id' => Auth::id(),
                'stock_id' => $stock->id,
                'type' => 'entree',
                'description' => $data['description'] ?? 'Entrée de stock',
                'quantite' => $data['quantite'],
                'quantite_avant' => $quantiteAvant,
                'quantite_apres' => $stock->quantite_actuelle,
                'date_mouvement' => $data['date'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Entrée de stock enregistrée',
                'stock' => $stock->fresh(),
            ]);
        });
    }

    public function storeHarvest(Request $request)
    {
        $data = $request->validate([
            'parcelle_id' => 'required|exists:parcelles,id',
            'date' => 'required|date',
            'quantite' => 'required|numeric|min:0.01',
            'culture' => 'nullable|string|max:255',
            'prix_unitaire' => 'nullable|numeric|min:0',
            'couts_totaux' => 'nullable|numeric|min:0',
        ]);

        $user = Auth::user();
        $parcelle = Parcelle::where('user_id', $user->id)->findOrFail($data['parcelle_id']);
        $culture = $data['culture'] ?: $parcelle->culture;
        $prixUnitaire = $data['prix_unitaire'] ?? $this->priceForCulture($culture);
        $coutsTotaux = $data['couts_totaux'] ?? 0;
        $revenuTotal = $data['quantite'] * $prixUnitaire;
        $beneficeNet = $revenuTotal - $coutsTotaux;

        $recolte = Recolte::create([
            'user_id' => $user->id,
            'parcelle_id' => $parcelle->id,
            'date_recolte' => $data['date'],
            'quantite' => $data['quantite'],
            'culture' => $culture,
            'prix_unitaire' => $prixUnitaire,
            'couts_totaux' => $coutsTotaux,
            'revenu_total' => $revenuTotal,
            'benefice_net' => $beneficeNet,
            'saison' => now()->year,
        ]);

        if ($beneficeNet < 0) {
            \App\Models\Notification::notifyManager(
                'rentabilite',
                'Faible rentabilité détectée',
                "L'agriculteur " . $user->name . " a une rentabilité négative sur la culture {$culture} : " . number_format($beneficeNet, 0, ',', ' ') . " FCFA.",
                'warning',
                url('/manager/supervision')
            );
        }

        return response()->json(['data' => $recolte->fresh(['parcelle'])], 201);
    }

    public function storeRentabilite(Request $request)
    {
        $data = $request->validate([
            'parcelle_id' => 'nullable|exists:parcelles,id',
            'parcelle_nom' => 'nullable|string|max:255',
            'culture' => 'required|string|max:255',
            'surface' => 'nullable|numeric|min:0',
            'quantite' => 'required|numeric|min:0',
            'prix_unitaire' => 'required|numeric|min:0',
            'couts_totaux' => 'nullable|numeric|min:0',
        ]);

        $user = Auth::user();
        $parcelle = null;

        if (! empty($data['parcelle_id'])) {
            $parcelle = Parcelle::where('user_id', $user->id)->findOrFail($data['parcelle_id']);
        } elseif (! empty($data['parcelle_nom'])) {
            $parcelle = Parcelle::where('user_id', $user->id)
                ->whereRaw('LOWER(nom) = ?', [mb_strtolower($data['parcelle_nom'])])
                ->first();
        }

        if (! $parcelle) {
            $parcelle = Parcelle::create([
                'user_id' => $user->id,
                'nom' => $data['parcelle_nom'] ?: $data['culture'],
                'region' => $user->location ?? 'Non spécifié',
                'surface' => $data['surface'] ?? 0,
                'culture' => $data['culture'],
                'statut' => 'En culture',
            ]);
        }

        $coutsTotaux = $data['couts_totaux'] ?? 0;
        $revenuTotal = $data['quantite'] * $data['prix_unitaire'];
        $beneficeNet = $revenuTotal - $coutsTotaux;

        $recolte = Recolte::create([
            'user_id' => $user->id,
            'parcelle_id' => $parcelle->id,
            'date_recolte' => now()->toDateString(),
            'quantite' => $data['quantite'],
            'culture' => $data['culture'],
            'prix_unitaire' => $data['prix_unitaire'],
            'couts_totaux' => $coutsTotaux,
            'revenu_total' => $revenuTotal,
            'benefice_net' => $beneficeNet,
            'saison' => now()->year,
        ]);

        return response()->json(['data' => $recolte->fresh(['parcelle'])], 201);
    }

    public function notificationsIndex()
    {
        $notifications = \App\Models\Notification::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return response()->json(['data' => $notifications]);
    }

    public function notificationsReadAll()
    {
        \App\Models\Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }

    public function notificationsDestroy(\App\Models\Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return response()->json(['ok' => true]);
    }

    protected function priceForCulture(string $culture): float
    {
        $key = mb_strtolower($culture);
        $defaults = [
            'riz' => 1500,
            'mais' => 1200,
            'maïs' => 1200,
            'coton' => 2000,
        ];

        $intrant = Intrant::whereRaw('LOWER(nom) LIKE ?', ['%' . $key . '%'])->orderBy('prix')->first();
        if ($intrant) {
            return (float) $intrant->prix;
        }

        return $defaults[$key] ?? 1500;
    }

    public function storeObjectifs(Request $request)
    {
        $data = $request->validate([
            'objectif_production' => 'nullable|numeric|min:0',
            'objectif_ca' => 'nullable|numeric|min:0',
            'objectif_surface' => 'nullable|numeric|min:0',
        ]);

        $user = Auth::user();

        $objectif = \App\Models\Objectif::updateOrCreate(
            ['user_id' => $user->id, 'saison' => $user->saison ?? '2026'],
            [
                'objectif_production' => $data['objectif_production'] ?? 0,
                'objectif_ca' => $data['objectif_ca'] ?? 0,
                'objectif_surface' => $data['objectif_surface'] ?? 0,
            ]
        );

        return response()->json(['data' => $objectif, 'success' => true]);
    }
    
    public function storePdfExport(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string|max:50',
        ]);
        
        $user = Auth::user();
        
        $pdfService = new \App\Services\PdfExportService($user);
        $html = $pdfService->generateRentabiliteReport();
        
        $pdfExport = \App\Models\PdfExport::create([
            'user_id' => $user->id,
            'type' => $data['type'],
            'file_path' => "exports/rentabilite-{$user->id}-" . now()->format('YmdHis') . ".pdf",
        ]);
        
        return response()->json([
            'data' => $pdfExport, 
            'success' => true,
            'html_content' => $html
        ]);
    }
}