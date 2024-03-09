<?php

// database/migrations/[timestamp]_create_lugares_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class CreateLugaresTable extends Migration
{
    public function up()
    {
        // Crear la tabla de lugares
        DB::statement('
            CREATE TABLE lugares (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(255),
                valor_numerico DECIMAL(10, 2),
                latitud DOUBLE NULL,
                longitud DOUBLE NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ');
    }

    public function down()
    {
        // Eliminar la tabla de lugares
        DB::statement('DROP TABLE IF EXISTS lugares');
    }
}


