<?php

namespace App\Models;

use App\Contracts\HasIconContract;
use App\Contracts\HasPrefix;
use App\Contracts\HasUniqueIdentifierContract;
use App\Factories\DhcpServerFactory;
use App\Traits\Auditable;
use App\Traits\HasIcon;
use App\Traits\HasUniqueIdentifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasCartographers;

/**
 * App\DhcpServer
 */
class DhcpServer extends Model implements HasPrefix, HasIconContract, HasUniqueIdentifierContract
{
    use Auditable, HasFactory, HasIcon, HasUniqueIdentifier, SoftDeletes;
    use HasCartographers;

    public $table = 'dhcp_servers';

    public static string $prefix = 'DHCP_';

    public static string $icon = '/images/dhcp.png';

    public static array $searchable = [
        'name',
        'description',
        'address_ip',
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'ext_refs',
        'name',
        'type',
        'attributes',
        'description',
        'address_ip',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function newFactory(): Factory
    {
        return DhcpServerFactory::new();
    }

    /** @param Builder<static> $query */
    public function scopeMaturityLevel2(Builder $query): Builder
    {
        return $query->whereNotNull('description');
    }

}
