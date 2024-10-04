<?php

namespace App\Ldap\Rules;

use LdapRecord\Laravel\Auth\Rule;

use Illuminate\Database\Eloquent\Model as Eloquent;
use LdapRecord\Models\Model as LdapRecord;

class OnlySpecificUsers implements Rule
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
