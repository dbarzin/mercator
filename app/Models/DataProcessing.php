<?php

namespace App\Models;

use App\Contracts\HasIconContract;
use App\Contracts\HasPrefix;
use App\Factories\DataProcessingFactory;
use App\Traits\Auditable;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Actor
 */
class DataProcessing extends Model implements HasPrefix, HasIconContract
{
    use Auditable, HasIcon, HasUniqueIdentifier, HasFactory, SoftDeletes;

    public $table = 'data_processing';

    public static string $prefix = 'DATAPROC_';

    public static string $icon = '/images/dataprocessing.png';

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
        'data_source',
        'data_collection_obligation',
        'data_subject_rights',
        'automated_decision_making',
    ];

    protected $casts = [
        'update_date' => 'date:Y-m-d',
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
        'data_source',
        'data_collection_obligation',
        'data_subject_rights',
        'automated_decision_making',
        'update_date'
    ];

    protected static function newFactory(): Factory
    {
        return DataProcessingFactory::new();
    }

    /** @return BelongsToMany<Process, $this> */
    public function processes(): BelongsToMany
    {
        return $this->belongsToMany(Process::class)->orderBy('name');
    }

    /** @return BelongsToMany<Application, $this> */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(Application::class)->orderBy('name');
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
