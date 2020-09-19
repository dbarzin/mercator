<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Operation extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'operations';

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

    public function operationsActivities()
    {
        return $this->belongsToMany(Activity::class);
    }

    public function actors()
    {
        return $this->belongsToMany(Actor::class);
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }
}
