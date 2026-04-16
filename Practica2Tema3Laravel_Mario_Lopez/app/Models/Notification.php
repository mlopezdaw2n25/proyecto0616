<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $guarded = [];

    // ── Relationships ─────────────────────────────────────────────────────────

    /** The user who receives this notification. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** The user who triggered the action. */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /** The post associated with this notification (may be null). */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
