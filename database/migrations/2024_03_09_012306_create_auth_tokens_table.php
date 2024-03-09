<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAuthTokensTable extends Migration
{
    public function up()
    {
        // Crear la tabla auth_tokens utilizando DB::statement
        DB::statement('
            CREATE TABLE auth_tokens (
                id INT PRIMARY KEY AUTO_INCREMENT,
                user_id INT,
                token VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES auths(id) ON DELETE CASCADE
            )
        ');
    }

    public function down()
    {
        // Eliminar la tabla auth_tokens
        DB::statement('DROP TABLE IF EXISTS auth_tokens');
    }
}
