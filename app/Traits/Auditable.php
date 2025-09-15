<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function (Model $model) {
            self::audit('created', $model);
        });

        static::updated(function (Model $model) {
            self::audit('updated', $model);
        });

        static::deleted(function (Model $model) {
            self::audit('deleted', $model);
        });
    }

    protected static function audit($description, $model)
    {
        AuditLog::create([
            'description' => $description,
            'subject_id' => $model->id ?? null,
            'subject_type' => $model::class ?? null,
            'user_id' => auth()->id() ?? null,
            'properties' => substr($model, 0, 65534) ?? null,
            'host' => request()->ip() ?? null,
        ]);
    }
}
