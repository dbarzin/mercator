<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Annuaire *
 */
class Annuaire extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    public $table = 'annuaires';

    public static $searchable = [
        'name',
        'description',
        'solution',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'solution',
        'zone_admin_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function zone_admin(): BelongsTo
    {
        return $this->belongsTo(ZoneAdmin::class, 'zone_admin_id');
    }
}
