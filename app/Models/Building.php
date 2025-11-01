<?php


namespace App\Models;

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
class Building extends Model
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

    /** @return HasMany<Bay, self> */
    public function roomBays(): HasMany
    {
        return $this->hasMany(Bay::class, 'room_id', 'id')->orderBy('name');
    }

    /** @return HasMany<PhysicalServer, self> */
    public function buildingPhysicalServers(): HasMany
    {
        return $this->hasMany(PhysicalServer::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return HasMany<PhysicalRouter, self> */
    public function buildingPhysicalRouters(): HasMany
    {
        return $this->hasMany(PhysicalRouter::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return HasMany<PhysicalSecurityDevice, self> */
    public function buildingPhysicalSwitch(): HasMany
    {
        return $this->hasMany(PhysicalSwitch::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Workstation, self> */
    public function buildingWorkstations(): HasMany
    {
        return $this->hasMany(Workstation::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return HasMany<StorageDevice, self> */
    public function buildingStorageDevices(): HasMany
    {
        return $this->hasMany(StorageDevice::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Peripheral, self> */
    public function buildingPeripherals(): HasMany
    {
        return $this->hasMany(Peripheral::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Phone, self> */
    public function buildingPhones(): HasMany
    {
        return $this->hasMany(Phone::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return HasMany<WifiTerminal, self> */
    public function wifiTerminals(): HasMany
    {
        return $this->hasMany(WifiTerminal::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return HasMany<PhysicalSwitch, self> */
    public function buildingPhysicalSwitches(): HasMany
    {
        return $this->hasMany(PhysicalSwitch::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return BelongsTo<Site, self> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    /** @return BelongsTo<Building, self> */
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

    /** @return HasMany<Building, self> */
    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class, 'building_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Building, self> */
    public function allChildren(): HasMany
    {
        return $this->buildings()->with('allChildren');
    }
}
