<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\ExternalConnectedEntity
 *
 * @property int $id
 * @property string $name
 * @property string|null $responsible_sec
 * @property string|null $contacts
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Network> $connected_networks
 * @property-read int|null $connected_networks_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalConnectedEntity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalConnectedEntity newQuery()
 * @method static \Illuminate\Database\Query\Builder|ExternalConnectedEntity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalConnectedEntity query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalConnectedEntity whereContacts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalConnectedEntity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalConnectedEntity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalConnectedEntity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalConnectedEntity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalConnectedEntity whereResponsibleSec($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalConnectedEntity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ExternalConnectedEntity withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ExternalConnectedEntity withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ExternalConnectedEntity extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'external_connected_entities';

    public static $searchable = [
        'name',
        'description',
        'contacts',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'type',
        'entity_id',
        'contacts',
        'description',
        'network_id',
        'src',
        'dest',
    ];

    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

    public function network()
    {
        return $this->belongsTo(Network::class, 'network_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
