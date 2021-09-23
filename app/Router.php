<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Router
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $rules
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Router newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Router newQuery()
 * @method static \Illuminate\Database\Query\Builder|Router onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Router query()
 * @method static \Illuminate\Database\Eloquent\Builder|Router whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Router whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Router whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Router whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Router whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Router whereRules($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Router whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Router withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Router withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Router extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'routers';

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
        'rules',
        'ip_addresses',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /*
    public function networkSwitches()
    {
        // TODO: to change
        return $this->hasMany(NetworkSwitches::class, 'router_id', 'id');
    }
    */
}
