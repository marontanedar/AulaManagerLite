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
    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id', 'resource_id');
    }
}
