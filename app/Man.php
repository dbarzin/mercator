<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Man
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Lan> $lans
 * @property-read int|null $lans_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Wan> $mansWans
 * @property-read int|null $mans_wans_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Man newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Man newQuery()
 * @method static \Illuminate\Database\Query\Builder|Man onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Man query()
 * @method static \Illuminate\Database\Eloquent\Builder|Man whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Man whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Man whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Man whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Man whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Man withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Man withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Man extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'mans';

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

    public function mansWans()
    {
        return $this->belongsToMany(Wan::class)->orderBy('name');
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
