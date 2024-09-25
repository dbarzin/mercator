<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Site
 */
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
        'icon_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function buildings()
    {
        return $this->hasMany(Building::class, 'site_id', 'id')->orderBy('name');
    }

    public function physicalServers()
    {
        return $this->hasMany(PhysicalServer::class, 'site_id', 'id')->orderBy('name');
    }

    public function workstations()
    {
        return $this->hasMany(Workstation::class, 'site_id', 'id')->orderBy('name');
    }

    public function storageDevices()
    {
        return $this->hasMany(StorageDevice::class, 'site_id', 'id')->orderBy('name');
    }

    public function peripherals()
    {
        return $this->hasMany(Peripheral::class, 'site_id', 'id')->orderBy('name');
    }

    public function phones()
    {
        return $this->hasMany(Phone::class, 'site_id', 'id')->orderBy('name');
    }

    public function physicalSwitches()
    {
        return $this->hasMany(PhysicalSwitch::class, 'site_id', 'id')->orderBy('name');
    }

    public function getFillable()
    {
        return $this->fillable;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
