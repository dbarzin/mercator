<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

/**
 * App\SecurityDevice
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityDevice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityDevice newQuery()
 * @method static \Illuminate\Database\Query\Builder|SecurityDevice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityDevice query()
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityDevice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityDevice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityDevice whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityDevice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityDevice whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityDevice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|SecurityDevice withTrashed()
 * @method static \Illuminate\Database\Query\Builder|SecurityDevice withoutTrashed()
 * @mixin \Eloquent
 */
class SecurityDevice extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'security_devices';

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

}
