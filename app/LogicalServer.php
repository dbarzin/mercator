<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\LogicalServer
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $net_services
 * @property string|null $configuration
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $operating_system
 * @property string|null $address_ip
 * @property string|null $cpu
 * @property string|null $memory
 * @property string|null $environment
 * @property int|null $disk
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\MApplication> $applications
 * @property-read int|null $applications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\PhysicalServer> $servers
 * @property-read int|null $servers_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LogicalServer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LogicalServer newQuery()
 * @method static \Illuminate\Database\Query\Builder|LogicalServer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LogicalServer query()
 * @method static \Illuminate\Database\Eloquent\Builder|LogicalServer whereAddressIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogicalServer whereConfiguration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogicalServer whereCpu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogicalServer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogicalServer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogicalServer whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogicalServer whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogicalServer whereEnvironment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogicalServer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogicalServer whereMemory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogicalServer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogicalServer whereNetServices($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogicalServer whereOperatingSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogicalServer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|LogicalServer withTrashed()
 * @method static \Illuminate\Database\Query\Builder|LogicalServer withoutTrashed()
 *
 * @mixin \Eloquent
 */
class LogicalServer extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'logical_servers';

    public static $searchable = [
        'name',
        'description',
        'configuration',
        'net_services',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'operating_system',
        'address_ip',
        'cpu',
        'memory',
        'disk',
        'environment',
        'net_services',
        'configuration',
        'created_at',
        'updated_at',
        'deleted_at',
        'install_date',
        'update_date'
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

    public function applications()
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    public function servers()
    {
        return $this->belongsToMany(PhysicalServer::class)->orderBy('name');
    }

    public function certificates()
    {
        return $this->belongsToMany(Certificate::class)->orderBy('name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
