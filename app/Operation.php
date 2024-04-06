<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Operation
 */
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
        'process_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class)->orderBy('name');
    }

    public function actors()
    {
        return $this->belongsToMany(Actor::class)->orderBy('name');
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class)->orderBy('name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
