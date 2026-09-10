<?php

namespace App\Models;

use App\Contracts\HasIconContract;
use App\Contracts\HasPrefix;
use App\Contracts\HasUniqueIdentifierContract;
use App\Factories\BayFactory;
use App\Traits\Auditable;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCartographers;

/**
 * App\Bay
 */
class Bay extends Model implements HasPrefix, HasIconContract, HasUniqueIdentifierContract
{
    use Auditable, HasIcon, HasUniqueIdentifier, HasFactory, SoftDeletes;
    use HasCartographers;

    public $table = 'bays';

    public static string $prefix = 'BAY_';

    public static string $icon = '/images/bay.png';

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
        'ext_refs',
        'name',
        'type',
        'attributes',
        'description',
        'building_id',
        'site_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function newFactory(): Factory
    {
        return BayFactory::new();
    }

    /** @return HasMany<PhysicalServer, $this> */
    public function physicalServers(): HasMany
    {
        return $this->hasMany(PhysicalServer::class, 'bay_id', 'id')->orderBy('name');
    }

    /** @return HasMany<StorageDevice, $this> */
    public function storageDevices(): HasMany
    {
        return $this->hasMany(StorageDevice::class, 'bay_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Peripheral, $this> */
    public function peripherals(): HasMany
    {
        return $this->hasMany(Peripheral::class, 'bay_id', 'id')->orderBy('name');
    }

    /** @return HasMany<PhysicalSwitch, $this> */
    public function physicalSwitches(): HasMany
    {
        return $this->hasMany(PhysicalSwitch::class, 'bay_id', 'id')->orderBy('name');
    }

    /** @return HasMany<PhysicalRouter, $this> */
    public function physicalRouters(): HasMany
    {
        return $this->hasMany(PhysicalRouter::class, 'bay_id', 'id')->orderBy('name');
    }

    /** @return HasMany<PhysicalSecurityDevice, $this> */
    public function physicalSecurityDevices(): HasMany
    {
        return $this->hasMany(PhysicalSecurityDevice::class, 'bay_id', 'id')->orderBy('name');
    }

    /** @return BelongsTo<Building, $this> */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    /** @return BelongsTo<Site, $this> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    /** @param Builder<static> $query */
    public function scopeMaturityLevel1(Builder $query): Builder
    {
        return $query
            ->whereNotNull('description')
            ->whereNotNull('site_id')
            ->whereNotNull('building_id');
    }
}
