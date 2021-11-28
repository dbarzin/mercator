<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\ApplicationService
 *
 * @property int $id
 * @property string|null $description
 * @property string|null $exposition
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\ApplicationModule> $modules
 * @property-read int|null $modules_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Flux> $serviceDestFluxes
 * @property-read int|null $service_dest_fluxes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Flux> $serviceSourceFluxes
 * @property-read int|null $service_source_fluxes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\MApplication> $servicesMApplications
 * @property-read int|null $services_m_applications_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationService newQuery()
 * @method static \Illuminate\Database\Query\Builder|ApplicationService onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationService query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationService whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationService whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationService whereExposition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationService whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationService whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ApplicationService withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ApplicationService withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ApplicationService extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'application_services';

    public static $searchable = [
        'name',
        'description',
        'exposition',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'exposition',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function serviceSourceFluxes()
    {
        return $this->hasMany(Flux::class, 'service_source_id', 'id')->orderBy('name');
    }

    public function serviceDestFluxes()
    {
        return $this->hasMany(Flux::class, 'service_dest_id', 'id')->orderBy('name');
    }

    public function servicesApplications()
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    public function modules()
    {
        return $this->belongsToMany(ApplicationModule::class)->orderBy('name');
    }

    public function applications()
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
