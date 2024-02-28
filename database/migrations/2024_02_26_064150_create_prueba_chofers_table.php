<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePruebaChofersTable extends Migration
{
    public function up()
    {
        Schema::create('pruebaChofer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idChofer')->constrained('chofers')->onDelete('cascade'); // Reemplaza 'chofers' con el nombre real de la tabla de Chofer
            $table->integer('calificacion');
            $table->timestamp('fecha_creacion')->useCurrent();
            // Agrega otros campos según tus necesidades
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pruebaChofer');
    }
}

