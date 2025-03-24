<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Actor
 */
class Actor extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'actors';

    public static $searchable = [
        'name',
        'nature',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'contact',
        'nature',
        'type',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function operations(): BelongsToMany
    {
        return $this->belongsToMany(Operation::class)->orderBy('name');
    }
}
