<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class CreateRolesTable extends Migration
{
    public function up()
    {
        // Crear la tabla de roles
        DB::statement('
            CREATE TABLE roles (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(255),
                estado VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ');
    }

    public function down()
    {
        // Eliminar la tabla de roles
        DB::statement('DROP TABLE IF EXISTS roles');
    }
}
