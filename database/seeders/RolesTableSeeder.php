<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        // Desactivar restricciones de clave externa
        Schema::disableForeignKeyConstraints();

        // Limpia la tabla antes de insertar datos para evitar duplicados
        DB::table('roles')->truncate();

        // Inserta roles predeterminados
        DB::table('roles')->insert([
            ['nombre' => 'Cliente', 'estado' => 'activo'],
            ['nombre' => 'Chofer', 'estado' => 'activo'],
            ['nombre' => 'PersonalAdmin', 'estado' => 'activo'],
            // Puedes agregar más roles según sea necesario
        ]);

        // Reactivar restricciones de clave externa
        Schema::enableForeignKeyConstraints();
    }
}
