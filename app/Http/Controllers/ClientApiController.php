<?php

namespace App\Http\Controllers;

use App\Models\Parcelle;
use App\Models\Stock;
use App\Models\StockMouvement;
use App\Models\IntrantConsomme;
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
                'description' => 'nullable|string',
                'date' => 'required|date',
            ]);

            $stock = Stock::findOrFail($data['stock_id']);
            $this->authorizeStock($stock);

            $quantiteAvant = $stock->quantite_actuelle;
            $stock->quantite_actuelle += $data['quantite'];
            $stock->save();

            // Enregistrer le mouvement d'entrée
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
}