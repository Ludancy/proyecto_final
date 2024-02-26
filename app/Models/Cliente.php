<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'cliente';  // Especifica el nombre de tu tabla

    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'fechaNacimiento',
        'idAuth',
    ];

    public function auths()
    {
        return $this->belongsTo('App\Models\Auths', 'auth_id');
    }
}

