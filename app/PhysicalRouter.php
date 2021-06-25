<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

/**
 * App\PhysicalRouter
 *
 * @property int $id
 * @property string|null $description
 * @property string|null $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $site_id
 * @property int|null $building_id
 * @property int|null $bay_id
 * @property string|null $name
 * @property-read \App\Bay|null $bay
 * @property-read \App\Building|null $building
 * @property-read \App\Site|null $site
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Vlan[] $vlans
 * @property-read int|null $vlans_count
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalRouter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalRouter newQuery()
 * @method static \Illuminate\Database\Query\Builder|PhysicalRouter onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalRouter query()
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalRouter whereBayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalRouter whereBuildingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalRouter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalRouter whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalRouter whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalRouter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalRouter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalRouter whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalRouter whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalRouter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|PhysicalRouter withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PhysicalRouter withoutTrashed()
 * @mixin \Eloquent
 */
class PhysicalRouter extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'physical_routers';

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

    public function vlans()
    {
        return $this->belongsToMany(Vlan::class)->orderBy("name");;
    }
}
