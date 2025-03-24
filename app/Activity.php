<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Activity
 */
class Activity extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'activities';

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

        'responsible',
        'purpose',
        'categories',
        'recipients',
        'transfert',
        'retention',
        'controls',

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function activitiesProcesses(): BelongsToMany
    {
        return $this->belongsToMany(Process::class)->orderBy('name');
    }

    public function operations(): BelongsToMany
    {
        return $this->belongsToMany(Operation::class)->orderBy('name');
    }
}
