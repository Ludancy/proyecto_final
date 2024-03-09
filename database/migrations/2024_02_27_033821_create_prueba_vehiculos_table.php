<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class CreatePruebaVehiculosTable extends Migration
{
    public function up()
    {
        // Crear la tabla de PruebaVehiculo
        DB::statement('
            CREATE TABLE PruebaVehiculo (
                id INT AUTO_INCREMENT PRIMARY KEY,
                idVehiculo INT,
                calificacion DECIMAL(5, 2),
                fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (idVehiculo) REFERENCES vehiculos(id) ON DELETE CASCADE
            )
        ');
    }

    public function down()
    {
        // Eliminar la tabla de PruebaVehiculo
        DB::statement('DROP TABLE IF EXISTS PruebaVehiculo');
    }
}


