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
// auth table
    Schema::create('auths', function (Blueprint $table) {
        $table->id();
        $table->string('email_user')->unique();
        $table->string('password');
        $table->string('role'); // Adjust the 'role' field according to your roles
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
        Schema::dropIfExists('auths');
    }
};
