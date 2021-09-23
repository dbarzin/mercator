<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

/**
 * App\Information
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $owner
 * @property string|null $administrator
 * @property string|null $storage
 * @property int|null $security_need_c
 * @property string|null $sensitivity
 * @property string|null $constraints
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $security_need_i
 * @property int|null $security_need_a
 * @property int|null $security_need_t
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Database[] $informationsDatabases
 * @property-read int|null $informations_databases_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Process[] $processes
 * @property-read int|null $processes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Information newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Information newQuery()
 * @method static \Illuminate\Database\Query\Builder|Information onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Information query()
 * @method static \Illuminate\Database\Eloquent\Builder|Information whereAdministrator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Information whereConstraints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Information whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Information whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Information whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Information whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Information whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Information whereOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Information whereSecurityNeedA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Information whereSecurityNeedC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Information whereSecurityNeedI($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Information whereSecurityNeedT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Information whereSensitivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Information whereStorage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Information whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Information withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Information withoutTrashed()
 * @mixin \Eloquent
 */
class Information extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'information';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $searchable = [
        'name',
        'description',
        'owner',
        'constraints',
    ];

    protected $fillable = [
        'name',
        'description',
        'owner',
        'administrator',
        'storage',
        'security_need_c',
        'security_need_i',
        'security_need_a',
        'security_need_t',
        'sensitivity',
        'constraints',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function informationsDatabases()
    {
        return $this->belongsToMany(Database::class)->orderBy("name");
    }

    public function processes()
    {
        return $this->belongsToMany(Process::class)->orderBy("identifiant");
    }
}
