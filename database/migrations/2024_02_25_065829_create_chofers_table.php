<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class CreateChofersTable extends Migration
{
    public function up()
    {
        // Crear la tabla de chofer
        DB::statement('
            CREATE TABLE chofers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(255) NULL,
                apellido VARCHAR(255) NULL,
                cedula VARCHAR(255) NULL,
                fechaNacimiento DATE NULL,
                idAuth INT,
                entidadBancaria VARCHAR(255) NULL,
                numeroCuenta VARCHAR(255) NULL,
                saldo DECIMAL(10, 2) DEFAULT 0,
                FOREIGN KEY (idAuth) REFERENCES auths(id) ON DELETE CASCADE
            )
        ');
    }

    public function down()
    {
        // Eliminar la tabla de chofers
        DB::statement('DROP TABLE IF EXISTS chofers');
    }
}


