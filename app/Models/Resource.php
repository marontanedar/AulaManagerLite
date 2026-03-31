<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Resource extends Model
{
    use HasFactory;
    use Auditable;

    //protected $table = 'resources';
    protected $primaryKey = 'resource_id';

    protected $fillable = [
        'name',
        'description',
        'status',
        'category_id',
        'created_by',
        'updated_by'
    ];

    // Relaciones

    // Reservas de este recurso
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'resource_id', 'resource_id');
    }

    // Recurso pertenece a categoría
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    // usuario que creo el recurso
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    // Quién acttualizo el recurso
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_id');
    }

    // Incidencias de este recurso
    public function incidences()
    {
        return $this->hasMany(Incidence::class, 'resource_id', 'resource_id');
    }
}
