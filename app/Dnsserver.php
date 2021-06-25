<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

/**
 * App\Dnsserver
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Dnsserver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dnsserver newQuery()
 * @method static \Illuminate\Database\Query\Builder|Dnsserver onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Dnsserver query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dnsserver whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dnsserver whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dnsserver whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dnsserver whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dnsserver whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dnsserver whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Dnsserver withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Dnsserver withoutTrashed()
 * @mixin \Eloquent
 */
class Dnsserver extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'dnsservers';

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
