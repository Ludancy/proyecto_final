<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class CreateClientesTable extends Migration
{
    public function up()
    {
        // Crear la tabla de cliente
        DB::statement('
            CREATE TABLE cliente (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(255) NOT NULL,
                apellido VARCHAR(255) NOT NULL,
                cedula VARCHAR(255) NOT NULL,
                fechaNacimiento DATE NOT NULL,
                idAuth INT,
                saldo DECIMAL(10, 2) DEFAULT 0.00,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (idAuth) REFERENCES auths(id) ON DELETE CASCADE
            )
        ');
    }

    public function down()
    {
        // Eliminar la tabla de cliente
        DB::statement('DROP TABLE IF EXISTS cliente');
    }
}

