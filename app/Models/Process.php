<?php

namespace App\Models;

use App\Contracts\HasIconContract;
use App\Contracts\HasPrefix;
use App\Factories\ProcessFactory;
use App\Traits\Auditable;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * App\Process
 */
class Process extends Model implements HasIconContract, HasPrefix
{
    use Auditable, HasFactory, HasUniqueIdentifier, HasIcon, SoftDeletes;

    public $table = 'processes';

    public static string $prefix = 'PROCESS_';

    public static string $icon = '/images/process.png';

    protected $fillable = [
        'name',
        'icon_id',
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


    protected static function newFactory(): Factory
    {
        return ProcessFactory::new();
    }

    /** @return BelongsToMany<Information, $this> */
    public function information(): BelongsToMany
    {
        return $this->belongsToMany(Information::class)->orderBy('name');
    }

    /** @return BelongsToMany<Application, $this> */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(Application::class)->orderBy('name');
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

    public function graphs(): Collection
    {
        return once(fn() => Graph::query()
            ->select('id','name')
            ->where('class', '=', '2')
            ->whereLike('content', '%"#'.$this->getUID().'"%')
            ->get()
        );
    }

}
