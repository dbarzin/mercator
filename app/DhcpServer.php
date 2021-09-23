<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\DhcpServer
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|DhcpServer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DhcpServer newQuery()
 * @method static \Illuminate\Database\Query\Builder|DhcpServer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|DhcpServer query()
 * @method static \Illuminate\Database\Eloquent\Builder|DhcpServer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DhcpServer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DhcpServer whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DhcpServer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DhcpServer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DhcpServer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|DhcpServer withTrashed()
 * @method static \Illuminate\Database\Query\Builder|DhcpServer withoutTrashed()
 *
 * @mixin \Eloquent
 */
class DhcpServer extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'dhcp_servers';

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
