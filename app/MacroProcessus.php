<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\MacroProcessus
 */
class MacroProcessus extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'macro_processuses';

    public static $searchable = [
        'name',
        'description',
        'io_elements',
        'owner',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'io_elements',
        'security_need_c',
        'security_need_i',
        'security_need_a',
        'security_need_t',
        'owner',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function processes()
    {
        return $this->hasMany(Process::class, 'macroprocess_id', 'id')->orderBy('identifiant');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
