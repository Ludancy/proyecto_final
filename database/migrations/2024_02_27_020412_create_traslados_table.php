<?php

// database/migrations/2024_02_26_create_traslados_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrasladosTable extends Migration
{
    public function up()
    {
        Schema::create('traslados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idChofer')->constrained('chofers');
            $table->foreignId('idCliente')->constrained('cliente');
            $table->string('origen');
            $table->string('destino');
            $table->decimal('costo', 10, 2);
            $table->enum('estado', ['pendiente', 'realizado', 'cancelado'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('traslados');
    }
}
