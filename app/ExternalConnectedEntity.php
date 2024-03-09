<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\ExternalConnectedEntity
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
