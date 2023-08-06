<?php

namespace App;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\PhysicalServer
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $responsible
 * @property string|null $configuration
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $site_id
 * @property int|null $building_id
 * @property int|null $bay_id
 * @property int|null $physical_switch_id
 * @property string|null $type
 *
 * @property-read \App\Bay|null $bay
 * @property-read \App\Building|null $building
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\LogicalServer> $serversLogicalServers
 * @property-read int|null $servers_logical_servers_count
 * @property-read \App\Site|null $site
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalServer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalServer newQuery()
 * @method static \Illuminate\Database\Query\Builder|PhysicalServer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalServer query()
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalServer whereBayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalServer whereBuildingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalServer whereConfiguration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalServer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalServer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalServer whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalServer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalServer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalServer wherePhysicalSwitchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalServer whereResponsible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalServer whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalServer whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PhysicalServer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|PhysicalServer withTrashed()
 * @method static \Illuminate\Database\Query\Builder|PhysicalServer withoutTrashed()
 *
 * @mixin \Eloquent
 */
class PhysicalServer extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'physical_servers';

    public static $searchable = [
        'name',
        'type',
        'description',
        'configuration',
        'responsible',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'type',
        'description',
        'configuration',
        'address_ip',
        'cpu',
        'memory',
        'disk',
        'disk_used',
        'operating_system',
        'install_date',
        'update_date',
        'address_ip',
        'site_id',
        'building_id',
        'bay_id',
        'responsible',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Permet d'exécuter de modifier un attribut avant que la valeurs soit récupérée du model
     */
    public function getInstallDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format').' '.config('panel.time_format')) : null;
    }

    public function setInstallDateAttribute($value)
    {
        $this->attributes['install_date'] = $value ? Carbon::createFromFormat(config('panel.date_format').' '.config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    /**
     * Permet d'exécuter de modifier un attribut avant que la valeurs soit récupérée du model
     */
    public function getUpdateDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format').' '.config('panel.time_format')) : null;
    }

    public function setUpdateDateAttribute($value)
    {
        $this->attributes['update_date'] = $value ? Carbon::createFromFormat(config('panel.date_format').' '.config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function serversLogicalServers()
    {
        return $this->belongsToMany(LogicalServer::class)->orderBy('name');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function bay()
    {
        return $this->belongsTo(Bay::class, 'bay_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
