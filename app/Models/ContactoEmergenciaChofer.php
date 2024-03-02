<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactoEmergenciaChofer extends Model
{
    use HasFactory;

    protected $table = 'contacto_emergencia_chofer'; // Asegúrate de que coincida con el nombre de tu tabla
    protected $fillable = [
        'idChofer', // Supongo que esto es una relación con el modelo Chofer
        'nombre',
        'telefono',
        
    ];

    // Relación con el chofer
    public function chofer()
    {
        return $this->belongsTo(Chofer::class, 'idChofer');
    }
}



