<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PruebaChofer extends Model
{
    use HasFactory;

    protected $table = 'pruebaChofer'; // Asegúrate de que coincida con el nombre real de la tabla

    protected $fillable = [
        'idChofer',
        'calificacion',
        // Agrega otros campos según tus necesidades
    ];

    // Relación con la tabla de Chofer
    public function chofer()
    {
        return $this->belongsTo(Chofer::class, 'idChofer');
    }
}

