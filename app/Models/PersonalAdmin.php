<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalAdmin extends Model
{
    protected $table = 'PersonalAdmin';  // Especifica el nombre de tu tabla

    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'fechaNacimiento',
        'idAuth',
    ];

    // Resto del código...
}

