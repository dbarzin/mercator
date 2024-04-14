<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\LogicalFlow
 */
class LogicalFlow extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'logical_flows';

    public static $searchable = [
        'name',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'source_ip_range',
        'dest_ip_range',
        'source_port',
        'dest_port',
        'protocol',
        'description',
    ];

    public function isSource(string $ip): bool
    {
        return $this->contains($this->source_ip_range, $ip);
    }

    public function isDestination(string $ip): bool
    {
        return $this->contains($this->dest_ip_range, $ip);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Does the given IP match the CIDR prefix
     */
    private function contains(string $cidr, string $ip): bool
    {
        if ((str_contains($ip, '.') && str_contains($cidr, '.')) ||
              (str_contains($ip, ':') && str_contains($cidr, ':'))) {
            // Get mask bits
            [$net, $maskBits] = explode('/', $cidr);

            // Size
            $size = strpos($ip, ':') === false ? 4 : 16;

            // Convert to binary
            $ip = inet_pton($ip);
            $net = inet_pton($net);
            if (! $ip || ! $net) {
                return false;
            }

            // Build mask
            $solid = floor($maskBits / 8);
            $solidBits = $solid * 8;
            $mask = str_repeat(chr(255), $solid);
            for ($i = $solidBits; $i < $maskBits; $i += 8) {
                $bits = max(0, min(8, $maskBits - $i));
                $mask .= chr(pow(2, $bits) - 1 << 8 - $bits);
            }
            $mask = str_pad($mask, $size, chr(0));

            // Compare the mask
            return ($ip & $mask) === ($net & $mask);
        }
        return false;
    }
}
