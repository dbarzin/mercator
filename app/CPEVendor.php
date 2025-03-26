<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\CPEVendor
 */
class CPEVendor extends Model
{
    use HasFactory;
    
    public $table = 'cpe_vendors';

    public $timestamps = false;

    public static $searchable = [
    ];

    protected $dates = [
    ];

    protected $fillable = [
        'part',
        'name',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(CPEProduct::class)->orderBy('name');
    }
}
