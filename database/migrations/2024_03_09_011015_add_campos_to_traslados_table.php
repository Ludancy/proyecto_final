<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposToTrasladosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('traslados', function (Blueprint $table) {
            $table->date('fecha_pago')->nullable(); // Puedes ajustar la definición según tus necesidades
            $table->string('referencia')->nullable(); // Añade la columna referencia
            $table->decimal('monto_pagado', 10, 2)->nullable(); // Añade la columna monto_pagado
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('traslados', function (Blueprint $table) {
            $table->dropColumn('fecha_pago');
            $table->dropColumn('referencia');
            $table->dropColumn('monto_pagado');
        });
    }
}
