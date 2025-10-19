<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    public $table = 'documents';

    public static $searchable = [
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
    ];

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class);
    }

    public function entities(): BelongsToMany
    {
        return $this->belongsToMany(Entity::class);
    }

    public function humanSize()
    {
        return \Illuminate\Support\Number::fileSize($this->size);
        /*
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $bytes = $this->size;
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
        */
    }
}
