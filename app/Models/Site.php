<?php


namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Site
 */
class Site extends Model
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

    /** @return HasMany<Building, self> */
    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class, 'site_id', 'id')->orderBy('name');
    }

    /** @return HasMany<PhysicalServer, self> */
    public function physicalServers(): HasMany
    {
        return $this->hasMany(PhysicalServer::class, 'site_id', 'id')->orderBy('name');
    }

    /** @return HasMany<PhysicalRouter, self> */
    public function workstations(): HasMany
    {
        return $this->hasMany(Workstation::class, 'site_id', 'id')->orderBy('name');
    }

    /** @return HasMany<PhysicalRouter, self> */
    public function storageDevices(): HasMany
    {
        return $this->hasMany(StorageDevice::class, 'site_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Peripheral, self> */
    public function peripherals(): HasMany
    {
        return $this->hasMany(Peripheral::class, 'site_id', 'id')->orderBy('name');
    }

    /** @return HasMany<WifiTerminal, self> */
    public function phones(): HasMany
    {
        return $this->hasMany(Phone::class, 'site_id', 'id')->orderBy('name');
    }

    /** @return HasMany<WifiTerminal, self> */
    public function physicalSwitches(): HasMany
    {
        return $this->hasMany(PhysicalSwitch::class, 'site_id', 'id')->orderBy('name');
    }
}
