<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMouvement extends Model
{
    protected $fillable = [
        'user_id',
        'stock_id',
        'type',
        'description',
        'quantite',
        'quantite_avant',
        'quantite_apres',
        'reference',
        'date_mouvement',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'quantite_avant' => 'decimal:2',
        'quantite_apres' => 'decimal:2',
        'date_mouvement' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'entree' => 'Entrée de stock',
            'utilisation' => 'Utilisation',
            'ajustement' => 'Ajustement',
            default => 'Inconnu',
        };
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match($this->type) {
            'entree' => 'success',
            'utilisation' => 'danger',
            'ajustement' => 'warning',
            default => '',
        };
    }
}