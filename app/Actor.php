<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Actor
 *
 * @property int $id
 * @property string $name
 * @property string|null $nature
 * @property string|null $type
 * @property string|null $contact
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Operation> $operations
 * @property-read int|null $operations_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Actor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Actor newQuery()
 * @method static \Illuminate\Database\Query\Builder|Actor onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Actor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Actor whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Actor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Actor whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Actor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Actor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Actor whereNature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Actor whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Actor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Actor withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Actor withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Actor extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'actors';

    public static $searchable = [
        'name',
        'nature',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'contact',
        'nature',
        'type',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function operations()
    {
        return $this->belongsToMany(Operation::class)->orderBy('name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
