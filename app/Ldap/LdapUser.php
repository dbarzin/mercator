<?php

namespace App\Ldap;

use LdapRecord\Models\ActiveDirectory\User as ActiveDirectoryUser;
use LdapRecord\Models\DirectoryServer\User as DirectoryServerUser;
use LdapRecord\Models\FreeIPA\User as FreeIPAUser;
use LdapRecord\Models\OpenLDAP\User as OpenLdapUser;

switch (strtolower(config('app.ldap_type', 'AD'))) {
    case 'openldap':
        class LdapUser extends OpenLdapUser
        {
        }
        break;
        // no break
    case 'freeipa':
        class LdapUser extends FreeIPAUser
        {
        }
        break;
        // no break
    case 'directoryserver':
        class LdapUser extends DirectoryServerUser
        {
        }
        break;
        // no break
    default:
        class LdapUser extends ActiveDirectoryUser
        {
        }
        break;
}
