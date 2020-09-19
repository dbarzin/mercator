<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

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

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function roomBays()
    {
        return $this->hasMany(Bay::class, 'room_id', 'id');
    }

    public function buildingPhysicalServers()
    {
        return $this->hasMany(PhysicalServer::class, 'building_id', 'id');
    }

    public function buildingWorkstations()
    {
        return $this->hasMany(Workstation::class, 'building_id', 'id');
    }

    public function buildingStorageDevices()
    {
        return $this->hasMany(StorageDevice::class, 'building_id', 'id');
    }

    public function buildingPeripherals()
    {
        return $this->hasMany(Peripheral::class, 'building_id', 'id');
    }

    public function buildingPhones()
    {
        return $this->hasMany(Phone::class, 'building_id', 'id');
    }

    public function buildingPhysicalSwitches()
    {
        return $this->hasMany(PhysicalSwitch::class, 'building_id', 'id');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
