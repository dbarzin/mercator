<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Workstation
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $site_id
 * @property int|null $building_id
 * @property int|null $physical_switch_id
 * @property string|null $type
 *
 * @property-read \App\Building|null $building
 * @property-read \App\Site|null $site
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Workstation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Workstation newQuery()
 * @method static \Illuminate\Database\Query\Builder|Workstation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Workstation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Workstation whereBuildingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workstation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workstation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workstation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workstation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workstation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workstation wherePhysicalSwitchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workstation whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workstation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workstation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Workstation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Workstation withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Workstation extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'workstations';

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
        'site_id',
        'building_id',
        'cpu',
        'memory',
        'disk',
        'operating_system',
        'address_ip',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function applications()
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
