<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Lan
 */
class Lan extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'lans';

    public static array $searchable = [
        'name',
        'description',
    ];

    protected array $dates = [
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

    /** @return BelongsToMany<Man, $this> */
    public function Mans(): BelongsToMany
    {
        return $this->belongsToMany(Man::class)->orderBy('name');
    }

    /** @return BelongsToMany<Wan, $this> */
    public function Wans(): BelongsToMany
    {
        return $this->belongsToMany(Wan::class)->orderBy('name');
    }
}
