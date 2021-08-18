<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

/**
 * App\Subnetwork
 *
 * @property int $id
 * @property string|null $description
 * @property string|null $address
 * @property string|null $ip_range
 * @property string|null $ip_allocation_type
 * @property string|null $responsible_exp
 * @property string|null $dmz
 * @property string|null $wifi
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $connected_subnets_id
 * @property int|null $gateway_id
 * @property-read \Illuminate\Database\Eloquent\Collection|Subnetwork[] $connectedSubnetsSubnetworks
 * @property-read int|null $connected_subnets_subnetworks_count
 * @property-read Subnetwork|null $connected_subnets
 * @property-read \App\Gateway|null $gateway
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Network[] $subnetworksNetworks
 * @property-read int|null $subnetworks_networks_count
 * @method static \Illuminate\Database\Eloquent\Builder|Subnetwork newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subnetwork newQuery()
 * @method static \Illuminate\Database\Query\Builder|Subnetwork onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Subnetwork query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subnetwork whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subnetwork whereConnectedSubnetsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subnetwork whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subnetwork whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subnetwork whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subnetwork whereDmz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subnetwork whereGatewayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subnetwork whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subnetwork whereIpAllocationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subnetwork whereIpRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subnetwork whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subnetwork whereResponsibleExp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subnetwork whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subnetwork whereWifi($value)
 * @method static \Illuminate\Database\Query\Builder|Subnetwork withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Subnetwork withoutTrashed()
 * @mixin \Eloquent
 */
class Subnetwork extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'subnetworks';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $searchable = [
        'name',
        'description',        
        'responsible_exp',
    ];

    protected $fillable = [
        'name',
        'description',
        'address',
        'ip_allocation_type',
        'responsible_exp',
        'dmz',
        'wifi', 
        'connected_subnets_id',
        'gateway_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function connectedSubnetsSubnetworks()
    {
        return $this->hasMany(Subnetwork::class, 'connected_subnets_id', 'id')->orderBy("name");
    }

    public function subnetworksNetworks()
    {
        return $this->belongsToMany(Network::class)->orderBy("name");
    }

    public function connected_subnets()
    {
        return $this->belongsTo(Subnetwork::class, 'connected_subnets_id');
    }

    public function gateway()
    {
        return $this->belongsTo(Gateway::class, 'gateway_id');
    }

    public function ipRange() {
        if ($this->address==null)
            return null;        
        $range = array();
        // $cidr = explode('/ ', $this->address);
        $cidr = preg_split('/[ ]?\/[ ]?/', $this->address);
        $range[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
        $range[1] = long2ip((ip2long($range[0])) + pow(2, (32 - (int)$cidr[1])) - 1);    
        return $range[0] . " - " . $range[1];
    }    

    public function contains($ip) {
        if ($ip==null)
            return null;
        if ($this->address==null)
            return null;
        // \Log::info("Subnetwork.contains " . $this->address . " " . $ip);
        $src = ip2long($ip);
        $range = array();        
        // $cidr = explode('/ ', $this->address);
        $cidr = preg_split('/[ ]?\/[ ]?/', $this->address);
        $range[0] = ((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
        $range[1] = ((($range[0])) + pow(2, (32 - (int)$cidr[1])) - 1);    
        \Log::info("Subnetwork.contains " . $src . " [" . $range[0] . " " . $range[1] ."]");
        return ($src>=$range[0]) && ($src<=$range[1]);
    }

}
