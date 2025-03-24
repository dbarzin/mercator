<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Man
 */
class Man extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'mans';

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

    public function mansWans(): BelongsToMany
    {
        return $this->belongsToMany(Wan::class)->orderBy('name');
    }

    public function lans(): BelongsToMany
    {
        return $this->belongsToMany(Lan::class)->orderBy('name');
    }
}
