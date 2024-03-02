<?php

namespace App\Models;

// app/Models/Chofer.php
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoCliente extends Model
{
    use HasFactory;

    protected $table = 'saldo_clientes'; // Nombre de la tabla
    protected $fillable = [
        'idCliente',
        'idBanco',
        'fecha_recarga',
        'referencia',
        'monto',
    ];

    // Relación con el cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }

    // Relación con el banco
    public function banco()
    {
        return $this->belongsTo(Banco::class, 'idBanco');
    }
}


