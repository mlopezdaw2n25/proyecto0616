<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Coments;
use App\Models\Connection;
use App\Models\Notification;
use App\Models\UserSettings;
use App\Models\UserSkill;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    //
    use HasFactory;
    protected $guarded=[];
    public function post() : HasMany 
    {
        return $this->hasMany(Post::class);
    }

    public function Tipus_User() : BelongsTo 
    {
    return $this->belongsTo(Tipus_User::class, 'tipus_user_id');
    }

    public function likes() : BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'likes', 'user_id', 'post_id');
    }

    public function coments() : HasMany
    {
        return $this->hasMany(Coments::class);
    }

    public function cv(): HasOne
    {
        return $this->hasOne(UserCv::class);
    }

    public function settings(): HasOne
    {
        return $this->hasOne(UserSettings::class);
    }

    /**
     * Returns settings, creating defaults if they don't exist yet.
     */
    public function getOrCreateSettings(): UserSettings
    {
        return $this->settings ?? $this->settings()->create([]);
    }

    // ── Connection relationships ──────────────────────────────────────────────

    public function sentRequests(): HasMany
    {
        return $this->hasMany(Connection::class, 'sender_id');
    }

    public function receivedRequests(): HasMany
    {
        return $this->hasMany(Connection::class, 'receiver_id');
    }

    public function friends()
    {
        $sent     = $this->sentRequests()->where('status', 'accepted')->pluck('receiver_id');
        $received = $this->receivedRequests()->where('status', 'accepted')->pluck('sender_id');
        return User::whereIn('id', $sent->merge($received));
    }

    public function pendingReceivedRequests(): HasMany
    {
        return $this->receivedRequests()->where('status', 'pending');
    }

    /** All notifications received by this user, newest first. */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class)->latest();
    }

    /** Skills / aptitudes for this user. */
    public function skills(): HasMany
    {
        return $this->hasMany(UserSkill::class);
    }

    public function connectionWith(int $userId): ?Connection
    {
        return Connection::where(function ($q) use ($userId) {
            $q->where('sender_id', $this->id)->where('receiver_id', $userId);
        })->orWhere(function ($q) use ($userId) {
            $q->where('sender_id', $userId)->where('receiver_id', $this->id);
        })->first();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
