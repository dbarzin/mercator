<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Network
 *
 * @property int $id
 * @property string $name
 * @property string|null $protocol_type
 * @property string|null $responsible
 * @property string|null $responsible_sec
 * @property int|null $security_need
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\ExternalConnectedEntity> $externalConnectedEntities
 * @property-read int|null $connected_networks_external_connected_entities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Subnetwork> $subnetworks
 * @property-read int|null $subnetworks_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Network newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Network newQuery()
 * @method static \Illuminate\Database\Query\Builder|Network onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Network query()
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereProtocolType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereResponsible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereResponsibleSec($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereSecurityNeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Network whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Network withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Network withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Network extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'networks';

    public static $searchable = [
        'name',
        'description',
        'protocol_type',
        'responsible',
        'responsible_sec',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'protocol_type',
        'responsible',
        'responsible_sec',
        'security_need_c',
        'security_need_i',
        'security_need_a',
        'security_need_t',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function externalConnectedEntities()
    {
        return $this->belongsToMany(ExternalConnectedEntity::class)->orderBy('name');
    }

    public function subnetworks()
    {
        return $this->hasMany(Subnetwork::class, 'network_id', 'id')->orderBy('name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
