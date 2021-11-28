<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Gateway
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $ip
 * @property string|null $authentification
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Subnetwork> $subnetworks
 * @property-read int|null $gateway_subnetworks_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Gateway newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gateway newQuery()
 * @method static \Illuminate\Database\Query\Builder|Gateway onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Gateway query()
 * @method static \Illuminate\Database\Eloquent\Builder|Gateway whereAuthentification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gateway whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gateway whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gateway whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gateway whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gateway whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gateway whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gateway whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Gateway withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Gateway withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Gateway extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'gateways';

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
        'authentification',
        'ip',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function subnetworks()
    {
        return $this->hasMany(Subnetwork::class, 'gateway_id', 'id')->orderBy('name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
