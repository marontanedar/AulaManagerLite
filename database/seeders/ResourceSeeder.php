<?php

namespace Database\Seeders;

use App\Models\Resource;
use App\Models\Category;
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
        $audiovisual = Category::where('name', 'Audiovisuales')->first()->category_id;
        $informatica = Category::where('name', 'Informática')->first()->category_id;
        $laboratorio = Category::where('name', 'Laboratorio')->first()->category_id;

        $resources = [
            // Audiovisuales
            ['name' => 'Proyector portátil A',    'category_id' => $audiovisual, 'status' => 1],
            ['name' => 'Proyector portátil B',    'category_id' => $audiovisual, 'status' => 1],
            ['name' => 'Altavoces portátiles',    'category_id' => $audiovisual, 'status' => 1],
            ['name' => 'Micrófono inalámbrico',   'category_id' => $audiovisual, 'status' => 2],
            ['name' => 'Pantalla móvil',          'category_id' => $audiovisual, 'status' => 1],
            // Informática
            ['name' => 'Portátil Dell 01',        'category_id' => $informatica, 'status' => 1],
            ['name' => 'Portátil Dell 02',        'category_id' => $informatica, 'status' => 1],
            ['name' => 'Portátil Dell 03',        'category_id' => $informatica, 'status' => 3],
            ['name' => 'Impresora portátil',      'category_id' => $informatica, 'status' => 1],
            // Laboratorio
            ['name' => 'Microscopio binocular A', 'category_id' => $laboratorio, 'status' => 1],
            ['name' => 'Microscopio binocular B', 'category_id' => $laboratorio, 'status' => 1],
            ['name' => 'Kit química orgánica',    'category_id' => $laboratorio, 'status' => 1],
        ];

        foreach ($resources as $resource) {
            Resource::create($resource);
        }
    }
}
