<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Wan
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Lan> $lans
 * @property-read int|null $lans_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Man> $mans
 * @property-read int|null $mans_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Wan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wan newQuery()
 * @method static \Illuminate\Database\Query\Builder|Wan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Wan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Wan withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Wan withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Wan extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'wans';

    public static $searchable = [
        'name',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function mans()
    {
        return $this->belongsToMany(Man::class)->orderBy('name');
    }

    public function lans()
    {
        return $this->belongsToMany(Lan::class)->orderBy('name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
