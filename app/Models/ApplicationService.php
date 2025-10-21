<?php


namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\ApplicationService
 */
class ApplicationService extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'application_services';

    public static array $searchable = [
        'name',
        'description',
        'exposition',
    ];

    protected array $dates = [
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

    public function serviceSourceFluxes(): HasMany
    {
        return $this->hasMany(Flux::class, 'service_source_id', 'id')->orderBy('name');
    }

    public function serviceDestFluxes(): HasMany
    {
        return $this->hasMany(Flux::class, 'service_dest_id', 'id')->orderBy('name');
    }

    public function servicesApplications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(ApplicationModule::class)->orderBy('name');
    }

    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }
}
