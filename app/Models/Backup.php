<?php

namespace App\Models;

use App\Factories\BackupFactory;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Backup extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    protected $table = 'backups';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'logical_server_id',
        'storage_device_id',
        'backup_frequency',
        'backup_cycle',
        'backup_retention',
    ];

    public static array $searchable = [
    ];

    protected $casts = [
        'logical_server_id' => 'integer',
        'storage_device_id' => 'integer',
        'backup_frequency' => 'integer',
        'backup_cycle' => 'integer',
        'backup_retention' => 'integer',
    ];

    protected static function newFactory(): Factory
    {
        return BackupFactory::new();
    }

    /** @return BelongsTo<LogicalServer, $this> */
    // Relations
    public function logicalServer() : BelongsTo
    {
        return $this->belongsTo(LogicalServer::class);
    }

    /** @return BelongsTo<StorageDevice, $this> */
    public function storageDevice() : BelongsTo
    {
        return $this->belongsTo(StorageDevice::class);
    }
}