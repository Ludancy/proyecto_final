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
            $table->foreignId('idChofer')->constrained('chofers')->onDelete('cascade');
            $table->foreignId('idCliente')->constrained('cliente')->onDelete('cascade');
            $table->unsignedBigInteger('origen');
            $table->unsignedBigInteger('destino');
            $table->decimal('costo', 10, 2);
            $table->enum('estado', ['pendiente', 'realizado', 'cancelado'])->default('pendiente');
            $table->unsignedBigInteger('idVehiculo')->nullable(); // Añadido para la relación con vehículos
            $table->timestamps();

            $table->foreign('origen')->references('id')->on('lugares')->onDelete('cascade');
            $table->foreign('destino')->references('id')->on('lugares')->onDelete('cascade');
            $table->foreign('idVehiculo')->references('id')->on('vehiculos')->onDelete('set null'); // Set null si no hay vehículo asignado
        });
    }

    public function down()
    {
        Schema::dropIfExists('traslados');
    }
}
