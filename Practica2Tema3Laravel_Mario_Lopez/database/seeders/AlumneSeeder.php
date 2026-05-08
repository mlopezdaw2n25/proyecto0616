<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tipus_User;
use Illuminate\Support\Facades\Hash;

class AlumneSeeder extends Seeder
{
    public function run(): void
    {
        $tipus = fn(string $name): int =>
            Tipus_User::where('name', $name)->value('id') ?? 1;

        $alumnes = [
            [
                'name'          => 'Alex Martínez',
                'email'         => 'alex@gmail.com',
                'bio'           => null,
                'password'      => Hash::make('12345678'),
                'ruta'          => 'imatges/foto1.jpg',
                'location'      => null,
                'is_private'    => false,
                'followers'     => 0,
                'tipus_user_id' => $tipus("Desenvolupament d'aplicacions web"),
            ],
            [
                'name'          => 'Jordan López',
                'email'         => 'jordan@gmail.com',
                'bio'           => null,
                'password'      => Hash::make('12345678'),
                'ruta'          => 'imatges/foto2.jpg',
                'location'      => null,
                'is_private'    => false,
                'followers'     => 0,
                'tipus_user_id' => $tipus('Animació 3D, jocs i entorns interactius'),
            ],
            [
                'name'          => 'Taylor García',
                'email'         => 'taylor@gmail.com',
                'bio'           => null,
                'password'      => Hash::make('12345678'),
                'ruta'          => 'imatges/foto3.jpg',
                'location'      => null,
                'is_private'    => false,
                'followers'     => 0,
                'tipus_user_id' => $tipus('Estètica i bellesa'),
            ],
            [
                'name'          => 'Morgan Fernández',
                'email'         => 'morgan@gmail.com',
                'bio'           => null,
                'password'      => Hash::make('12345678'),
                'ruta'          => 'imatges/foto4.jpg',
                'location'      => null,
                'is_private'    => false,
                'followers'     => 0,
                'tipus_user_id' => $tipus('Administració de sistemes informàtics en xarxa'),
            ],
            [
                'name'          => 'Casey Sánchez',
                'email'         => 'casey@gmail.com',
                'bio'           => null,
                'password'      => Hash::make('12345678'),
                'ruta'          => 'imatges/foto5.jpg',
                'location'      => null,
                'is_private'    => false,
                'followers'     => 0,
                'tipus_user_id' => $tipus('Automoció'),
            ],
            [
                'name'          => 'Riley Torres',
                'email'         => 'riley@gmail.com',
                'bio'           => null,
                'password'      => Hash::make('12345678'),
                'ruta'          => 'imatges/foto6.jpg',
                'location'      => null,
                'is_private'    => false,
                'followers'     => 0,
                'tipus_user_id' => $tipus('Disseny en fabricació mecànica'),
            ],
            [
                'name'          => 'Jamie Rodríguez',
                'email'         => 'jamie@gmail.com',
                'bio'           => null,
                'password'      => Hash::make('12345678'),
                'ruta'          => 'imatges/foto7.jpg',
                'location'      => null,
                'is_private'    => false,
                'followers'     => 0,
                'tipus_user_id' => $tipus("Perruqueria i cosmètica capil·lar"),
            ],
            [
                'name'          => 'Avery Pérez',
                'email'         => 'avery@gmail.com',
                'bio'           => null,
                'password'      => Hash::make('12345678'),
                'ruta'          => 'imatges/foto8.jpg',
                'location'      => null,
                'is_private'    => false,
                'followers'     => 0,
                'tipus_user_id' => $tipus('Sistemes microinformàtics i xarxes'),
            ],
            [
                'name'          => 'Quinn Ruiz',
                'email'         => 'quinn@gmail.com',
                'bio'           => null,
                'password'      => Hash::make('12345678'),
                'ruta'          => 'imatges/foto9.jpg',
                'location'      => null,
                'is_private'    => false,
                'followers'     => 0,
                'tipus_user_id' => $tipus('Rellotjeria'),
            ],
            [
                'name'          => 'Skyler Gómez',
                'email'         => 'skyler@gmail.com',
                'bio'           => null,
                'password'      => Hash::make('12345678'),
                'ruta'          => 'imatges/foto10.jpg',
                'location'      => null,
                'is_private'    => false,
                'followers'     => 0,
                'tipus_user_id' => $tipus("Il·luminació, captació i tractament d'imatge"),
            ],
        ];

        foreach ($alumnes as $data) {
            User::firstOrCreate(['email' => $data['email']], $data);
        }
    }
}
