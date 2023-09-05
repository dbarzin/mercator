<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Bay
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
