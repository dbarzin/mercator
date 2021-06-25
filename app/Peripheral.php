<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

/**
 * App\Peripheral
 *
 * @property int $id
 * @property string $name
 * @property string|null $type
 * @property string|null $description
 * @property string|null $responsible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $site_id
 * @property int|null $building_id
 * @property int|null $bay_id
 * @property-read \App\Bay|null $bay
 * @property-read \App\Building|null $building
 * @property-read \App\Site|null $site
 * @method static \Illuminate\Database\Eloquent\Builder|Peripheral newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Peripheral newQuery()
 * @method static \Illuminate\Database\Query\Builder|Peripheral onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Peripheral query()
 * @method static \Illuminate\Database\Eloquent\Builder|Peripheral whereBayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peripheral whereBuildingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peripheral whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peripheral whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peripheral whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peripheral whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peripheral whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peripheral whereResponsible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peripheral whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peripheral whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Peripheral whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Peripheral withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Peripheral withoutTrashed()
 * @mixin \Eloquent
 */
class Peripheral extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'peripherals';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $searchable = [
        'name',
        'type',
        'description',
        'responsible',
    ];

    protected $fillable = [
        'name',
        'type',
        'description',
        'responsible',
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
