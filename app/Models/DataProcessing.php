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

    public static array $searchable = [
        'name',
        'description',
        'description',
        'responsible',
        'purpose',
        'categories',
        'recipients',
        'transfert',
        'retention',
        'controls',
        'lawfulness',
    ];

    protected array $dates = [
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
        'lawfulness',
        'lawfulness_legitimate_interest',
        'lawfulness_public_interest',
        'lawfulness_vital_interest',
        'lawfulness_legal_obligation',
        'lawfulness_contract',
        'lawfulness_consent',
    ];

    /** @return BelongsToMany<Process, $this> */
    public function processes(): BelongsToMany
    {
        return $this->belongsToMany(Process::class)->orderBy('name');
    }

    /** @return BelongsToMany<MApplication, $this> */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    /** @return BelongsToMany<Information, $this> */
    public function informations(): BelongsToMany
    {
        return $this->belongsToMany(Information::class)->orderBy('name');
    }

    /** @return BelongsToMany<Document, $this> */
    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class);
    }
}
