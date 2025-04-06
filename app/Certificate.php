<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    public $table = 'certificates';

    public static $searchable = [
        'name',
        'description',
        'type',
    ];

    protected $dates = [
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

    public function logical_servers(): BelongsToMany
    {
        return $this->belongsToMany(LogicalServer::class)->orderBy('name');
    }

    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }
}
