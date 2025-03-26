<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\DhcpServer
 */
class DhcpServer extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    public $table = 'dhcp_servers';

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
        'name',
        'description',
        'address_ip',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
