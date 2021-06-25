<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

/**
 * App\WifiTerminal
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
 * @property int|null $physical_switch_id
 * @property-read \App\Building|null $building
 * @property-read \App\Site|null $site
 * @method static \Illuminate\Database\Eloquent\Builder|WifiTerminal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WifiTerminal newQuery()
 * @method static \Illuminate\Database\Query\Builder|WifiTerminal onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|WifiTerminal query()
 * @method static \Illuminate\Database\Eloquent\Builder|WifiTerminal whereBuildingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WifiTerminal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WifiTerminal whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WifiTerminal whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WifiTerminal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WifiTerminal whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WifiTerminal wherePhysicalSwitchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WifiTerminal whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WifiTerminal whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WifiTerminal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|WifiTerminal withTrashed()
 * @method static \Illuminate\Database\Query\Builder|WifiTerminal withoutTrashed()
 * @mixin \Eloquent
 */
class WifiTerminal extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'wifi_terminals';

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
        'type',
        'site_id',
        'building_id',
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

}
