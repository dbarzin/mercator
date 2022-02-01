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
        //dd($this->user->getDn());
        return true;
        /*return $this->user->groups()->contains([
            'John A. Zoidberg'
        ]);*/
    }
}
