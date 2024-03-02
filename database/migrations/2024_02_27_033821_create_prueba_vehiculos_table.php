<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePruebaVehiculosTable extends Migration
{
    public function up()
    {
        Schema::create('PruebaVehiculo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idVehiculo')->constrained('vehiculos')->onDelete('cascade');
            $table->decimal('calificacion', 5, 2);
            $table->timestamp('fecha_creacion')->useCurrent();
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('PruebaVehiculo');
    }
}

