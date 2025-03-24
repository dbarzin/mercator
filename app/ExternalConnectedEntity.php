<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'src_desc',
        'dest_desc',
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
        'src_desc',
        'dest_desc',
    ];

    public function entity() : BelongsTo
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

    public function network() : BelongsTo
    {
        return $this->belongsTo(Network::class, 'network_id');
    }

}
