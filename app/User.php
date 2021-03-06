<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'Usuario';
    protected $primaryKey = 'id';
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login', 'nombres', 'apellidos', 'ci', 'telefono', 'correo', 'idMesa', 'idRol'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */    
    /*
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];*/


    //Para hacer referencia a la tabla Rol
    public function rol() {
        return $this->belongsTo('App\Rol');
    }

}
