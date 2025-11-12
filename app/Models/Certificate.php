<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'certificates';

    public static array $searchable = [
        'name',
        'description',
        'type',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'type',
        'start_validity',
        'end_validity',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /** @return BelongsToMany<LogicalServer, $this> */
    public function logical_servers(): BelongsToMany
    {
        return $this->belongsToMany(LogicalServer::class)->orderBy('name');
    }

    /** @return BelongsToMany<MApplication, $this> */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }
}
