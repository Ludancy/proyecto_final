<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chofer extends Model
{
    use HasFactory;

    protected $table = 'chofers';
    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'fechaNacimiento',
        'idAuth',
        'entidadBancaria',
        'numeroCuenta',
        'saldo',
        
    ];
    

// Relación con la entidad bancaria
public function cuentasBancarias()
{
    return $this->hasMany(BancoChofer::class, 'idChofer');
}

    // Relación con los contactos de emergencia
    public function contactosEmergencia()
    {
        return $this->hasMany(ContactoEmergenciaChofer::class, 'idChofer');
    }

    // Relación con los vehículos (asumo que tienes un modelo Vehiculo)
    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'idChofer');
    }

    // Relación con los traslados
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
