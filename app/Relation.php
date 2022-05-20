<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Relation
 *
 * @property int $id
 * @property int|null $importance
 * @property string $name
 * @property string|null $type
 * @property string|null $description
 * @property string|false $is_hierarchical
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $source_id
 * @property int $destination_id
 *
 * @property-read \App\Entity $destination
 * @property-read \App\Entity $source
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Relation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Relation newQuery()
 * @method static \Illuminate\Database\Query\Builder|Relation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Relation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Relation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Relation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Relation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Relation whereDestinationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Relation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Relation whereInportance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Relation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Relation whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Relation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Relation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Relation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Relation withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Relation extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'relations';

    public static $searchable = [
        'name',
        'type',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'type',
        'description',
        'importance',
        'source_id',
        'destination_id',
        'is_hierarchical',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function source()
    {
        return $this->belongsTo(Entity::class, 'source_id')->orderBy('name');
    }

    public function destination()
    {
        return $this->belongsTo(Entity::class, 'destination_id')->orderBy('name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
