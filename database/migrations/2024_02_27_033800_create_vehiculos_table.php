<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiculosTable extends Migration
{
    public function up()
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idChofer')->constrained('chofers')->onDelete('cascade');
            $table->string('marca');
            $table->string('color');
            $table->string('placa')->unique();
            $table->integer('anio_fabricacion');
            $table->string('estado_vehiculo');
            // Otros campos según tus necesidades
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehiculos');
    }
}

