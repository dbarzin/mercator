<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

/**
 * App\NetworkSwitch
 *
 * @property int $id
 * @property string $name
 * @property string|null $ip
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkSwitch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkSwitch newQuery()
 * @method static \Illuminate\Database\Query\Builder|NetworkSwitch onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkSwitch query()
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkSwitch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkSwitch whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkSwitch whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkSwitch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkSwitch whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkSwitch whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkSwitch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|NetworkSwitch withTrashed()
 * @method static \Illuminate\Database\Query\Builder|NetworkSwitch withoutTrashed()
 * @mixin \Eloquent
 */
class NetworkSwitch extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'network_switches';

    public static $searchable = [
        'name',
        'description',
        'ip',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'ip',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
