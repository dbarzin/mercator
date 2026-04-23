<?php

namespace App\Models;

use App\Factories\MApplicationFactory;
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

class MApplication extends Model
{
    use Auditable, HasIcon, HasUniqueIdentifier, HasFactory, SoftDeletes;

    public $table = 'm_applications';

    public static string $prefix = 'APP_';

    public static string $icon = '/images/application.png';

    public static array $searchable = [
        'name',
        'description',
        'vendor',
        'responsible',
        'editor',
        'functional_referent',
        'attributes',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'install_date',
        'update_date',
        'next_update',
    ];

    protected $fillable = [
        'name',
        'type',
        'attributes',
        'icon_id',
        'application_block_id',
        'description',
        'vendor',
        'product',
        'version',
        'entity_resp_id',
        'functional_referent',
        'editor',
        'technology',
        'documentation',
        'users',
        'responsible',
        'security_need_c',
        'security_need_i',
        'security_need_a',
        'security_need_t',
        'security_need_auth',
        'rto',
        'rpo',
        'external',
        'patching_frequency',
        'install_date',
        'update_date',
        'next_update',
    ];

    protected $casts = [
        'patching_frequency' => 'integer',
        'update_date'        => 'date',
        'next_update'        => 'date',
    ];

    protected static function newFactory(): Factory
    {
        return MApplicationFactory::new();
    }

    /**
    /*
     * format $delay in minute to string in format "a days b hours c minutes"
     */
    public static function formatDelay(?int $delay): ?string
    {
        if ($delay === null) {
            return null;
        }

        $days = intdiv($delay, 60 * 24);
        $hours = intdiv($delay, 60) % 24;
        $minutes = $delay % 60;

        $parts = [];

        if ($days > 0) {
            $parts[] = $days.' '.trans($days > 1 ? 'global.days' : 'global.day');
        }

        if ($hours > 0) {
            $parts[] = $hours.' '.trans($hours > 1 ? 'global.hours' : 'global.hour');
        }

        if ($minutes > 0) {
            $parts[] = $minutes.' '.trans($minutes > 1 ? 'global.minutes' : 'global.minute');
        }

        return implode(' ', $parts);
    }

    /** @return HasMany<Flux, $this> */
    public function applicationSourceFluxes(): HasMany
    {
        return $this->hasMany(Flux::class, 'application_source_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Flux, $this> */
    public function applicationDestFluxes(): HasMany
    {
        return $this->hasMany(Flux::class, 'application_dest_id', 'id')->orderBy('name');
    }

    /** @return BelongsToMany<Entity, $this> */
    public function entities(): BelongsToMany
    {
        return $this->belongsToMany(Entity::class)->orderBy('name');
    }

    /** @return BelongsTo<Entity, $this> */
    public function entityResp(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'entity_resp_id');
    }

    /** @return BelongsToMany<Process, $this> */
    public function processes(): BelongsToMany
    {
        return $this->belongsToMany(Process::class)->orderBy('name');
    }

    /** @return BelongsToMany<Activity, $this> */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class)->orderBy('name');
    }

    /** @return BelongsToMany<ApplicationService, $this> */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(ApplicationService::class)->orderBy('name');
    }

    /** @return BelongsToMany<Database, $this> */
    public function databases(): BelongsToMany
    {
        return $this->belongsToMany(Database::class)->orderBy('name');
    }

    /** @return BelongsToMany<Workstation, $this> */
    public function workstations(): BelongsToMany
    {
        return $this->belongsToMany(Workstation::class)->orderBy('name');
    }

    /** @return BelongsToMany<LogicalServer, $this> */
    public function logicalServers(): BelongsToMany
    {
        return $this->belongsToMany(LogicalServer::class)->orderBy('name');
    }

    /** @return BelongsToMany<LogicalServer, $this> */
    public function containers(): BelongsToMany
    {
        return $this->belongsToMany(Container::class)->orderBy('name');
    }

    /** @return BelongsToMany<SecurityDevice, $this> */
    public function securityDevices(): BelongsToMany
    {
        return $this->belongsToMany(SecurityDevice::class)->orderBy('name');
    }

    /** @return BelongsTo<ApplicationBlock, $this> */
    public function applicationBlock(): BelongsTo
    {
        return $this->belongsTo(ApplicationBlock::class, 'application_block_id');
    }

    /** @return BelongsToMany<AdminUser, $this> */
    public function administrators(): BelongsToMany
    {
        return $this->belongsToMany(AdminUser::class, 'admin_user_m_application', 'm_application_id', 'admin_user_id');
    }

    /** @return HasMany<MApplicationEvent, $this> */
    public function events(): HasMany
    {
        return $this->hasMany(MApplicationEvent::class, 'm_application_id', 'id')->with('user');
    }

    /** @return BelongsToMany<SecurityControl, $this> */
    public function securityControls(): BelongsToMany
    {
        return $this->belongsToMany(SecurityControl::class, 'security_control_m_application')->orderBy('name');
    }

    /** @return BelongsToMany<Certificate, $this> */
    public function certificates(): BelongsToMany
    {
        return $this->belongsToMany(Certificate::class)->orderBy('name');
    }

}


