<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

/**
 * App\ForestAd
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $zone_admin_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DomaineAd[] $domaines
 * @property-read int|null $domaines_count
 * @property-read \App\ZoneAdmin|null $zone_admin
 * @method static \Illuminate\Database\Eloquent\Builder|ForestAd newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ForestAd newQuery()
 * @method static \Illuminate\Database\Query\Builder|ForestAd onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ForestAd query()
 * @method static \Illuminate\Database\Eloquent\Builder|ForestAd whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ForestAd whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ForestAd whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ForestAd whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ForestAd whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ForestAd whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ForestAd whereZoneAdminId($value)
 * @method static \Illuminate\Database\Query\Builder|ForestAd withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ForestAd withoutTrashed()
 * @mixin \Eloquent
 */
class ForestAd extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'forest_ads';

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
        'zone_admin_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function zone_admin()
    {
        return $this->belongsTo(ZoneAdmin::class, 'zone_admin_id');
    }

    public function domaines()
    {
        return $this->belongsToMany(DomaineAd::class)->orderBy("name");
    }
}
