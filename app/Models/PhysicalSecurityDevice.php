<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\PhysicalSecurityDevice
 */
class PhysicalSecurityDevice extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'physical_security_devices';

    public static $searchable = [
        'name',
        'type',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'type',
        'description',
        'address_ip',
        'site_id',
        'building_id',
        'bay_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function securityDevices(): BelongsToMany
    {
        return $this->belongsToMany(SecurityDevice::class)->orderBy('name');
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function bay(): BelongsTo
    {
        return $this->belongsTo(Bay::class, 'bay_id');
    }
}
