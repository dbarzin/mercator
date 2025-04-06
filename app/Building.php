<?php

namespace App;

use App\Traits\Auditable;
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
    use HasFactory, SoftDeletes, Auditable;

    public $table = 'buildings';

    public static $searchable = [
        'name',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'attributes',
        'site_id',
        'camera',
        'badge',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function roomBays(): HasMany
    {
        return $this->hasMany(Bay::class, 'room_id', 'id')->orderBy('name');
    }

    public function buildingPhysicalServers(): HasMany
    {
        return $this->hasMany(PhysicalServer::class, 'building_id', 'id')->orderBy('name');
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
}
