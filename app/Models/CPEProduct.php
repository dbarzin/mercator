<?php

namespace App\Models;

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

    public static array $searchable = [
    ];

    protected array $dates = [
    ];

    protected $fillable = [
        'cpe_vendor_id',
        'name',
    ];

    /** @return BelongsToMany<CPEVersion, $this> */
    public function versions(): BelongsToMany
    {
        return $this->belongsToMany(CPEVersion::class)->orderBy('name');
    }

    /** @return BelongsTo<CPEVendor, $this> */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(CPEVendor::class, 'cpe_vendor_id');
    }
}
