<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Incidence extends Model
{
    use HasFactory;
    use Auditable;

    // protected $table = 'incidences';
    protected $primaryKey = 'incidence_id';

    protected $fillable = [
        'resource_id',
        'user_id',
        'created_by',
        'updated_by',
        'date_incidence',
        'description',
        'status'
    ];

    protected $cast = [
        'status'         => 'booleans',
        'date_incidence' => 'datetime',
    ];

    // Relaciones

    // Recurso al que pertenece la incidencia
    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id', 'resource_id');
    }

    // Usuario que reporta la incidencia
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Usuario que creo el registro
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    // Usuario que actualiza un registro
    public function updater()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }
}
