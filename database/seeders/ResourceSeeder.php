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
        $resources = [
            // Informática (category_id: 1)
            ['name' => 'Portátil HP ProBook 450',    'description' => 'Core i7, 16GB RAM, SSD 512GB',           'status' => 1, 'category_id' => 1],
            ['name' => 'Portátil Dell Latitude 14',  'description' => 'Core i5, 8GB RAM, SSD 256GB',            'status' => 1, 'category_id' => 1],
            ['name' => 'Carrito Portátiles A',        'description' => '20 portátiles + cargadores',             'status' => 1, 'category_id' => 1],
            ['name' => 'Carrito Portátiles B',        'description' => '20 portátiles + cargadores',             'status' => 2, 'category_id' => 1],
            ['name' => 'Tablet Samsung Galaxy Tab',   'description' => 'Android 13, pantalla 10.5"',             'status' => 1, 'category_id' => 1],

            // Audiovisuales (category_id: 2)
            ['name' => 'Proyector Epson EB-X05',     'description' => '3300 lúmenes, HDMI y VGA',               'status' => 1, 'category_id' => 2],
            ['name' => 'Proyector BenQ MX550',       'description' => '3600 lúmenes, XGA',                      'status' => 1, 'category_id' => 2],
            ['name' => 'Pantalla Proyección 200cm',  'description' => 'Trípode retráctil, formato 4:3',          'status' => 1, 'category_id' => 2],
            ['name' => 'Cámara Canon EOS M50',       'description' => 'Mirrorless, vídeo 4K',                   'status' => 3, 'category_id' => 2],
            ['name' => 'Altavoz Bluetooth JBL',      'description' => 'Portátil, 20W, batería 12h',             'status' => 1, 'category_id' => 2],

            // Mobiliario / Aulas (category_id: 3)
            ['name' => 'Aula 101',                   'description' => '30 alumnos, proyector fijo, AC',         'status' => 1, 'category_id' => 3],
            ['name' => 'Aula 102',                   'description' => '30 alumnos, pizarra digital',            'status' => 1, 'category_id' => 3],
            ['name' => 'Aula 201',                   'description' => '25 alumnos, orientación norte',          'status' => 1, 'category_id' => 3],
            ['name' => 'Aula 202',                   'description' => '25 alumnos, doble pantalla',             'status' => 2, 'category_id' => 3],
            ['name' => 'Sala de Reuniones',          'description' => '12 personas, TV 65" y videoconferencia', 'status' => 1, 'category_id' => 3],
            ['name' => 'Sala de Actos',              'description' => '150 personas, escenario y sonido',       'status' => 1, 'category_id' => 3],

            // Laboratorio (category_id: 4)
            ['name' => 'Microscopio Binocular A',    'description' => 'Aumento 1000x, LED',                     'status' => 1, 'category_id' => 4],
            ['name' => 'Microscopio Binocular B',    'description' => 'Aumento 1000x, LED',                     'status' => 2, 'category_id' => 4],
            ['name' => 'Kit Química Orgánica',       'description' => 'Reactivos, tubos de ensayo, mechero',    'status' => 1, 'category_id' => 4],
            ['name' => 'Balanza de Precisión',       'description' => '0.001g resolución, RS232',               'status' => 1, 'category_id' => 4],

            // Deportes (category_id: 5)
            ['name' => 'Pabellón Polideportivo',     'description' => 'Pista completa, vestuarios',             'status' => 1, 'category_id' => 5],
            ['name' => 'Pista de Fútbol Sala',       'description' => 'Exterior, césped artificial',            'status' => 1, 'category_id' => 5],

            // Música (category_id: 6)
            ['name' => 'Aula de Música',             'description' => 'Piano, equipo de sonido, insonorizada',  'status' => 1, 'category_id' => 6],
            ['name' => 'Sala de Ensayo',             'description' => 'Batería, amplificadores, insonorizada',  'status' => 1, 'category_id' => 6],
        ];

        foreach ($resources as $r) {
            resource::create([
                'name'        => $r['name'],
                'description' => $r['description'],
                'status'      => $r['status'],
                'category_id' => $r['category_id'],
                'created_by'  => 1,
            ]);
        }
    }
}
