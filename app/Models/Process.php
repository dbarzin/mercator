<?php

namespace App\Models;

use App\Contracts\HasIcon;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Process
 */
class Process extends Model implements HasIcon
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'processes';

    public static array $searchable = [
        'name',
        'description',
        'icon_id',
        'in_out',
        'owner',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'in_out',
        'security_need_c',
        'security_need_i',
        'security_need_a',
        'security_need_t',
        'security_need_auth',
        'owner',
        'macroprocess_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /*
     * Implement HasIcon
     */
    public function setIconId(?int $id): void
    {
        $this->icon_id = $id;
    }

    public function getIconId(): ?int
    {
        return $this->icon_id;
    }

    /** @return BelongsToMany<Information, $this> */
    public function information(): BelongsToMany
    {
        return $this->belongsToMany(Information::class)->orderBy('name');
    }

    /** @return BelongsToMany<MApplication, $this> */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    /** @return BelongsToMany<Activity, $this> */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class)->orderBy('name');
    }

    /** @return BelongsToMany<Entity, $this> */
    public function entities(): BelongsToMany
    {
        return $this->belongsToMany(Entity::class)->orderBy('name');
    }

    /** @return HasMany<Operation, $this> */
    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class, 'process_id', 'id')->orderBy('name');
    }

    /** @return BelongsToMany<DataProcessing, $this> */
    public function dataProcesses(): BelongsToMany
    {
        return $this->belongsToMany(DataProcessing::class, 'data_processing_process')->orderBy('name');
    }

    /** @return BelongsTo<MacroProcessus, $this> */
    public function macroProcess(): BelongsTo
    {
        return $this->belongsTo(MacroProcessus::class, 'macroprocess_id');
    }

    /** @return BelongsToMany<SecurityControl, $this> */
    public function securityControls(): BelongsToMany
    {
        return $this->belongsToMany(SecurityControl::class, 'security_control_process')->orderBy('name');
    }
}
