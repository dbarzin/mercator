<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class ApplicationService extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'application_services';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $searchable = [
        'name',
        'description',
        'exposition',
    ];

    protected $fillable = [
        'name',
        'description',
        'exposition',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function serviceSourceFluxes()
    {
        return $this->hasMany(Flux::class, 'service_source_id', 'id');
    }

    public function serviceDestFluxes()
    {
        return $this->hasMany(Flux::class, 'service_dest_id', 'id');
    }

    public function servicesMApplications()
    {
        return $this->belongsToMany(MApplication::class);
    }

    public function modules()
    {
        return $this->belongsToMany(ApplicationModule::class);
    }
}
