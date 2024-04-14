<?php

namespace App;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\MApplication
 */
class MApplication extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'm_applications';

    public static $searchable = [
        'name',
        'description',
        'vendor',
        'responsible',
        'editor',
        'functional_referent',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'vendor',
        'product',
        'version',
        'entity_resp_id',
        'functional_referent',
        'editor',
        'technology',
        'documentation',
        'type',
        'users',
        'responsible',
        'security_need_c',
        'security_need_i',
        'security_need_a',
        'security_need_t',
        'external',
        'application_block_id',
        'attributes',
        'patching_frequency',
        'install_date',
        'update_date',
        'next_update',
    ];

    /**
     * Vérifie que l'utilisateur passé en paramètre est cartographe de cette application.
     *
     * @param User $user
     *
     * @return bool
     */
    public function hasCartographer(User $user)
    {
        return $this->cartographers()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Permet d'exécuter de modifier un attribut avant que la valeurs soit récupérée du model
     */
    public function getUpdateDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setUpdateDateAttribute($value)
    {
        $this->attributes['update_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getInstallDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setInstallDateAttribute($value)
    {
        $this->attributes['install_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    /**
     * Permet d'exécuter de modifier un attribut avant que la valeurs soit récupérée du model
     */
    public function getNextUpdateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setNextUpdateAttribute($value)
    {
        // dd($value);
        $this->attributes['next_update'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function applicationSourceFluxes()
    {
        return $this->hasMany(Flux::class, 'application_source_id', 'id')->orderBy('name');
    }

    public function applicationDestFluxes()
    {
        return $this->hasMany(Flux::class, 'application_dest_id', 'id')->orderBy('name');
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

    public function workstations()
    {
        return $this->belongsToMany(Workstation::class)->orderBy('name');
    }

    public function logical_servers()
    {
        return $this->belongsToMany(LogicalServer::class)->orderBy('name');
    }

    public function application_block()
    {
        return $this->belongsTo(ApplicationBlock::class, 'application_block_id');
    }

    public function cartographers()
    {
        return $this->belongsToMany(User::class, 'cartographer_m_application');
    }

    public function events()
    {
        return $this->hasMany(MApplicationEvent::class, 'm_application_id', 'id');
    }

    public function securityControls()
    {
        return $this->belongsToMany(SecurityControl::class, 'security_control_m_application')->orderBy('name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }
}
