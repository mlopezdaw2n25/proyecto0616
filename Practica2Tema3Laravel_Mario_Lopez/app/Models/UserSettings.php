<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSettings extends Model
{
    use HasFactory;

    protected $table = 'user_settings';

    protected $fillable = [
        'user_id',
        'dark_mode',
        'language',
        'font_size',
        'colorblind_mode',
        'show_friends',
        'show_likes',
        'show_comments',
        'notifications_enabled',
    ];

    protected $casts = [
        'dark_mode'             => 'boolean',
        'colorblind_mode'       => 'boolean',
        'show_friends'          => 'boolean',
        'show_likes'            => 'boolean',
        'show_comments'         => 'boolean',
        'notifications_enabled' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
