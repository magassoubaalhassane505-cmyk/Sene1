<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable([
    'name', 'email', 'password', 'role', 'saison', 'is_active',
    'phone', 'location', 'company', 'rejection_reason', 'status',
    'approved_at', 'approved_by', 'rejected_at', 'last_login_at',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected static function booted(): void
    {
        static::saving(function (User $user) {
            if (is_string($user->email)) {
                $user->email = Str::lower(trim($user->email));
            }
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'last_login_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function parcelles(): HasMany
    {
        return $this->hasMany(Parcelle::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function visites(): HasMany
    {
        return $this->hasMany(Visite::class)->orderByDesc('date_visite');
    }

    public function recoltes(): HasMany
    {
        return $this->hasMany(Recolte::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isManager(): bool
    {
        return in_array($this->role, ['manager', 'admin'], true);
    }
}
