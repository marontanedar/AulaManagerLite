<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Resource::create([
            'name' => 'Portátil HP ProBook',
            'description' => 'Core i7, 16GB RAM, SSD 512GB',
            'status' => 1, // Disponible
            'category_id' => 1, // Informática
            'created_by' => 1,  // El Admin que creamos en UserSeeder
        ]);

        Resource::create([
            'name' => 'Proyector Epson EB-X05',
            'description' => '3300 lúmenes, entrada HDMI y VGA',
            'status' => 1,
            'category_id' => 2, // Audiovisuales
            'created_by' => 1,
        ]);

        Resource::create([
            'name' => 'Microscopio Binocular',
            'description' => 'Aumento 1000x con iluminación LED',
            'status' => 2, // En mantenimiento
            'category_id' => 4, // Laboratorio
            'created_by' => 1,
        ]);
    }
}
