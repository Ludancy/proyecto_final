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
    Schema::create('chofer', function (Blueprint $table) {
        $table->id();
        $table->foreignId('auth_id')->constrained('auth'); // Foreign key to auth table
        // add additional chofer-specific fields if needed
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
