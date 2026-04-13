<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Space;

class SpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $informatica  = Category::where('name', 'Informática')->first()->category_id;
        $audiovisual  = Category::where('name', 'Audiovisuales')->first()->category_id;
        $laboratorio  = Category::where('name', 'Laboratorio')->first()->category_id;
        $musica       = Category::where('name', 'Música')->first()->category_id;

        $spaces = [
            ['name' => 'Aula 101',           'category_id' => $informatica, 'capacity' => 30, 'status' => 1],
            ['name' => 'Aula 102',           'category_id' => $informatica, 'capacity' => 25, 'status' => 1],
            ['name' => 'Aula 201',           'category_id' => $informatica, 'capacity' => 30, 'status' => 2],
            ['name' => 'Lab. Informática',   'category_id' => $informatica, 'capacity' => 20, 'status' => 1],
            ['name' => 'Lab. Ciencias',      'category_id' => $laboratorio, 'capacity' => 18, 'status' => 1],
            ['name' => 'Lab. Química',       'category_id' => $laboratorio, 'capacity' => 16, 'status' => 1],
            ['name' => 'Sala de Actos',      'category_id' => $audiovisual, 'capacity' => 120,'status' => 1],
            ['name' => 'Sala Reuniones',     'category_id' => $audiovisual, 'capacity' => 12, 'status' => 1],
            ['name' => 'Aula de Música',     'category_id' => $musica,      'capacity' => 20, 'status' => 1],
        ];

        foreach ($spaces as $space) {
            Space::create($space);
        }
    }
}
