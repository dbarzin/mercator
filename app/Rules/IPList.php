<?php

namespace App\Rules;

use Closure;
use const FILTER_FLAG_IPV4;
use const FILTER_FLAG_IPV6;
use const FILTER_VALIDATE_IP;
use Illuminate\Contracts\Validation\ValidationRule;

class IPList implements ValidationRule
{
    /**
     * @param string $attribute The attribute being validated
     * @param mixed $value The current value of the attribute
     * @param Closure $fail Closure to be run in case of failure
     *
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $ipList = explode(',', $value);
        foreach ($ipList as $ip) {
            if (
                // Less restrictive IPv4 filter
                (preg_match_all("/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/i", trim($ip)) === 0) &&
                // (filter_var(trim($ip), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) &&
                (filter_var(trim($ip), FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false)) 
            {
                $fail($this->message());
                return;
            }
            // less restrictiveIPv4 filter
        }
    }

    public function message(): string
    {
        return 'Invalid IP List';
    }
}
