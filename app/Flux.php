<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Flux
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $application_source_id
 * @property int|null $service_source_id
 * @property int|null $module_source_id
 * @property int|null $database_source_id
 * @property int|null $application_dest_id
 * @property int|null $service_dest_id
 * @property int|null $module_dest_id
 * @property int|null $database_dest_id
 * @property int|null $crypted
 *
 * @property-read \App\MApplication|null $application_dest
 * @property-read \App\MApplication|null $application_source
 * @property-read \App\Database|null $database_dest
 * @property-read \App\Database|null $database_source
 * @property-read \App\ApplicationModule|null $module_dest
 * @property-read \App\ApplicationModule|null $module_source
 * @property-read \App\ApplicationService|null $service_dest
 * @property-read \App\ApplicationService|null $service_source
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Flux newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Flux newQuery()
 * @method static \Illuminate\Database\Query\Builder|Flux onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Flux query()
 * @method static \Illuminate\Database\Eloquent\Builder|Flux whereApplicationDestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flux whereApplicationSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flux whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flux whereCrypted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flux whereDatabaseDestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flux whereDatabaseSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flux whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flux whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flux whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flux whereModuleDestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flux whereModuleSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flux whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flux whereServiceDestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flux whereServiceSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Flux whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Flux withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Flux withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Flux extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'fluxes';

    public static $searchable = [
        'name',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
    ];

    public function application_source()
    {
        return $this->belongsTo(MApplication::class, 'application_source_id');
    }

    public function service_source()
    {
        return $this->belongsTo(ApplicationService::class, 'service_source_id');
    }

    public function module_source()
    {
        return $this->belongsTo(ApplicationModule::class, 'module_source_id');
    }

    public function database_source()
    {
        return $this->belongsTo(Database::class, 'database_source_id');
    }

    public function application_dest()
    {
        return $this->belongsTo(MApplication::class, 'application_dest_id');
    }

    public function service_dest()
    {
        return $this->belongsTo(ApplicationService::class, 'service_dest_id');
    }

    public function module_dest()
    {
        return $this->belongsTo(ApplicationModule::class, 'module_dest_id');
    }

    public function database_dest()
    {
        return $this->belongsTo(Database::class, 'database_dest_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
