<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\ApplicationModule
 */
class ApplicationModule extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'application_modules';

    public static array $searchable = [
        'name',
        'description',
    ];

    protected array $dates = [
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

    /** @return HasMany<Flux, $this> */
    public function moduleSourceFluxes(): HasMany
    {
        return $this->hasMany(Flux::class, 'module_source_id', 'id')->orderBy('name');
    }

    /** @return HasMany<Flux, $this> */
    public function moduleDestFluxes(): HasMany
    {
        return $this->hasMany(Flux::class, 'module_dest_id', 'id')->orderBy('name');
    }

    /** @return BelongsToMany<ApplicationService, $this> */
    public function applicationServices(): BelongsToMany
    {
        return $this->belongsToMany(ApplicationService::class)->orderBy('name');
    }
}
