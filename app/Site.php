<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Site extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'sites';

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
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function siteBuildings()
    {
        return $this->hasMany(Building::class, 'site_id', 'id');
    }

    public function sitePhysicalServers()
    {
        return $this->hasMany(PhysicalServer::class, 'site_id', 'id');
    }

    public function siteWorkstations()
    {
        return $this->hasMany(Workstation::class, 'site_id', 'id');
    }

    public function siteStorageDevices()
    {
        return $this->hasMany(StorageDevice::class, 'site_id', 'id');
    }

    public function sitePeripherals()
    {
        return $this->hasMany(Peripheral::class, 'site_id', 'id');
    }

    public function sitePhones()
    {
        return $this->hasMany(Phone::class, 'site_id', 'id');
    }

    public function sitePhysicalSwitches()
    {
        return $this->hasMany(PhysicalSwitch::class, 'site_id', 'id');
    }
}
