<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class CreateAdminsTable extends Migration
{
    public function up()
    {
        // Crear la tabla de personalAdmin
        DB::statement('
            CREATE TABLE personalAdmin (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(255) NULL,
                apellido VARCHAR(255) NULL,
                cedula VARCHAR(255) NULL,
                fechaNacimiento DATE NULL,
                idAuth INT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (idAuth) REFERENCES auths(id) ON DELETE CASCADE
            )
        ');
    }

    public function down()
    {
        // Eliminar la tabla de personalAdmin
        DB::statement('DROP TABLE IF EXISTS personalAdmin');
    }
}
