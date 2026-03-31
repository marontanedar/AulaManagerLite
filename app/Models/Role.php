<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    //protected $table = "roles"; //no es necesario ponerlo, laravel trae por defecto
    protected $primaryKey = 'rol_id';

    protected $fillable = [
        'nameRol',
        'admin',
    ];

    protected $casts = ['admin' => 'boolean'];

    // Relaciones

    //Rol tiene muchos usuarios
    public function users()
    {
        return $this->hasMany(User::class, 'rol_id', 'rol_id');
    }

    //Es administrador?
    public function isAdmin(): bool
    {
        return $this->admin === true;
    }
}
