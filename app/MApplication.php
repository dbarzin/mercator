<?php

namespace App;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

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
        'attributes',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'install_date',
        'update_date',
        'next_update',
    ];

    protected $fillable = [
        'name',
        'application_block_id',
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
        'security_need_auth',
        'rto',
        'rpo',
        'external',
        'attributes',
        'patching_frequency',
        'install_date',
        'update_date',
        'next_update',
    ];

    public function hasCartographer(User $user)
    {
        return $this->cartographers()
            ->where('user_id', $user->id)
            ->exists();
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
        return $this->belongsToMany(Process::class)->orderBy('name');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class)->orderBy('name');
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

    public function logicalServers()
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
}
