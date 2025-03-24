<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\NetworkSwitch
 */
class NetworkSwitch extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'network_switches';

    public static $searchable = [
        'name',
        'description',
        'ip',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'ip',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function physicalSwitches(): BelongsToMany
    {
        return $this->belongsToMany(PhysicalSwitch::class)->orderBy('name');
    }
}
