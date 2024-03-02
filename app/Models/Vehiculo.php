<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;
    protected $table = 'vehiculos';

    protected $fillable = [
        'idChofer',
        'marca',
        'color',
        'placa',
        'anio_fabricacion',
        'estado_vehiculo',
        'estado_actual'
        
    ];

    // Relación con la tabla de Chofer
    public function chofer()
    {
        return $this->belongsTo(Chofer::class, 'idChofer');
    }

    // Relación con la tabla de PruebaVehiculo
    public function pruebasVehiculo()
    {
        return $this->hasMany(PruebaVehiculo::class, 'idVehiculo');
    }
}

