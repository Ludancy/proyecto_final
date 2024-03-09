<?php

// database/migrations/2024_02_28_create_saldo_clientes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class CreateSaldoClientesTable extends Migration
{
    public function up()
    {
        // Crear la tabla de SaldoClientes
        DB::statement('
            CREATE TABLE saldo_clientes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                idCliente INT,
                idBanco INT,
                fecha_recarga DATE,
                referencia VARCHAR(255),
                monto DECIMAL(10, 2),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (idCliente) REFERENCES cliente(id) ON DELETE CASCADE,
                FOREIGN KEY (idBanco) REFERENCES bancos(id) ON DELETE CASCADE
            )
        ');
    }

    public function down()
    {
        // Eliminar la tabla de SaldoClientes
        DB::statement('DROP TABLE IF EXISTS saldo_clientes');
    }
}
