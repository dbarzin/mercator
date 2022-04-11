<?php

namespace App\Ldap\Rules;

use LdapRecord\Laravel\Auth\Rule;

class OnlySpecificUsers extends Rule
{
    /**
     * Check if the rule passes validation.
     *
     * @return bool
     */
    public function isValid()
    {
        if(!config('app.ldap_groups')) {
            return true;
        }

        return $this->user->groups()->recursive()->exists(
            explode(",", config('app.ldap_groups'))
        );
    }
}
