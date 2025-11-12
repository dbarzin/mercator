<?php

namespace App\Models;

use App\Contracts\HasIcon;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Site
 */
class Site extends Model implements HasIcon
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'sites';

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
        'description',
        'icon_id',
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

    /** @return HasMany<Building, $this> */
    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class, 'site_id', 'id')->orderBy('name');
    }

    /** @return HasMany<PhysicalServer, $this> */
    public function physicalServers(): HasMany
    {
        return $this->hasMany(PhysicalServer::class, 'site_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Workstation, $this> */
    public function workstations(): HasMany
    {
        return $this->hasMany(Workstation::class, 'site_id', 'id')->orderBy('name');
    }

    /** @return HasMany<StorageDevice, $this> */
    public function storageDevices(): HasMany
    {
        return $this->hasMany(StorageDevice::class, 'site_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Peripheral, $this> */
    public function peripherals(): HasMany
    {
        return $this->hasMany(Peripheral::class, 'site_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Phone, $this> */
    public function phones(): HasMany
    {
        return $this->hasMany(Phone::class, 'site_id', 'id')->orderBy('name');
    }

    /** @return HasMany<PhysicalSwitch, $this> */
    public function physicalSwitches(): HasMany
    {
        return $this->hasMany(PhysicalSwitch::class, 'site_id', 'id')->orderBy('name');
    }
}
