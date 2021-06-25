<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

/**
 * App\Site
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Building[] $siteBuildings
 * @property-read int|null $site_buildings_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Peripheral[] $sitePeripherals
 * @property-read int|null $site_peripherals_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Phone[] $sitePhones
 * @property-read int|null $site_phones_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PhysicalServer[] $sitePhysicalServers
 * @property-read int|null $site_physical_servers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PhysicalSwitch[] $sitePhysicalSwitches
 * @property-read int|null $site_physical_switches_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\StorageDevice[] $siteStorageDevices
 * @property-read int|null $site_storage_devices_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Workstation[] $siteWorkstations
 * @property-read int|null $site_workstations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Site newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Site newQuery()
 * @method static \Illuminate\Database\Query\Builder|Site onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Site query()
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Site withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Site withoutTrashed()
 * @mixin \Eloquent
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
        return $this->hasMany(Building::class, 'site_id', 'id')->orderBy('name');
    }

    public function sitePhysicalServers()
    {
        return $this->hasMany(PhysicalServer::class, 'site_id', 'id')->orderBy('name');
    }

    public function siteWorkstations()
    {
        return $this->hasMany(Workstation::class, 'site_id', 'id')->orderBy('name');
    }

    public function siteStorageDevices()
    {
        return $this->hasMany(StorageDevice::class, 'site_id', 'id')->orderBy('name');
    }

    public function sitePeripherals()
    {
        return $this->hasMany(Peripheral::class, 'site_id', 'id')->orderBy('name');
    }

    public function sitePhones()
    {
        return $this->hasMany(Phone::class, 'site_id', 'id')->orderBy('name');
    }

    public function sitePhysicalSwitches()
    {
        return $this->hasMany(PhysicalSwitch::class, 'site_id', 'id')->orderBy('name');
    }
}
