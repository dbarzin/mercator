<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Wan
 */
class Wan extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'wans';

    public static array $searchable = [
        'name',
    ];

    protected array $dates = [
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

    /** @return BelongsToMany<Man, $this> */
    public function mans(): BelongsToMany
    {
        return $this->belongsToMany(Man::class)->orderBy('name');
    }

    /** @return BelongsToMany<Lan, $this> */
    public function lans(): BelongsToMany
    {
        return $this->belongsToMany(Lan::class)->orderBy('name');
    }
}
