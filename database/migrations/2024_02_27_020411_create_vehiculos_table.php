<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class CreateVehiculosTable extends Migration
{
    public function up()
    {
        // Crear la tabla de vehiculos
        DB::statement('
            CREATE TABLE vehiculos (
                id INT AUTO_INCREMENT PRIMARY KEY,
                idChofer INT,
                marca VARCHAR(255),
                color VARCHAR(255),
                placa VARCHAR(255) UNIQUE,
                anio_fabricacion INT,
                estado_vehiculo ENUM("Pendiente", "Aprobado") DEFAULT "Pendiente",
                estado_actual ENUM("activo", "inactivo") DEFAULT "activo",

                FOREIGN KEY (idChofer) REFERENCES chofers(id) ON DELETE CASCADE
            )
        ');
    }

    public function down()
    {
        // Eliminar la tabla de vehiculos
        DB::statement('DROP TABLE IF EXISTS vehiculos');
    }
}

