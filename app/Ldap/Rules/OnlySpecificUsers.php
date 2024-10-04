<?php

namespace App\Ldap\Rules;

use LdapRecord\Laravel\Auth\Rule;

class OnlySpecificUsers extends Rule
{
    public function passes(LdapRecord $user, Eloquent $model = null): bool
    {
        if (! config('app.ldap_groups')) {
            return true;
        }

        return $user->groups()->recursive()->exists(
            explode(',', config('app.ldap_groups'))
        );
    }
}
