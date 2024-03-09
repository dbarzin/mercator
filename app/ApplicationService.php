<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\ApplicationService
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
