<?php return array (
  'cert' => 
  array (
    'mail-from' => 'mercator@localhost',
    'mail-to' => 'helpdesk@localhost',
    'mail-subject' => '[Mercator] Certificate expiration',
    'check-frequency' => '0',
    'expire-delay' => '1',
    'group' => '0',
    'repeat-notification' => '0',
  ),
  'cve' => 
  array (
    'mail-from' => 'mercator@localhost',
    'mail-to' => 'secops@localhost',
    'mail-subject' => '[Mercator] Vulnerability detected',
    'check-frequency' => '1',
    'provider' => 'https://vulnerability.circl.lu',
    'guesser' => 'https://cpe-guesser.cve-search.org',
  ),
  'parameters' => 
  array (
    'security_need_auth' => true,
  ),
  'cpe' => 
  array (
    'guesser' => 'https://cpe-guesser.cve-search.org',
  ),
);