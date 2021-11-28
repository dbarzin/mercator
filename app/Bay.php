<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Bay
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $room_id
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Peripheral> $bayPeripherals
 * @property-read int|null $bay_peripherals_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\PhysicalRouter> $bayPhysicalRouters
 * @property-read int|null $bay_physical_routers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\PhysicalSecurityDevice> $bayPhysicalSecurityDevices
 * @property-read int|null $bay_physical_security_devices_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\PhysicalServer> $bayPhysicalServers
 * @property-read int|null $bay_physical_servers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\PhysicalSwitch> $bayPhysicalSwitches
 * @property-read int|null $bay_physical_switches_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\StorageDevice> $bayStorageDevices
 * @property-read int|null $bay_storage_devices_count
 * @property-read \App\Building|null $room
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Bay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bay newQuery()
 * @method static \Illuminate\Database\Query\Builder|Bay onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Bay query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bay whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bay whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bay whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bay whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bay whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Bay withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Bay withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Bay extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'bays';

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
        'room_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function bayPhysicalServers()
    {
        return $this->hasMany(PhysicalServer::class, 'bay_id', 'id')->orderBy('name');
    }

    public function bayStorageDevices()
    {
        return $this->hasMany(StorageDevice::class, 'bay_id', 'id')->orderBy('name');
    }

    public function bayPeripherals()
    {
        return $this->hasMany(Peripheral::class, 'bay_id', 'id')->orderBy('name');
    }

    public function bayPhysicalSwitches()
    {
        return $this->hasMany(PhysicalSwitch::class, 'bay_id', 'id')->orderBy('name');
    }

    public function bayPhysicalRouters()
    {
        return $this->hasMany(PhysicalRouter::class, 'bay_id', 'id')->orderBy('name');
    }

    public function bayPhysicalSecurityDevices()
    {
        return $this->hasMany(PhysicalSecurityDevice::class, 'bay_id', 'id')->orderBy('name');
    }

    public function room()
    {
        return $this->belongsTo(Building::class, 'room_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
