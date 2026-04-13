<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Reservation extends Model
{
    use HasFactory;
    use Auditable;

    //protected $table = 'reservations';
    protected $primaryKey = 'reservation_id';

    protected $fillable = [
        'space_id',
        'user_id',
        'resource_id',
        'date',
        'start',
        'end',
    ];

    protected $cast = ['date' => 'date'];

    //Relaciones

    // Usuario que hizo reserva
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Recurso reservado
    public function resources()
    {
        return $this->belongsToMany(
            Resource::class,
            'reservation_resource',
            'reservation_id',
            'resource_id'
        );
    }

    //
    public function space()
    {
        return $this->belongsTo(Space::class, 'space_id', 'space_id');
    }
}
