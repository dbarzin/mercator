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
        return true;
//        return $this->user->groups()->recursive()->exists(
//            'cn=admin_staff,ou=people,dc=planetexpress,dc=com'
//        );
    }
}
