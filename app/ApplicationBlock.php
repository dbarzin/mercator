<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

/**
 * App\ApplicationBlock
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $responsible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MApplication[] $applications
 * @property-read int|null $applications_count
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationBlock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationBlock newQuery()
 * @method static \Illuminate\Database\Query\Builder|ApplicationBlock onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationBlock query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationBlock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationBlock whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationBlock whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationBlock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationBlock whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationBlock whereResponsible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationBlock whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ApplicationBlock withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ApplicationBlock withoutTrashed()
 * @mixin \Eloquent
 */
class ApplicationBlock extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'application_blocks';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $searchable = [
        'name',
        'description',
        'responsible',
    ];

    protected $fillable = [
        'name',
        'description',
        'responsible',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function applications()
    {
        return $this->hasMany(MApplication::class, 'application_block_id', 'id')->orderBy("name");
    }
}
