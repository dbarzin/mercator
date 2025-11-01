<?php


namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Bay
 */
class Bay extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'bays';

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
        'room_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /** @return HasMany<PhysicalServer, self> */
    public function bayPhysicalServers(): HasMany
    {
        return $this->hasMany(PhysicalServer::class, 'bay_id', 'id')->orderBy('name');
    }

    /** @return HasMany<StorageDevice, self> */
    public function bayStorageDevices(): HasMany
    {
        return $this->hasMany(StorageDevice::class, 'bay_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Peripheral, self> */
    public function bayPeripherals(): HasMany
    {
        return $this->hasMany(Peripheral::class, 'bay_id', 'id')->orderBy('name');
    }

    /** @return HasMany<PhysicalSwitch, self> */
    public function bayPhysicalSwitches(): HasMany
    {
        return $this->hasMany(PhysicalSwitch::class, 'bay_id', 'id')->orderBy('name');
    }

    /** @return HasMany<PhysicalRouter, self> */
    public function bayPhysicalRouters(): HasMany
    {
        return $this->hasMany(PhysicalRouter::class, 'bay_id', 'id')->orderBy('name');
    }

    /** @return HasMany<PhysicalSecurityDevice, self> */
    public function bayPhysicalSecurityDevices(): HasMany
    {
        return $this->hasMany(PhysicalSecurityDevice::class, 'bay_id', 'id')->orderBy('name');
    }

    /** @return BelongsTo<Building, self> */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'room_id');
    }
}
