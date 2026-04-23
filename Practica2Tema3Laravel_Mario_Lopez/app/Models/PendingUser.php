<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PendingUser extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'ruta',
        'location',
        'tipus_user_id',
        'tipus_type',
        'token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Indica si el token de verificació ha caducat.
     */
    public function isExpired(): bool
    {
        return Carbon::now()->isAfter($this->expires_at);
    }
}
