<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Flux Applicatif
 */
class Flux extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'fluxes';

    public static array $searchable = [
        'name',
        'description',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'nature',
        'attributes',
        'description',
        'application_source_id',
        'service_source_id',
        'module_source_id',
        'database_source_id',
        'application_dest_id',
        'service_dest_id',
        'module_dest_id',
        'database_dest_id',
        'crypted',
        'bidirectional',
    ];

    public function sourceId(): ?string
    {
        if ($this->application_source_id !== null) {
            return 'APP_'.$this->application_source_id;
        }
        if ($this->service_source_id !== null) {
            return 'SRV_'.$this->service_source_id;
        }
        if ($this->module_source_id !== null) {
            return 'MOD_'.$this->module_source_id;
        }
        if ($this->database_source_id !== null) {
            return 'DB_'.$this->database_source_id;
        }

        return null;
    }

    public function destId(): ?string
    {
        if ($this->application_dest_id !== null) {
            return 'APP_'.$this->application_dest_id;
        }
        if ($this->service_dest_id !== null) {
            return 'SRV_'.$this->service_dest_id;
        }
        if ($this->module_dest_id !== null) {
            return 'MOD_'.$this->module_dest_id;
        }
        if ($this->database_dest_id !== null) {
            return 'DB_'.$this->database_dest_id;
        }

        return null;
    }

    /** @return BelongsTo<MApplication, $this> */
    public function application_source(): BelongsTo
    {
        return $this->belongsTo(MApplication::class, 'application_source_id');
    }

    /** @return BelongsTo<ApplicationService, $this> */
    public function service_source(): BelongsTo
    {
        return $this->belongsTo(ApplicationService::class, 'service_source_id');
    }

    /** @return BelongsTo<ApplicationModule, $this> */
    public function module_source(): BelongsTo
    {
        return $this->belongsTo(ApplicationModule::class, 'module_source_id');
    }

    /** @return BelongsTo<Database, $this> */
    public function database_source(): BelongsTo
    {
        return $this->belongsTo(Database::class, 'database_source_id');
    }

    /** @return BelongsTo<MApplication, $this> */
    public function application_dest(): BelongsTo
    {
        return $this->belongsTo(MApplication::class, 'application_dest_id');
    }

    /** @return BelongsTo<ApplicationService, $this> */
    public function service_dest(): BelongsTo
    {
        return $this->belongsTo(ApplicationService::class, 'service_dest_id');
    }

    /** @return BelongsTo<ApplicationModule, $this> */
    public function module_dest(): BelongsTo
    {
        return $this->belongsTo(ApplicationModule::class, 'module_dest_id');
    }

    /** @return BelongsTo<Database, $this> */
    public function database_dest(): BelongsTo
    {
        return $this->belongsTo(Database::class, 'database_dest_id');
    }
}
