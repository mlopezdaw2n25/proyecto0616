<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Coments;


class Post extends Model
{
    //
    use HasFactory;
    
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
