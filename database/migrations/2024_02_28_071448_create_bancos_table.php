<?php

// database/migrations/2024_02_28_create_bancos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class CreateBancosTable extends Migration
{
    public function up()
    {
        // Crear la tabla de Bancos
        DB::statement('
            CREATE TABLE bancos (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(255),
                codigo VARCHAR(255)
            )
        ');
    }

    public function down()
    {
        // Eliminar la tabla de Bancos
        DB::statement('DROP TABLE IF EXISTS bancos');
    }
}

