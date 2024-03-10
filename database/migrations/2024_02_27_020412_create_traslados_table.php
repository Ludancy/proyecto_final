<?php

// database/migrations/2024_02_26_create_traslados_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class CreateTrasladosTable extends Migration
{
    public function up()
    {
        // Crear la tabla traslados
        DB::statement('
            CREATE TABLE traslados (
                id INT PRIMARY KEY AUTO_INCREMENT,
                idChofer INT,
                idCliente INT,
                origen INT,
                destino INT,
                costo DECIMAL(10, 2),
                estado ENUM("pendiente", "realizado", "cancelado") DEFAULT "pendiente",
                idVehiculo INT NULL,

                FOREIGN KEY (idChofer) REFERENCES chofers(id) ON DELETE CASCADE,
                FOREIGN KEY (idCliente) REFERENCES cliente(id) ON DELETE CASCADE,
                FOREIGN KEY (origen) REFERENCES lugares(id) ON DELETE CASCADE,
                FOREIGN KEY (destino) REFERENCES lugares(id) ON DELETE CASCADE,
                FOREIGN KEY (idVehiculo) REFERENCES vehiculos(id) ON DELETE SET NULL
            )
        ');
    }
    

    public function down()
    {
        // Eliminar la tabla traslados
        DB::statement('DROP TABLE IF EXISTS traslados');
    }
}

