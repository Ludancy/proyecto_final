<?php

// database/migrations/[timestamp]_create_banco_chofer_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBancoChoferTable extends Migration
{
    public function up()
    {
        Schema::create('banco_chofer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idChofer')->constrained('chofers')->onDelete('CASCADE');
            $table->foreignId('idBanco')->constrained('bancos')->onDelete('CASCADE');
            $table->string('nroCuenta');
            $table->string('estado')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('banco_chofer');
    }
}

