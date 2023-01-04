<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Database
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $responsible
 * @property string|null $type
 * @property int|null $security_need_c
 * @property string|null $external
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $entity_resp_id
 * @property int|null $security_need_i
 * @property int|null $security_need_a
 * @property int|null $security_need_t
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Flux> $databaseDestFluxes
 * @property-read int|null $database_dest_fluxes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Flux> $databaseSourceFluxes
 * @property-read int|null $database_source_fluxes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\MApplication> $databasesMApplications
 * @property-read int|null $databases_m_applications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Entity> $entities
 * @property-read int|null $entities_count
 * @property-read \App\Entity|null $entity_resp
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Information> $informations
 * @property-read int|null $informations_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Database newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Database newQuery()
 * @method static \Illuminate\Database\Query\Builder|Database onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Database query()
 * @method static \Illuminate\Database\Eloquent\Builder|Database whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Database whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Database whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Database whereEntityRespId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Database whereExternal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Database whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Database whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Database whereResponsible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Database whereSecurityNeedA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Database whereSecurityNeedC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Database whereSecurityNeedI($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Database whereSecurityNeedT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Database whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Database whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Database withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Database withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Database extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'databases';

    public static $searchable = [
        'name',
        'description',
        'responsible',
        'type',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'entity_resp_id',
        'responsible',
        'type',
        'security_need_c',
        'security_need_i',
        'security_need_a',
        'security_need_t',
        'external',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function databaseSourceFluxes()
    {
        return $this->hasMany(Flux::class, 'database_source_id', 'id')->orderBy('name');
    }

    public function databaseDestFluxes()
    {
        return $this->hasMany(Flux::class, 'database_dest_id', 'id')->orderBy('name');
    }

    public function applications()
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    public function entities()
    {
        return $this->belongsToMany(Entity::class)->orderBy('name');
    }

    public function entity_resp()
    {
        return $this->belongsTo(Entity::class, 'entity_resp_id');
    }

    public function informations()
    {
        return $this->belongsToMany(Information::class)->orderBy('name');
    }

    public function logicalServers()
    {
        return $this->belongsToMany(LogicalServer::class)->orderBy('name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
