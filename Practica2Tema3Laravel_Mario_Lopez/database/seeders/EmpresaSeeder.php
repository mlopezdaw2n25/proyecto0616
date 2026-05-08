<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'druni@gmail.com'],
            [
                'name'          => 'Druni',
                'email'         => 'druni@gmail.com',
                'bio'           => null,
                'password'      => Hash::make('12345678'),
                'ruta'          => 'imatges/default.png',
                'location'      => null,
                'is_private'    => false,
                'followers'     => 0,
                'tipus_user_id' => 2,
            ]
        );
    }
}
