<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Role::create([
            'nameRol' => 'Administrador',
            'admin' => true,
        ]);

        \App\Models\Role::create([
            'nameRol' => 'Usuario',
            'admin' => false,
        ]);
    }
}
