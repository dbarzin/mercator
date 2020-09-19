<?php

namespace App;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class MacroProcessus extends Model 
{
    use SoftDeletes, Auditable;

    public $table = 'macro_processuses';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static $searchable = [
        'name',
        'description',
        'io_elements',
        'owner',
    ];

    protected $fillable = [
        'name',
        'description',
        'io_elements',
        'security_need',
        'owner',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function processes()
    {
        return $this->hasMany(Process::class, 'macroprocess_id', 'id');        
    }
}
