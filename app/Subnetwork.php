<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Subnetwork
 */
class Subnetwork extends Model
{
    use SoftDeletes, Auditable;

    public $table = 'subnetworks';

    public static $searchable = [
        'name',
        'description',
        'responsible_exp',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'description',
        'address',
        'default_gateway',
        'ip_allocation_type',
        'vlan_id',
        'zone',
        'dmz',
        'wifi',
        'responsible_exp',
        'gateway_id',
        'network_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function connectedSubnetsSubnetworks()
    {
        return $this->hasMany(Subnetwork::class, 'connected_subnets_id', 'id')->orderBy('name');
    }

    public function network()
    {
        return $this->belongsTo(Network::class, 'network_id');
    }

    public function connected_subnets()
    {
        return $this->belongsTo(Subnetwork::class, 'connected_subnets_id');
    }

    public function gateway()
    {
        return $this->belongsTo(Gateway::class, 'gateway_id');
    }

    public function vlan()
    {
        return $this->belongsTo(Vlan::class, 'vlan_id');
    }

    public function ipRange()
    {
        // no address
        if ($this->address === null) {
            return null;
        }

        // Explode range
        $subnetParts = explode('/', $this->address);

        if (count($subnetParts) < 2) {
            return 'N/A';
        }

        // IPv4
        if (filter_var($subnetParts[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            if ($subnetParts[1] >= 32) {
                return $subnetParts[0] . ' - ' . $subnetParts[0];
            }

            $ip = ip2long($subnetParts[0]);
            $mask = ~1 << 32 - $subnetParts[1] - 1;
            $start = long2ip($ip & $mask);
            $end = long2ip($ip | ~$mask);

            return $start . ' - ' . $end;
        }

        // IPv6
        if (filter_var($subnetParts[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            // Split in address and prefix length
            [$addr_given_str, $prefixlen] = explode('/', $this->address);

            // Parse the address into a binary string
            $addr_given_bin = inet_pton($addr_given_str);

            // Convert the binary string to a string with hexadecimal characters
            $addr_given_hex = bin2hex($addr_given_bin);

            // Overwriting first address string to make sure notation is optimal
            $addr_given_str = inet_ntop($addr_given_bin);

            // Calculate the number of 'flexible' bits
            $flexbits = 128 - $prefixlen;

            // Build the hexadecimal strings of the first and last addresses
            $addr_hex_first = $addr_given_hex;
            $addr_hex_last = $addr_given_hex;

            // We start at the end of the string (which is always 32 characters long)
            $pos = 31;
            while ($flexbits > 0) {
                // Get the characters at this position
                $orig_first = substr($addr_hex_first, $pos, 1);
                $orig_last = substr($addr_hex_last, $pos, 1);

                // Convert them to an integer
                $origval_first = hexdec($orig_first);
                $origval_last = hexdec($orig_last);

                // First address: calculate the subnet mask. min() prevents the comparison from being negative
                $mask = 0xf << min(4, $flexbits);

                // AND the original against its mask
                $new_val_first = $origval_first & $mask;

                // Last address: OR it with (2^flexbits)-1, with flexbits limited to 4 at a time
                $new_val_last = $origval_last | pow(2, min(4, $flexbits)) - 1;

                // Convert them back to hexadecimal characters
                $new_first = dechex($new_val_first);
                $new_last = dechex($new_val_last);

                // And put those character back in their strings
                $addr_hex_first = substr_replace($addr_hex_first, $new_first, $pos, 1);
                $addr_hex_last = substr_replace($addr_hex_last, $new_last, $pos, 1);

                // We processed one nibble, move to previous position
                $flexbits -= 4;
                $pos -= 1;
            }

            // Convert the hexadecimal strings to a binary string
            $addr_bin_first = hex2bin($addr_hex_first);
            $addr_bin_last = hex2bin($addr_hex_last);

            // And create an IPv6 address from the binary string
            $addr_str_first = inet_ntop($addr_bin_first);
            $addr_str_last = inet_ntop($addr_bin_last);

            // Report to user
            return $addr_str_first . ' -> ' . $addr_str_last;
        }

        // Si le subnet n'est ni IPv4 ni IPv6
        return 'N/A';
    }

    /**
     * Does the given IP match the CIDR prefix
     */
    public function contains(string $ip): bool
    {
        $cidr = $this->address;

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

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
