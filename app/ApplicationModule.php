<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\ApplicationModule
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Flux> $moduleDestFluxes
 * @property-read int|null $module_dest_fluxes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Flux> $moduleSourceFluxes
 * @property-read int|null $module_source_fluxes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\ApplicationService> $services
 * @property-read int|null $modules_application_services_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationModule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationModule newQuery()
 * @method static \Illuminate\Database\Query\Builder|ApplicationModule onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationModule query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationModule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationModule whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationModule whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationModule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationModule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationModule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ApplicationModule withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ApplicationModule withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ApplicationModule extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'application_modules';

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

    public function moduleSourceFluxes()
    {
        return $this->hasMany(Flux::class, 'module_source_id', 'id')->orderBy('name');
    }

    public function moduleDestFluxes()
    {
        return $this->hasMany(Flux::class, 'module_dest_id', 'id')->orderBy('name');
    }

    public function applicationServices()
    {
        return $this->belongsToMany(ApplicationService::class)->orderBy('name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
