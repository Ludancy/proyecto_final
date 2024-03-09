<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class CreateContactoEmergenciaChoferTable extends Migration
{
    public function up()
    {
        // Crear la tabla de contacto_emergencia_chofer
        DB::statement('
            CREATE TABLE contacto_emergencia_chofer (
                id INT AUTO_INCREMENT PRIMARY KEY,
                idChofer INT,
                nombre VARCHAR(255),
                telefono VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (idChofer) REFERENCES chofers(id) ON DELETE CASCADE
            )
        ');
    }

    public function down()
    {
        // Eliminar la tabla de contacto_emergencia_chofer
        DB::statement('DROP TABLE IF EXISTS contacto_emergencia_chofer');
    }
}
