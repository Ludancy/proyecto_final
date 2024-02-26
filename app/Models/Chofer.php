<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chofer extends Model
{
    public $timestamps = false; // Desactiva timestamps

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
