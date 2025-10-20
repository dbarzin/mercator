<?php


namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\AuditLog
 */
class AuditLog extends Model
{
    use HasFactory;

    public $table = 'audit_logs';

    protected $fillable = [
        'description',
        'subject_id',
        'subject_type',
        'user_id',
        'properties',
        'host',
    ];

    protected $casts = [
        'properties' => 'collection',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function subjectURL(string $subject_type)
    {
        return '/admin/'.
            ($subject_type === 'App\\Models\\MApplication' ?
                'applications' :
                Str::plural(Str::snake(substr($subject_type, 4), '-')));
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
