<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\ExternalConnectedEntity
 */
class ExternalConnectedEntity extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'external_connected_entities';

    public static array $searchable = [
        'name',
        'description',
        'contacts',
        'src_desc',
        'dest_desc',
    ];

    protected array $dates = [
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
        'security',
        'network_id',
        'src',
        'dest',
        'src_desc',
        'dest_desc',
    ];

    /** @return BelongsTo<Entity, $this> */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

    /** @return BelongsTo<Network, $this> */
    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class, 'network_id');
    }

    /** @return BelongsToMany<Subnetwork, $this> */
    public function subnetworks(): BelongsToMany
    {
        return $this->belongsToMany(Subnetwork::class)->orderBy('name');
    }

    /** @return BelongsToMany<Document, $this> */
    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class);
    }
}
