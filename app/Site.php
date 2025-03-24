<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class, 'site_id', 'id')->orderBy('name');
    }

    public function physicalServers(): HasMany
    {
        return $this->hasMany(PhysicalServer::class, 'site_id', 'id')->orderBy('name');
    }

    public function workstations(): HasMany
    {
        return $this->hasMany(Workstation::class, 'site_id', 'id')->orderBy('name');
    }

    public function storageDevices(): HasMany
    {
        return $this->hasMany(StorageDevice::class, 'site_id', 'id')->orderBy('name');
    }

    public function peripherals(): HasMany
    {
        return $this->hasMany(Peripheral::class, 'site_id', 'id')->orderBy('name');
    }

    public function phones(): HasMany
    {
        return $this->hasMany(Phone::class, 'site_id', 'id')->orderBy('name');
    }

    public function physicalSwitches(): HasMany
    {
        return $this->hasMany(PhysicalSwitch::class, 'site_id', 'id')->orderBy('name');
    }
}
