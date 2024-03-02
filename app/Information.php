<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Information
 */
class Information extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'information';

    public static $searchable = [
        'name',
        'description',
        'owner',
        'constraints',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'owner',
        'administrator',
        'storage',
        'security_need_c',
        'security_need_i',
        'security_need_a',
        'security_need_t',
        'sensitivity',
        'constraints',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function informationsDatabases()
    {
        return $this->belongsToMany(Database::class)->orderBy('name');
    }

    public function processes()
    {
        return $this->belongsToMany(Process::class)->orderBy('identifiant');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
