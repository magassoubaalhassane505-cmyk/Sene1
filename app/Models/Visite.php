<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'date_visite', 'parcelle_id', 'action_effectuee', 'duree', 'recommandation'])]
class Visite extends Model
{
    use HasFactory;

    protected $casts = [
        'date_visite' => 'datetime',
        'duree' => 'integer',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parcelle()
    {
        return $this->belongsTo(Parcelle::class);
    }

    // Méthodes utilitaires
    public function getDureeFormateeAttribute()
    {
        $heures = floor($this->duree / 60);
        $minutes = $this->duree % 60;
        
        if ($heures > 0) {
            return $heures . 'h' . ($minutes > 0 ? $minutes . 'min' : '');
        }
        return $minutes . 'min';
    }
}
