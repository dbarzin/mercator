<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Factories\DhcpServerFactory;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUniqueIdentifier;

/**
 * App\DhcpServer
 */
class DhcpServer extends Model
{
    use Auditable, HasFactory, HasUniqueIdentifier, SoftDeletes;

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
        'name',
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

}
