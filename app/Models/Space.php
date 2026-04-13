<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    use HasFactory;
    Use Auditable;

    protected $primaryKey = 'space_id';

    protected $fillable = [
        'name',
        'category_id',
        'capacity',
        'status',
        'description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'status' => 'integer',
        'capacity' => 'integer',
    ];

    // Relaciones

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'space_id', 'space_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_id');
    }

    // ¿está disponible en un tramo horario?
    public function isAvailable(string $date, string $start, string $end): bool
    {
        return !$this->reservations()
            ->where('date', $date)
            ->where('start', '<', $end)
            ->where('end', '>', $start)
            ->exists();
    }
}
