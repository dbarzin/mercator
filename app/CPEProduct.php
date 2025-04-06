<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\CPEProduct
 */
class CPEProduct extends Model
{
    use HasFactory;

    public $table = 'cpe_products';

    public $timestamps = false;

    public static $searchable = [
    ];

    protected $dates = [
    ];

    protected $fillable = [
        'cpe_vendor_id',
        'name',
    ];

    public function versions(): BelongsToMany
    {
        return $this->belongsToMany(CPEVersion::class)->orderBy('name');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(CPEVendor::class, 'cpe_vendor_id');
    }
}
