<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Operation
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Actor> $actors
 * @property-read int|null $actors_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Activity> $operationsActivities
 * @property-read int|null $operations_activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Task> $tasks
 * @property-read int|null $tasks_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Operation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Operation newQuery()
 * @method static \Illuminate\Database\Query\Builder|Operation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Operation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Operation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Operation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Operation withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Operation extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'operations';

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

    public function operationsActivities()
    {
        return $this->belongsToMany(Activity::class)->orderBy('name');
    }

    public function actors()
    {
        return $this->belongsToMany(Actor::class)->orderBy('name');
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class)->orderBy('nom');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
