<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalAdmin extends Model
{
    protected $table = 'PersonalAdmin';  // Especifica el nombre de tu tabla

    protected $fillable = [
        // Otros campos específicos de PersonalAdmin, si los hay
        'idAuth',
    ];

    // Resto del código...
}

