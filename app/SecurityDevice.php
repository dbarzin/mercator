<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\SecurityDevice
 */
class SecurityDevice extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    public $table = 'security_devices';

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

    public function physicalSecurityDevices(): BelongsToMany
    {
        return $this->belongsToMany(PhysicalSecurityDevice::class)->orderBy('name');
    }

}
