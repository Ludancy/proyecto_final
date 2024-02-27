<?php

namespace App\Models;

// app/Models/Chofer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chofer extends Model
{
    use HasFactory;

    public $timestamps = false; // Desactiva timestamps

    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'fechaNacimiento',
        'idAuth',
    ];
    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'idChofer');
    }
    public function auths()
    {
        return $this->belongsTo('App\Models\Auths', 'idAuth');
    }

    // Relación con la tabla de traslados
    public function traslados()
    {
        return $this->hasMany('App\Models\Traslado', 'idChofer');
    }
}

