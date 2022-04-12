<?php

namespace App\Ldap;

use LdapRecord\Models\OpenLDAP\User as OpenLdapUser;
use LdapRecord\Models\FreeIPA\User as FreeIPAUser;
use LdapRecord\Models\ActiveDirectory\User as ActiveDirectoryUser;
use LdapRecord\Models\DirectoryServer\User as DirectoryServerUser;

switch (strtolower(config('app.ldap_type', 'AD'))) {
    case 'openldap':
        class LdapUser extends OpenLdapUser {};
        break;
    case 'freeipa':
        class LdapUser extends FreeIPAUser {};
        break;
    case 'directoryserver':
        class LdapUser extends DirectoryServerUser {};
        break;
    default:
        class LdapUser extends ActiveDirectoryUser {};
        break;
}
