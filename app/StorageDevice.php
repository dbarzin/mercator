<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\StorageDevice
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $site_id
 * @property int|null $building_id
 * @property int|null $bay_id
 * @property int|null $physical_switch_id
 *
 * @property-read \App\Bay|null $bay
 * @property-read \App\Building|null $building
 * @property-read \App\Site|null $site
 *
 * @method static \Illuminate\Database\Eloquent\Builder|StorageDevice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageDevice newQuery()
 * @method static \Illuminate\Database\Query\Builder|StorageDevice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageDevice query()
 * @method static \Illuminate\Database\Eloquent\Builder|StorageDevice whereBayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageDevice whereBuildingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageDevice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageDevice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageDevice whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageDevice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageDevice whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageDevice wherePhysicalSwitchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageDevice whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StorageDevice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|StorageDevice withTrashed()
 * @method static \Illuminate\Database\Query\Builder|StorageDevice withoutTrashed()
 *
 * @mixin \Eloquent
 */
class StorageDevice extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'storage_devices';

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
        'site_id',
        'building_id',
        'bay_id',
        'physical_switch_id',
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

    public function bay()
    {
        return $this->belongsTo(Bay::class, 'bay_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
