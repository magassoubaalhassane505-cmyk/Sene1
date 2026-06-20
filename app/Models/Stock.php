<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'nom', 'type', 'quantite_actuelle', 'seuil_critique', 'cout_unitaire', 'stock_minimum'])]
class Stock extends Model
{
    use HasFactory;

    protected $casts = [
        'quantite_actuelle' => 'decimal:2',
        'seuil_critique' => 'decimal:2',
        'cout_unitaire' => 'decimal:2',
        'stock_minimum' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relations
    public function intrantsConsommes()
    {
        return $this->hasMany(IntrantConsomme::class);
    }

    public function mouvements()
    {
        return $this->hasMany(StockMouvement::class);
    }

    // Méthodes utilitaires
    public function estCritique()
    {
        return $this->quantite_actuelle <= $this->seuil_critique;
    }

    public function getPourcentageRemplissage()
    {
        $capaciteMax = 10000;
        return ($this->quantite_actuelle / $capaciteMax) * 100;
    }
}