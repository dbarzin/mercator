<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

/**
 * App\ZoneAdmin
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Annuaire[] $zoneAdminAnnuaires
 * @property-read int|null $zone_admin_annuaires_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ForestAd[] $zoneAdminForestAds
 * @property-read int|null $zone_admin_forest_ads_count
 * @method static \Illuminate\Database\Eloquent\Builder|ZoneAdmin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ZoneAdmin newQuery()
 * @method static \Illuminate\Database\Query\Builder|ZoneAdmin onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ZoneAdmin query()
 * @method static \Illuminate\Database\Eloquent\Builder|ZoneAdmin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoneAdmin whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoneAdmin whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoneAdmin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoneAdmin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ZoneAdmin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ZoneAdmin withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ZoneAdmin withoutTrashed()
 * @mixin \Eloquent
 */
class ZoneAdmin extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'zone_admins';

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

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function zoneAdminAnnuaires()
    {
        return $this->hasMany(Annuaire::class, 'zone_admin_id', 'id')->orderBy("name");
    }

    public function zoneAdminForestAds()
    {
        return $this->hasMany(ForestAd::class, 'zone_admin_id', 'id')->orderBy("name");
    }
}
