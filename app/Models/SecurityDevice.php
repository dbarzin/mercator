<?php


namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\SecurityDevice
 */
class SecurityDevice extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'security_devices';

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
    ];

    /** @return BelongsToMany<PhysicalSecurityDevice, self> */
    public function physicalSecurityDevices(): BelongsToMany
    {
        return $this->belongsToMany(PhysicalSecurityDevice::class)->orderBy('name');
    }

    /** @return BelongsToMany<MApplication, self> */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }
}
