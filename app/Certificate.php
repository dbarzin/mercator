<?php

namespace App;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'certificates';

    public static $searchable = [
        'name',
        'description',
        'type',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'type',
        'start_validity',
        'end_validity',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function logical_servers()
    {
        return $this->belongsToMany(LogicalServer::class)->orderBy('name');
    }

    public function applications()
    {
        return $this->belongsToMany(MApplication::class)->orderBy('name');
    }
}
