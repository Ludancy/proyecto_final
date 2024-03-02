<?php

// database/migrations/2024_02_28_create_saldo_clientes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaldoClientesTable extends Migration
{
    public function up()
    {
        Schema::create('saldo_clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idCliente')->constrained('cliente')->onDelete('cascade');
            $table->foreignId('idBanco')->constrained('bancos')->onDelete('cascade');
            $table->date('fecha_recarga');
            $table->string('referencia');
            $table->decimal('monto', 10, 2);
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('saldo_clientes');
    }
}
