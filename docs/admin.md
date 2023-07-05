## Administration

### User management

Mercator application users are entered in the application database.

Table *users* :

| Field | Type | Description |
|:------------------|:-------------|:-----------------|
| id | int unsigned | auto_increment |
| name | varchar(255) | User name |
| email | varchar(255) | user's email address |
| email_verified_at | datetime | Date email address verified |
| password | varchar(255) | User's password |
| remember_token | varchar(255) | session token |
| granularity | int | Level of granularity used |
| language | varchar(2) | User language |
| created_at | timestamp | Date created |
| updated_at | timestamp | Date updated |
| deleted_at | timestamp | Date of deletion |

Passwords are hashed using Laravel's standard hash function.

Mercator can be connected to an LDAP directory (see LoginController.php). 

### Role management

For each object in the cartography, there is a :

- access
- creation
- edit
- display
- delete

Three roles are encoded by default:

- User: users have full rights to all objects except users and config.
- Auditor: auditors have the right to access and display all objects except users and configuration.
- Administrator: the administrator has all rights without exception.

New roles can be created as required. 

### Certificate management

Certificate expiry notifications can be sent at set times.

The configuration screen can be accessed via the "Configuration" -> "Certificates" menu.

   [<img src="/mercator/images/certificates.png" width="600">](/mercator/images/certificates.png)

The "Test" button sends a test e-mail to the specified notification address.

### CVE management

CVE detection notifications based on application names can be sent at set times.

CVEs are retrieved using the [CVE-Search] project (https://github.com/cve-search/cve-search). The search is performed locally.

The configuration screen can be accessed via the "Configuration" -> "CVE" menu.

   [<img src="/mercator/images/cve.png" width="600">](/mercator/images/cve.png)


The "Test Mail" button sends a test mail to the specified notification address and tests access to the CVE provider.

The "Test Provider" button tests access to the CVE-Search project provider.

