<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Auths extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;  // Incluye la trait HasApiTokens

    protected $table = 'auths';  // Especifica el nombre de tu tabla

    protected $fillable = [
        'email_user', 'password', 'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cliente()
    {
        return $this->hasOne('App\Models\Cliente');
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
