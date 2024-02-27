<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PruebaVehiculo extends Model
{
    use HasFactory;

    protected $table = 'pruebavehiculo';
    public $timestamps = false; // Desactivar timestamps

    protected $fillable = [
        'idVehiculo',
        'calificacion',
        // Otros campos según tus necesidades
    ];

    // Relación con el modelo Vehiculo
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'idVehiculo');
    }
}
