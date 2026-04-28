<?php

namespace App\Models;

use App\Contracts\HasIconContract;
use App\Contracts\HasPrefix;
use App\Factories\RelationFactory;
use App\Traits\Auditable;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Relation
 */
class Relation extends Model implements HasPrefix, HasIconContract
{
    use Auditable, HasIcon, HasUniqueIdentifier, HasFactory, SoftDeletes;

    public $table = 'relations';

    public static string $prefix = 'RELATION_';

    public static string $icon = '/images/relation.png';

    protected $fillable = [
        'name',
        'type',
        'description',
        'attributes',
        'reference',
        'responsible',
        'order_number',
        'active',
        'start_date',
        'end_date',
        'comments',
        'importance',
        'source_id',
        'destination_id',
        'is_hierarchical',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static array $searchable = [
        'name',
        'type',
        'description',
        'order_number',
        'reference',
        'comments',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function newFactory(): Factory
    {
        return RelationFactory::new();
    }

    /** @return BelongsTo<Entity, $this> */
    public function source(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'source_id')->orderBy('name');
    }

    /** @return BelongsTo<Entity, $this> */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'destination_id')->orderBy('name');
    }

    /** @return BelongsToMany<Document, $this> */
    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class);
    }

    /** @return HasMany<RelationValue, $this> */
    public function values(): HasMany
    {
        return $this->hasMany(RelationValue::class, 'relation_id', 'id')->orderBy('date_price');
    }
}
