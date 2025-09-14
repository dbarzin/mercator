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

    public function moduleSourceFluxes(): HasMany
    {
        return $this->hasMany(Flux::class, 'module_source_id', 'id')->orderBy('name');
    }

    public function moduleDestFluxes(): HasMany
    {
        return $this->hasMany(Flux::class, 'module_dest_id', 'id')->orderBy('name');
    }

    public function applicationServices(): BelongsToMany
    {
        return $this->belongsToMany(ApplicationService::class)->orderBy('name');
    }
}
