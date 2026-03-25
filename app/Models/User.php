<?php

namespace App\Models;

use Laravel\Passport\Contracts\OAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Laravel\Auth\HasLdapUser;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use Mercator\Core\Models\User as CoreUser;

class User extends CoreUser implements LdapAuthenticatable, OAuthenticatable
{
    use AuthenticatesWithLdap, HasLdapUser;
}
