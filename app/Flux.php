<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Flux extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'fluxes';

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
        'application_source_id',
        'service_source_id',
        'module_source_id',
        'database_source_id',
        'application_dest_id',
        'service_dest_id',
        'module_dest_id',
        'database_dest_id',
        'crypted',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function application_source()
    {
        return $this->belongsTo(MApplication::class, 'application_source_id');
    }

    public function service_source()
    {
        return $this->belongsTo(ApplicationService::class, 'service_source_id');
    }

    public function module_source()
    {
        return $this->belongsTo(ApplicationModule::class, 'module_source_id');
    }

    public function database_source()
    {
        return $this->belongsTo(Database::class, 'database_source_id');
    }

    public function application_dest()
    {
        return $this->belongsTo(MApplication::class, 'application_dest_id');
    }

    public function service_dest()
    {
        return $this->belongsTo(ApplicationService::class, 'service_dest_id');
    }

    public function module_dest()
    {
        return $this->belongsTo(ApplicationModule::class, 'module_dest_id');
    }

    public function database_dest()
    {
        return $this->belongsTo(Database::class, 'database_dest_id');
    }
}
