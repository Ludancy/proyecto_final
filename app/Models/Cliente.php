<?php

// app/Models/Cliente.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'cliente';  // Especifica el nombre de tu tabla

    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'fechaNacimiento',
        'idAuth',
        'saldo', // Agrega la propiedad saldo
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'idAuth');
    }

    // Nueva relación para manejar los traslados solicitados por un cliente
    public function traslados()
    {
        return $this->hasMany('App\Models\Traslado', 'idCliente');
    }

    // Relación con las recargas de saldo
    public function saldoRecargas()
    {
        return $this->hasMany(SaldoCliente::class, 'idCliente');
    }
}

