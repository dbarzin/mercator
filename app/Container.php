<?php

namespace App;

use App\MApplication;
use App\LogicalServer;
use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Container
 */
class Container extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'containers';

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
        'type',
        'description',
        'icon_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function applications()
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }

    public function logicalServers()
    {
        return $this->belongsToMany(LogicalServer::class)->orderBy('name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
