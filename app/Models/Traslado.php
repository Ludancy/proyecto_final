<?php

// app/Models/Traslado.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traslado extends Model
{
    use HasFactory;

    protected $fillable = [
        'idChofer',
        'idCliente',
        'origen',
        'destino',
        'costo',
        'estado',
    ];

    public function chofer()
    {
        return $this->belongsTo(Chofer::class, 'idChofer');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }
}
