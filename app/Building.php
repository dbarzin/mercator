<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Building
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $site_id
 * @property bool|null $camera
 * @property bool|null $badge
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Peripheral> $buildingPeripherals
 * @property-read int|null $building_peripherals_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Phone> $buildingPhones
 * @property-read int|null $building_phones_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\PhysicalServer> $buildingPhysicalServers
 * @property-read int|null $building_physical_servers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\PhysicalSwitch> $buildingPhysicalSwitches
 * @property-read int|null $building_physical_switches_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\StorageDevice> $buildingStorageDevices
 * @property-read int|null $building_storage_devices_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Workstation> $buildingWorkstations
 * @property-read int|null $building_workstations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Bay> $roomBays
 * @property-read int|null $room_bays_count
 * @property-read \App\Site|null $site
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Building newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Building newQuery()
 * @method static \Illuminate\Database\Query\Builder|Building onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Building query()
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereBadge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereCamera($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Building whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Building withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Building withoutTrashed()
 *
 * @mixin \Eloquent
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
