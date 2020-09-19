<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

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

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function moduleSourceFluxes()
    {
        return $this->hasMany(Flux::class, 'module_source_id', 'id');
    }

    public function moduleDestFluxes()
    {
        return $this->hasMany(Flux::class, 'module_dest_id', 'id');
    }

    public function modulesApplicationServices()
    {
        return $this->belongsToMany(ApplicationService::class);
    }
}
