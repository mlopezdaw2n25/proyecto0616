<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Corregir nom de Rellotjeria (id=1)
        DB::table('tipus_users')->where('id', 1)->update(['name' => 'Rellotjeria']);

        // Afegir nous tipus d'estudi (Cicles Formatius)
        $estudis = [
            'Disseny en fabricació mecànica',
            'Soldadura i caldereria',
            'Animació 3D, jocs i entorns interactius',
            'Il·luminació, captació i tractament d\'imatge',
            'Producció d\'audiovisuals i espectacles',
            'Realització de projectes audiovisuals i espectacles',
            'So per a audiovisuals i espectacles',
            'Videodiscjòquei i so',
            'Assessoria d\'imatge personal i corporativa',
            'Caracterització i maquillatge professional',
            'Estètica i bellesa',
            'Estètica integral i benestar',
            'Perruqueria i cosmètica capil·lar',
            'Administració de sistemes informàtics en xarxa',
            'Desenvolupament d\'aplicacions web',
            'Sistemes microinformàtics i xarxes',
            'Formació per a la mobilitat segura i sostenible',
            'Automoció',
            'Carrosseria',
            'Conducció de vehicles de transport per carretera',
            'Electromecànica de vehicles',
            'Curs d\'especialització de manteniment i seguretat de vehicles híbrids i elèctrics',
            'PFI',
        ];

        $now = now();
        $rows = array_map(fn($nom) => [
            'name'       => $nom,
            'created_at' => $now,
            'updated_at' => $now,
        ], $estudis);

        DB::table('tipus_users')->insert($rows);
    }

    public function down(): void
    {
        // Revertir nom de Rellotjeria
        DB::table('tipus_users')->where('id', 1)->update(['name' => 'relojeria']);

        // Eliminar els nous tipus inserits (id > 2)
        DB::table('tipus_users')->where('id', '>', 2)->delete();
    }
};
