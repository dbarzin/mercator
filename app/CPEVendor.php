<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\CPEVendor
 */
class CPEVendor extends Model
{
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
