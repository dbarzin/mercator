<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Actor
 */
class Actor extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public static array $searchable = [
        'name',
        'nature',
    ];

    public $table = 'actors';

    protected array $dates = [
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

    /** @return BelongsToMany<Operation, $this>
     */
    public function operations(): BelongsToMany
    {
        return $this->belongsToMany(Operation::class)->orderBy('name');
    }
}
