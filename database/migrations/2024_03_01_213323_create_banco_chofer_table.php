<?php

// database/migrations/[timestamp]_create_banco_chofer_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class CreateBancoChoferTable extends Migration
{
    public function up()
    {
        // Crear la tabla banco_chofer
        DB::statement('
            CREATE TABLE banco_chofer (
                id INT PRIMARY KEY AUTO_INCREMENT,
                idChofer INT,
                idBanco INT,
                nroCuenta VARCHAR(255),
                estado VARCHAR(255) NULL,

                FOREIGN KEY (idChofer) REFERENCES chofers(id) ON DELETE CASCADE,
                FOREIGN KEY (idBanco) REFERENCES bancos(id) ON DELETE CASCADE
            )
        ');
    }

    public function down()
    {
        // Eliminar la tabla banco_chofer
        DB::statement('DROP TABLE IF EXISTS banco_chofer');
    }
}
