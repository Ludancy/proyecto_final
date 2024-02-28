<?php

namespace App\Models;

// app/Models/Chofer.php
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chofer extends Model
{
    use HasFactory;

    public $timestamps = false;

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

    public function user()
    {
        return $this->belongsTo(User::class, 'idAuth');
    }

    public function traslados()
    {
        return $this->hasMany(Traslado::class, 'idChofer');
    }

    // Agrega el evento para manejar la eliminación en cascada
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($chofer) {
            // Eliminar también el usuario asociado al chofer
            $chofer->user->delete();
        });
    }
}


