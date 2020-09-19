<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Activity extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'activities';

    public static $searchable = [
        'name',
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

    public function activitiesProcesses()
    {
        return $this->belongsToMany(Process::class);
    }

    public function operations()
    {
        return $this->belongsToMany(Operation::class);
    }
}
