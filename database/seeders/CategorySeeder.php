<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Informática',
            'Audiovisuales',
            'Mobiliario',
            'Laboratorio',
            'Deportes',
            'Música',
            ];

        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat,
            ]);
        }
    }
}
