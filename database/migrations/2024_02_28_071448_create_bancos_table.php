<?php

// database/migrations/2024_02_28_create_bancos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBancosTable extends Migration
{
    public function up()
    {
        Schema::create('bancos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo');
            // Otros campos según tus necesidades
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bancos');
    }
}
