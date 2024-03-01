<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BancoChofer extends Model
{
    use HasFactory;

    protected $table = 'banco_chofer';
    protected $fillable = [
        'idChofer',
        'idBanco',
        'nroCuenta',
        'estado',
    ];

    // Relación con el modelo Chofer
    public function chofer()
    {
        return $this->belongsTo(Chofer::class, 'idChofer');
    }


    // Relación con el modelo Banco
    public function banco()
    {
        return $this->belongsTo(Banco::class, 'idBanco');
    }
}
