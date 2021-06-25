<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

/**
 * App\DomaineAd
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int|null $domain_ctrl_cnt
 * @property int|null $user_count
 * @property int|null $machine_count
 * @property string|null $relation_inter_domaine
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ForestAd[] $domainesForestAds
 * @property-read int|null $domaines_forest_ads_count
 * @method static \Illuminate\Database\Eloquent\Builder|DomaineAd newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DomaineAd newQuery()
 * @method static \Illuminate\Database\Query\Builder|DomaineAd onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|DomaineAd query()
 * @method static \Illuminate\Database\Eloquent\Builder|DomaineAd whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DomaineAd whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DomaineAd whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DomaineAd whereDomainCtrlCnt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DomaineAd whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DomaineAd whereMachineCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DomaineAd whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DomaineAd whereRelationInterDomaine($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DomaineAd whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DomaineAd whereUserCount($value)
 * @method static \Illuminate\Database\Query\Builder|DomaineAd withTrashed()
 * @method static \Illuminate\Database\Query\Builder|DomaineAd withoutTrashed()
 * @mixin \Eloquent
 */
class DomaineAd extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'domaine_ads';

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
        'domain_ctrl_cnt',
        'user_count',
        'machine_count',
        'relation_inter_domaine',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    public function domainesForestAds()
    {
        return $this->belongsToMany(ForestAd::class)->orderBy("name");
    }
}
