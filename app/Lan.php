<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

/**
 * App\Lan
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Man[] $lansMen
 * @property-read int|null $lans_men_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Wan[] $lansWans
 * @property-read int|null $lans_wans_count
 * @method static \Illuminate\Database\Eloquent\Builder|Lan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lan newQuery()
 * @method static \Illuminate\Database\Query\Builder|Lan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Lan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Lan withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Lan withoutTrashed()
 * @mixin \Eloquent
 */
class Lan extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'lans';

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

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function lansMen()
    {
        return $this->belongsToMany(Man::class)->orderBy("name");
    }

    public function lansWans()
    {
        return $this->belongsToMany(Wan::class)->orderBy("name");
    }
}
