<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Api\Usuarios\Models\Usuario;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Usuario::factory()->create([
            'name' => 'Usuario de Prueba',
            'email' => 'test@larapi.template.com',
        ]);

        // \App\Models\User::factory(10)->create();
    }
}
