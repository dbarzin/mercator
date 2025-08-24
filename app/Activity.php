<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Activity
 */
class Activity extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    public $table = 'activities';

    public static $searchable = [
        'name',
        'description',
        'drp'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'responsible',
        'purpose',
        'categories',
        'recipients',
        'transfert',
        'retention',
        'controls',
        'recovery_time_objective',
        'maximum_tolerable_downtime',
        'recovery_point_objective',
        'maximum_tolerable_data_loss',
        'drp',
        'drp_link',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function processes(): BelongsToMany
    {
        return $this->belongsToMany(Process::class)->orderBy('name');
    }

    public function operations(): BelongsToMany
    {
        return $this->belongsToMany(Operation::class)->orderBy('name');
    }

    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    public function impacts(): HasMany
    {
        return $this->hasMany(ActivityImpact::class)->orderBy('impact_type');
    }
}
