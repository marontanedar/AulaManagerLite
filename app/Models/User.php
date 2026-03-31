<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;
use App\Models\Incidence;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    //protected $table = 'users'; //No es necesario declarar
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // Relaciones

    // Pertenece a un rol
    public function role()
    {
        return $this->belongsTo(Role::class, 'rol_id', 'rol_id');
    }

    // Incidencias reportadas por un usuario
    public function incidences()
    {
        return $this->hasMany(Incidence::class, 'user_id', 'user_id');
    }

    // Reservas hechas por este usuario
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id', 'user_id');
    }

    // Recursos creados
    public function createdResources()
    {
        return $this->hasMany(Resource::class, 'created_by', 'user_id');
    }

    // Recursos actualizados
    public function updatedResources()
    {
        return $this->hasMany(Resource::class, 'updated_by', 'user_id');
    }

    // Es admin?
    public function isAdmin(): bool
    {
        return $this->role && $this->role->admin === true;
    }
}
