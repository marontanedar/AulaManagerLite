<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $primaryKey = 'log_id';

    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'description',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    // Relaciones

    // Usuario que hizo la acción
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public static function record(
        string $action,
        string $model,
        int $modelId,
        ?string $description = null,
        ?array $changes = null
    ): void {
        // Si no hay sesión activa (seeders, consola) no registra
        if (!auth()->check()) return;

        static::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'model'       => $model,
            'model_id'    => $modelId,
            'description' => $description,
            'changes'     => $changes,
        ]);
    }

    public function actionLabel(): string
    {
        return match ($this->action) {
            'created'   => 'Creado',
            'updated'   => 'Actualizado',
            'deleted'   => 'Eliminado',
            'reserved'  => 'Reservado',
            'cancelled' => 'Cancelado',
            'resolved'  => 'Resuelto',
            default     => ucfirst($this->action),
        };
    }

    public function actionColor(): string
    {
        return match ($this->action) {
            'created'   => 'success',
            'updated'   => 'warning',
            'deleted'   => 'danger',
            'reserved'  => 'primary',
            'cancelled' => 'secondary',
            'resolved'  => 'info',
            default     => 'dark',
        };
    }
}
