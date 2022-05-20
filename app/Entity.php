<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $security_level
 * @property string|null $contact_point
 * @property string|null $description
 * @property boolean|false $is_external
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Relation> $destinationRelations
 * @property-read int|null $destination_relations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\MApplication> $entitiesMApplications
 * @property-read int|null $entities_m_applications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Process> $entitiesProcesses
 * @property-read int|null $entities_processes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Database> $databases
 * @property-read int|null $entity_resp_databases_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\MApplication> $applications
 * @property-read int|null $entity_resp_m_applications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Relation> $sourceRelations
 * @property-read int|null $source_relations_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Entity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Entity newQuery()
 * @method static \Illuminate\Database\Query\Builder|Entity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Entity query()
 * @method static \Illuminate\Database\Eloquent\Builder|Entity whereContactPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Entity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Entity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Entity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Entity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Entity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Entity whereSecurityLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Entity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Entity withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Entity withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Entity extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'entities';

    public static $searchable = [
        'name',
        'description',
        'security_level',
        'contact_point',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'security_level',
        'contact_point',
        'is_external',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function databases()
    {
        return $this->hasMany(Database::class, 'entity_resp_id', 'id')->orderBy('name');
    }

    public function applications()
    {
        return $this->hasMany(MApplication::class, 'entity_resp_id', 'id')->orderBy('name');
    }

    public function sourceRelations()
    {
        return $this->hasMany(Relation::class, 'source_id', 'id')->orderBy('name');
    }

    public function destinationRelations()
    {
        return $this->hasMany(Relation::class, 'destination_id', 'id')->orderBy('name');
    }

    public function entitiesMApplications()
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    public function entitiesProcesses()
    {
        return $this->belongsToMany(Process::class)->orderBy('identifiant');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
