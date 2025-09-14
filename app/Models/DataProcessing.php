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
class DataProcessing extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'data_processing';

    public static $searchable = [
        'name',
        'description',
        'legal_basis',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'legal_basis',
        'description',
        'responsible',
        'purpose',
        'categories',
        'recipients',
        'transfert',
        'retention',
        'controls',
    ];

    public function processes(): BelongsToMany
    {
        return $this->belongsToMany(Process::class)->orderBy('name');
    }

    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    public function informations(): BelongsToMany
    {
        return $this->belongsToMany(Information::class)->orderBy('name');
    }

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class);
    }
}
