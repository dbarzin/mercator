<?php

namespace App\Models;

use App\Contracts\HasIcon;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * App\Building
 */
class Building extends Model implements HasIcon
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'buildings';

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
        'type',
        'attributes',
        'site_id',
        'building_id',
        'icon_id',
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

    /** @return HasMany<Bay, $this> */
    public function roomBays(): HasMany
    {
        return $this->hasMany(Bay::class, 'room_id', 'id')->orderBy('name');
    }

    /** @return HasMany<PhysicalServer, $this> */
    public function physicalServers(): HasMany
    {
        return $this->hasMany(PhysicalServer::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return HasMany<PhysicalRouter, $this> */
    public function buildingPhysicalRouters(): HasMany
    {
        return $this->hasMany(PhysicalRouter::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Workstation, $this> */
    public function workstations(): HasMany
    {
        return $this->hasMany(Workstation::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return HasMany<StorageDevice, $this> */
    public function storageDevices(): HasMany
    {
        return $this->hasMany(StorageDevice::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Peripheral, $this> */
    public function peripherals(): HasMany
    {
        return $this->hasMany(Peripheral::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Phone, $this> */
    public function phones(): HasMany
    {
        return $this->hasMany(Phone::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return HasMany<PhysicalRouter, $this> */
    public function physicalRouters(): HasMany
    {
        return $this->hasMany(PhysicalRouter::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return HasMany<WifiTerminal, $this> */
    public function wifiTerminals(): HasMany
    {
        return $this->hasMany(WifiTerminal::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return HasMany<PhysicalSwitch, $this> */
    public function physicalSwitches(): HasMany
    {
        return $this->hasMany(PhysicalSwitch::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return BelongsTo<Site, $this> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    /** @return BelongsTo<Building, $this> */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    /** Collection<Building> */
    public function allParents(): Collection
    {
        $res = collect();
        $cur = $this->loadMissing('building')->building;

        while ($cur) {
            $res->push($cur);
            $cur->loadMissing('building');
            $cur = $cur->building;
        }

        return $res;
    }

    /** @return HasMany<Building, $this> */
    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Building, $this> */
    public function allChildren(): HasMany
    {
        return $this->buildings()->with('allChildren');
    }
}
