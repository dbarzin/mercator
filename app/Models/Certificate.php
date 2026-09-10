<?php

namespace App\Models;

use App\Contracts\HasIconContract;
use App\Contracts\HasPrefix;
use App\Contracts\HasUniqueIdentifierContract;
use App\Factories\CertificateFactory;
use App\Traits\Auditable;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCartographers;

class Certificate extends Model implements HasPrefix, HasIconContract, HasUniqueIdentifierContract
{
    use Auditable, HasIcon, HasUniqueIdentifier, HasFactory, SoftDeletes;
    use HasCartographers;

    public $table = 'certificates';

    public static string $prefix = 'CERT_';

    public static string $icon = '/images/certificate.png';

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
        'ext_refs',
        'name',
        'description',
        'type',
        'attributes',
        'start_validity',
        'end_validity',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function newFactory(): Factory
    {
        return CertificateFactory::new();
    }

    /** @return BelongsToMany<LogicalServer, $this> */
    public function logicalServers(): BelongsToMany
    {
        return $this->belongsToMany(LogicalServer::class)->orderBy('name');
    }

    /** @return BelongsToMany<Application, $this> */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(Application::class)->orderBy('name');
    }

    /** @param Builder<static> $query */
    public function scopeMaturityLevel2(Builder $query): Builder
    {
        return $query
            ->whereNotNull('description')
            ->whereNotNull('type')
            ->whereNotNull('start_validity')
            ->whereNotNull('end_validity')
            ->whereExists(fn ($q) => $q
                ->from('certificate_logical_server')
                ->whereColumn('certificate_logical_server.certificate_id', 'certificates.id'));
    }
}
