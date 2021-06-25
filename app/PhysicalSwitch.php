<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

/**
 * App\PhysicalSwitch
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $site_id
 * @property int|null $building_id
 * @property int|null $bay_id
 * @property-read \App\Bay|null $bay
 * @property-read \App\Building|null $building
 * @property-read \App\Site|null $site
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalSwitch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalSwitch newQuery()
 * @method static \Illuminate\Database\Query\Builder|PhysicalSwitch onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalSwitch query()
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalSwitch whereBayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalSwitch whereBuildingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalSwitch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalSwitch whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalSwitch whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalSwitch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalSwitch whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalSwitch whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalSwitch whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalSwitch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|PhysicalSwitch withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PhysicalSwitch withoutTrashed()
 * @mixin \Eloquent
 */
class PhysicalSwitch extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'physical_switches';

    public static $searchable = [
        'name',
        'description',
        'type',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'type',
        'site_id',
        'building_id',
        'bay_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function bay()
    {
        return $this->belongsTo(Bay::class, 'bay_id');
    }
}
