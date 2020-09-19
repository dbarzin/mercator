<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class ExternalConnectedEntity extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'external_connected_entities';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $searchable = [
        'name',
        'responsible_sec',
        'contacts',
    ];

    protected $fillable = [
        'name',
        'responsible_sec',
        'contacts',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function connected_networks()
    {
        return $this->belongsToMany(Network::class);
    }
}
