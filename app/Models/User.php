<?php

namespace App\Models;
// User.php
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'auths'; // Asegúrate de que esta línea refleje el nuevo nombre de la tabla

    // protected $fillable = [
    //     'email_user',
    //     'password',
    //     'role'
    // ];
    protected $fillable = ['idRol', 'correo', 'password', 'fechaCreacion', 'estado'];

    protected $hidden = [
        'password'
    ];

    // public function cliente()
    // {
    //     return $this->hasOne('App\Models\Cliente');
    // }
    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'idAuth');
    }


    public function chofer()
    {
        return $this->hasOne('App\Models\Chofer');
    }

    public function personalAdmin()
    {
        return $this->hasOne('App\Models\PersonalAdmin');
    }
}
