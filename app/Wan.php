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
 * App\Wan
 */
class Wan extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'wans';

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
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function mans() : BelongsToMany
    {
        return $this->belongsToMany(Man::class)->orderBy('name');
    }

    public function lans() : BelongsToMany
    {
        return $this->belongsToMany(Lan::class)->orderBy('name');
    }

}
