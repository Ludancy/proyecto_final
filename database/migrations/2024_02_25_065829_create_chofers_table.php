<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // chofer table
        Schema::create('chofers', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();
            $table->string('cedula')->nullable();
            $table->date('fechaNacimiento')->nullable();
            $table->foreignId('idAuth')->constrained('auths')->onDelete('CASCADE');
            $table->string('entidadBancaria')->nullable(); // Agregado para la entidad bancaria
            $table->string('numeroCuenta')->nullable(); // Agregado para el número de cuenta
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chofers');
    }
};

