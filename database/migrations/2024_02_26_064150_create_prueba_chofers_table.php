<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class CreatePruebaChofersTable extends Migration
{
    public function up()
    {
        // Crear la tabla de pruebaChofer
        DB::statement('
            CREATE TABLE pruebaChofer (
                id INT AUTO_INCREMENT PRIMARY KEY,
                idChofer INT,
                calificacion INT,
                fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (idChofer) REFERENCES chofers(id) ON DELETE CASCADE
            )
        ');
    }

    public function down()
    {
        // Eliminar la tabla de pruebaChofer
        DB::statement('DROP TABLE IF EXISTS pruebaChofer');
    }
}
