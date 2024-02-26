<?php

// database/migrations/2024_02_25_create_auths_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthsTable extends Migration
{
    public function up()
    {
        Schema::create('auths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idRol')->constrained('roles');
            $table->string('correo')->unique();
            $table->string('password');
            $table->timestamp('fechaCreacion')->nullable();
            $table->string('estado');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('auths');
    }
}

