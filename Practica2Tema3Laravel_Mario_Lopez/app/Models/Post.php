<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Coments;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;


class Post extends Model
{
    //
    use HasFactory;

    /**
     * Accessor per al camp `url`.
     * - Si és un path de storage (no comença per http), el converteix en URL pública.
     * - Si és una URL externa (comença per http), la retorna tal qual (retrocompatibilitat).
     * - Si és null/buit, retorna null.
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => match (true) {
                empty($value)                    => null,
                str_starts_with($value, 'http')  => $value,
                default                          => Storage::url($value),
            },
        );
    }
    public function user() : BelongsTo 
    {
        return $this->belongsTo(User::class);
    }

    public function category() : BelongsTo 
    {
        return $this->belongsTo(Category::class);
    }

    public function tags() : BelongsToMany 
    {
        return $this->belongsToMany(Tag::class);
    }

    public function likes() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes', 'post_id', 'user_id');
    }

    public function coments() : HasMany
    {
        return $this->hasMany(Coments::class);
    }
}
