<?php

// database/migrations/2024_02_25_create_auths_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class CreateAuthsTable extends Migration
{
    public function up()
    {
        // Crear la tabla de auths
        DB::statement('
            CREATE TABLE auths (
                id INT AUTO_INCREMENT PRIMARY KEY,
                idRol INT,
                correo VARCHAR(255) UNIQUE,
                password VARCHAR(255),
                fechaCreacion TIMESTAMP NULL,
                estado VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (idRol) REFERENCES roles(id)
            )
        ');
    }

    public function down()
    {
        // Eliminar la tabla de auths
        DB::statement('DROP TABLE IF EXISTS auths');
    }
}

