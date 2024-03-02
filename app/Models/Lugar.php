<?php

// app/Models/Lugar.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lugar extends Model
{
    use HasFactory;

    protected $table = 'lugares'; // Nombre de la tabla

    protected $fillable = [
        'nombre',
        'valor_numerico',
        'latitud',
        'longitud',
    ];

    // Puedes agregar relaciones u otros métodos según tus necesidades
}
