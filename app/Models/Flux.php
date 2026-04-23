<?php

namespace App\Models;

use App\Contracts\HasPrefix;
use App\Factories\FluxFactory;
use App\Traits\Auditable;
use App\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Flux Applicatif
 */
class Flux extends Model implements HasPrefix
{
    use Auditable, HasFactory, HasUniqueIdentifier, SoftDeletes;

    public $table = 'fluxes';

    public static string $prefix = 'FLOW_';

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
        'nature',
        'attributes',
        'description',
        'application_source_id',
        'service_source_id',
        'module_source_id',
        'database_source_id',
        'application_dest_id',
        'service_dest_id',
        'module_dest_id',
        'database_dest_id',
        'crypted',
        'bidirectional',
    ];

    /**
     * Mapping des champs ID vers les noms de relations pour les sources
     */
    private const SOURCE_RELATIONS = [
        'application_source_id' => 'application_source',
        'service_source_id' => 'service_source',
        'module_source_id' => 'module_source',
        'database_source_id' => 'database_source',
    ];

    /**
     * Mapping des champs ID vers les noms de relations pour les destinations
     */
    private const DEST_RELATIONS = [
        'application_dest_id' => 'application_dest',
        'service_dest_id' => 'service_dest',
        'module_dest_id' => 'module_dest',
        'database_dest_id' => 'database_dest',
    ];

    protected static function newFactory(): Factory
    {
        return FluxFactory::new();
    }

    /* '*~-.,¸¸.-~·*'¨¯'*~-.,¸¸.-~·*'¨¯ UIDs ¯¨'*·~-.¸¸,.-~*''*~-.,¸¸.-~·*'¨¯ */

    /**
     * Retourne l'UID de la source (ex: "APP_42", "SRV_15")
     * Utilise le préfixe statique défini dans chaque modèle
     */
    public function sourceId(): ?string
    {
        return $this->getEntityUID(self::SOURCE_RELATIONS);
    }

    /**
     * Retourne l'UID de la destination (ex: "MOD_8", "DB_23")
     * Utilise le préfixe statique défini dans chaque modèle
     */
    public function destId(): ?string
    {
        return $this->getEntityUID(self::DEST_RELATIONS);
    }
    
    /** @return BelongsToMany<Information, $this> */
    public function informations(): BelongsToMany
    {
        return $this->belongsToMany(Information::class, 'flux_information');
    }

    /* '*~-.,¸¸.-~·*'¨¯'*~-.,¸¸.-~·*'¨¯ Relations ¯¨'*·~-.¸¸,.-~*''*~-.,¸¸.-~·*'¨¯ */

    /** @return BelongsTo<MApplication, $this> */
    public function application_source(): BelongsTo
    {
        return $this->belongsTo(MApplication::class, 'application_source_id');
    }

    /** @return BelongsTo<ApplicationService, $this> */
    public function service_source(): BelongsTo
    {
        return $this->belongsTo(ApplicationService::class, 'service_source_id');
    }

    /** @return BelongsTo<ApplicationModule, $this> */
    public function module_source(): BelongsTo
    {
        return $this->belongsTo(ApplicationModule::class, 'module_source_id');
    }

    /** @return BelongsTo<Database, $this> */
    public function database_source(): BelongsTo
    {
        return $this->belongsTo(Database::class, 'database_source_id');
    }

    /** @return BelongsTo<MApplication, $this> */
    public function application_dest(): BelongsTo
    {
        return $this->belongsTo(MApplication::class, 'application_dest_id');
    }

    /** @return BelongsTo<ApplicationService, $this> */
    public function service_dest(): BelongsTo
    {
        return $this->belongsTo(ApplicationService::class, 'service_dest_id');
    }

    /** @return BelongsTo<ApplicationModule, $this> */
    public function module_dest(): BelongsTo
    {
        return $this->belongsTo(ApplicationModule::class, 'module_dest_id');
    }

    /** @return BelongsTo<Database, $this> */
    public function database_dest(): BelongsTo
    {
        return $this->belongsTo(Database::class, 'database_dest_id');
    }
}