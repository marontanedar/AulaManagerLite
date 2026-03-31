<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    public static function bootAuditable(): void
    {

        // Al crear
        static::created(function ($model) {
            AuditLog::record(
                action: 'created',
                model: class_basename($model),
                modelId: $model->getKey(),
                description: "Registro creado: " . (is_string($model->name ?? null) ? $model->name : $model->getKey()),
            );
        });

        // Al actualizar
        static::updated(function ($model) {
            $dirty = $model->getDirty();
            $original = array_intersect_key($model->getOriginal(), $dirty);

            unset($dirty['updated_at'], $original['updated_at']);

            if (empty($dirty)) {
                return;
            }

            AuditLog::record(
                action: 'updated',
                model: class_basename($model),
                modelId: $model->getKey(),
                description: 'Registro actualizado: ' . (is_string($model->name ?? null) ? $model->name : $model->getKey()),
                changes: [
                    'before' => $original,
                    'after' => $dirty,
                ],
            );
        });

        // Al eliminar
        static::deleted(function ($model) {
            AuditLog::record(
                action: 'deleted',
                model: class_basename($model),
                modelId: $model->getKey(),
                description: "Registro eliminado: " . (is_string($model->name ?? null) ? $model->name : $model->getKey()),
            );
        });
    }
}
