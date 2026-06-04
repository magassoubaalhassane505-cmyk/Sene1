<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intrant extends Model
{
    protected $fillable = [
        'nom',
        'description',
        'unite',
        'prix',
        'statut',
        'type',
        'derniere_maj',
    ];
}
