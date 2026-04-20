<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tipus_User extends Model
{
    use HasFactory;
    protected $table = 'tipus_users'; 
    protected $fillable = ['name'];
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
