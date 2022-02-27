<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\MApplication
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int|null $security_need_c
 * @property string|null $responsible
 * @property string|null $functional_referent
 * @property string|null $editor
 * @property string|null $type
 * @property string|null $technology
 * @property string|null $external
 * @property string|null $users
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $entity_resp_id
 * @property int|null $application_block_id
 * @property string|null $documentation
 * @property int|null $security_need_i
 * @property int|null $security_need_a
 * @property int|null $security_need_t
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Flux> $applicationDestFluxes
 * @property-read int|null $application_dest_fluxes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Flux> $applicationSourceFluxes
 * @property-read int|null $application_source_fluxes_count
 * @property-read \App\ApplicationBlock|null $application_block
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Database> $databases
 * @property-read int|null $databases_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Entity> $entities
 * @property-read int|null $entities_count
 * @property-read \App\Entity|null $entity_resp
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\LogicalServer> $logical_servers
 * @property-read int|null $logical_servers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Process> $processes
 * @property-read int|null $processes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\ApplicationService> $services
 * @property-read int|null $services_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\CartographerMApplication> $cartographers
 *
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication newQuery()
 * @method static \Illuminate\Database\Query\Builder|MApplication onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication query()
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication whereApplicationBlockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication whereDocumentation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication whereEntityRespId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication whereExternal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication whereResponsible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication whereSecurityNeedA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication whereSecurityNeedC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication whereSecurityNeedI($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication whereSecurityNeedT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication whereTechnology($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MApplication whereUsers($value)
 * @method static \Illuminate\Database\Query\Builder|MApplication withTrashed()
 * @method static \Illuminate\Database\Query\Builder|MApplication withoutTrashed()
 *
 * @mixin \Eloquent
 */
class MApplication extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'm_applications';

    public static $searchable = [
        'name',
        'description',
        'responsible',
        'functional_referent'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'version',
        'description',
        'entity_resp_id',
        'responsible',
        'functional_referent',
        'editor',
        'technology',
        'documentation',
        'type',
        'users',
        'security_need_c',
        'security_need_i',
        'security_need_a',
        'security_need_t',
        'external',
        'application_block_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function applicationSourceFluxes()
    {
        return $this->hasMany(Flux::class, 'application_source_id', 'id')->orderBy('name');
    }

    public function applicationDestFluxes()
    {
        return $this->hasMany(Flux::class, 'application_dest_id', 'id')->orderBy('name');
    }

    /**
     * Vérifie que l'utilisateur passé en paramètre est cartographe de cette application.
     *
     * @param User $user
     * @return bool
     */
    public function hasCartographer(User $user) {
        return $this->cartographers()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function entities()
    {
        return $this->belongsToMany(Entity::class)->orderBy('name');
    }

    public function entity_resp()
    {
        return $this->belongsTo(Entity::class, 'entity_resp_id');
    }

    public function processes()
    {
        return $this->belongsToMany(Process::class)->orderBy('identifiant');
    }

    public function services()
    {
        return $this->belongsToMany(ApplicationService::class)->orderBy('name');
    }

    public function databases()
    {
        return $this->belongsToMany(Database::class)->orderBy('name');
    }

    public function logical_servers()
    {
        return $this->belongsToMany(LogicalServer::class)->orderBy('name');
    }

    public function application_block()
    {
        return $this->belongsTo(ApplicationBlock::class, 'application_block_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

	public function cartographers()
	{
		return $this->belongsToMany(User::class, 'cartographer_m_application');
	}
}
