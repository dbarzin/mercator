<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
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

    public function activitiesProcesses()
    {
        return $this->belongsToMany(Process::class)->orderBy('name');
    }

    public function operations()
    {
        return $this->belongsToMany(Operation::class)->orderBy('name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
