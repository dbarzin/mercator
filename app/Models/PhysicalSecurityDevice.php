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

    public static array $searchable = [
        'name',
        'type',
        'attributes',
        'description',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'type',
        'attributes',
        'description',
        'icon_id',
        'address_ip',
        'site_id',
        'building_id',
        'bay_id',
    ];

    /** @return BelongsToMany<SecurityDevice, self> */
    public function securityDevices(): BelongsToMany
    {
        return $this->belongsToMany(SecurityDevice::class)->orderBy('name');
    }

    /** @return <Site, self> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    /** @return <Building, self> */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    /** @return <Bay, self> */
    public function bay(): BelongsTo
    {
        return $this->belongsTo(Bay::class, 'bay_id');
    }
}
