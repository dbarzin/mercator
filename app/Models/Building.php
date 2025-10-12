<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function roomBays(): HasMany
    {
        return $this->hasMany(Bay::class, 'room_id', 'id')->orderBy('name');
    }

    public function buildingPhysicalServers(): HasMany
    {
        return $this->hasMany(PhysicalServer::class, 'building_id', 'id')->orderBy('name');
    }

    public function buildingPhysicalRouters(): HasMany
    {
        return $this->hasMany(PhysicalRouter::class, 'building_id', 'id')->orderBy('name');
    }

    public function buildingPhysicalSwitch(): HasMany
    {
        return $this->hasMany(PhysicalSwitch::class, 'building_id', 'id')->orderBy('name');
    }

    public function buildingWorkstations(): HasMany
    {
        return $this->hasMany(Workstation::class, 'building_id', 'id')->orderBy('name');
    }

    public function buildingStorageDevices(): HasMany
    {
        return $this->hasMany(StorageDevice::class, 'building_id', 'id')->orderBy('name');
    }

    public function buildingPeripherals(): HasMany
    {
        return $this->hasMany(Peripheral::class, 'building_id', 'id')->orderBy('name');
    }

    public function buildingPhones(): HasMany
    {
        return $this->hasMany(Phone::class, 'building_id', 'id')->orderBy('name');
    }

    public function wifiTerminals(): HasMany
    {
        return $this->hasMany(WifiTerminal::class, 'building_id', 'id')->orderBy('name');
    }

    public function buildingPhysicalSwitches(): HasMany
    {
        return $this->hasMany(PhysicalSwitch::class, 'building_id', 'id')->orderBy('name');
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

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

    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class, 'building_id', 'id')->orderBy('name');
    }

    public function allChildren(): HasMany
    {
        return $this->buildings()->with('allChildren');
    }
}
