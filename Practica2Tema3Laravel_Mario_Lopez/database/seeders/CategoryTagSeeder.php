<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryTagSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $categories = [
            'Fabricació mecànica',
            'Imatge i so',
            'Imatge personal',
            'Informàtica i comunicacions',
            'Programes de formació i inserció (PFI)',
            'Serveis socioculturals i a la comunitat',
            'Transport i manteniment de vehicles',
        ];

        foreach ($categories as $name) {
            DB::table('categories')->insertOrIgnore([
                'name'       => $name,
                'slug'       => Str::slug($name),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $tags = [
            'Mecatrònica',
            'DissenyCAD',
            'FresatCNC',
            'Tornejeria',
            'FabricacióAdditiva',
            'Impressió3D',
            'RealitatVirtual',
            'RealitatAugmentada',
            'GameDesign',
            'UnityUnrealEngine',
            'MotionGraphics',
            'Postproducció',
            'DireccióArtística',
            'FotografiaProfessional',
            'Sonologia',
            'EnginyeriaSo',
            'DJProductor',
            'EventManager',
            'WeddingPlanner',
            'PersonalShopper',
            'ImageConsultant',
            'EfectesEspecialsCinema',
            'MaquillatgeFX',
            'SpaWellness',
            'Cosmetologia',
            'Barberia',
            'DevOps',
            'LinuxServer',
            'CloudComputing',
            'Cybersecurity',
            'FrontendBackend',
            'FullStackDeveloper',
            'HelpDeskIT',
            'TècnicInformàtic',
            'ElectricistaAutomoció',
            'DiagnòsticOBD',
            'HíbridsElèctrics',
            'ConductorProfessional',
            'LogísticaTransport',
            'MecànicaPrecisió',
        ];

        foreach ($tags as $name) {
            DB::table('tags')->insertOrIgnore([
                'name'       => $name,
                'slug'       => Str::slug($name),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
