<?php

namespace App\Models;

// app/Models/Chofer.php
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    use HasFactory;

    protected $table = 'bancos'; // Nombre de la tabla
    protected $fillable = [
        'nombre',
        'codigo',
        
    ];

    // Relación con las recargas de saldo
    public function recargasSaldo()
    {
        return $this->hasMany(SaldoCliente::class, 'idBanco');
    }
}


