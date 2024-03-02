<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Building
 */
class Building extends Model
{
    use SoftDeletes, Auditable;

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
        'site_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function roomBays()
    {
        return $this->hasMany(Bay::class, 'room_id', 'id')->orderBy('name');
    }

    public function buildingPhysicalServers()
    {
        return $this->hasMany(PhysicalServer::class, 'building_id', 'id')->orderBy('name');
    }

    public function buildingWorkstations()
    {
        return $this->hasMany(Workstation::class, 'building_id', 'id')->orderBy('name');
    }

    public function buildingStorageDevices()
    {
        return $this->hasMany(StorageDevice::class, 'building_id', 'id')->orderBy('name');
    }

    public function buildingPeripherals()
    {
        return $this->hasMany(Peripheral::class, 'building_id', 'id')->orderBy('name');
    }

    public function buildingPhones()
    {
        return $this->hasMany(Phone::class, 'building_id', 'id')->orderBy('name');
    }

    public function wifiTerminals()
    {
        return $this->hasMany(WifiTerminal::class, 'building_id', 'id')->orderBy('name');
    }

    public function buildingPhysicalSwitches()
    {
        return $this->hasMany(PhysicalSwitch::class, 'building_id', 'id')->orderBy('name');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
