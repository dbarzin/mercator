<?php


namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

trait Auditable
{
    /**
     * Register model event listeners that create audit logs when the model is created, updated, or deleted.
     *
     * @return void
     */
    public static function bootAuditable(): void
    {
        static::created(function (Model $model): void {
            self::audit('created', $model);
        });

        static::updated(function (Model $model): void {
            self::audit('updated', $model);
        });

        static::deleted(function (Model $model): void {
            self::audit('deleted', $model);
        });
    }

    /**
     * Create an audit log entry for the given model with the provided description.
     *
     * Records an AuditLog containing the description, the model's id and class as the subject,
     * the current authenticated user's id (if any), the model's properties serialized and
     * truncated to 65,534 characters, and the client's IP address (if available).
     *
     * @param string $description A short description of the action to record (e.g., "created", "updated", "deleted").
     * @param Model  $model       The Eloquent model instance being audited.
     */
    protected static function audit(string $description, Model $model): void
    {
        AuditLog::create([
            'description' => $description,
            'subject_id' => $model->id ?? null,
            'subject_type' => $model::class,
            'user_id' => auth()->id() ?? null,
            'properties' => substr($model, 0, 65534) ,
            'host' => request()->ip() ?? null,
        ]);
    }
}