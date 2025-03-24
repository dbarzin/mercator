<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function process() : BelongsTo
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    public function activities() : BelongsToMany
    {
        return $this->belongsToMany(Activity::class)->orderBy('name');
    }

    public function actors() : BelongsToMany
    {
        return $this->belongsToMany(Actor::class)->orderBy('name');
    }

    public function tasks() : BelongsToMany
    {
        return $this->belongsToMany(Task::class)->orderBy('name');
    }

}
