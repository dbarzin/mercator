<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

/**
 * App\Vlan
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $address
 * @property string|null $mask
 * @property string|null $zone
 * @property string|null $gateway
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PhysicalRouter[] $vlanPhysicalRouters
 * @property-read int|null $vlan_physical_routers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Vlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vlan newQuery()
 * @method static \Illuminate\Database\Query\Builder|Vlan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Vlan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vlan whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vlan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vlan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vlan whereGateway($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vlan whereMask($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vlan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vlan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vlan whereZone($value)
 * @method static \Illuminate\Database\Query\Builder|Vlan withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Vlan withoutTrashed()
 * @mixin \Eloquent
 */
class Vlan extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'vlans';

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

    public function vlanPhysicalRouters()
    {
        return $this->belongsToMany(PhysicalRouter::class)->orderBy("name");
    }

    public function subnetworks()
    {
        return $this->hasMany(Subnetwork::class, 'vlan_id', 'id')->orderBy("name");
    }

}
