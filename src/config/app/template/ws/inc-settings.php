<?php

// server settings
define('ENABLE_REGISTER', 'TRUE');						// true if registration is to be enabled (make sure client and server are both set the same)
define('SESSION_IN_DATABASE', 'TRUE');
define('TIMEZONE', 'Australia/Melbourne');
define('DATE_OUTPUTFORMAT', 'DD/MM/YYYY');								// can be DD/MM/YYYY, MM/DD/YYYY or YYYY/MM/DD
define('DATE_OUTPUTFORMATDESC', 'DD/MM/YYYY');
define('WS_MAX_RUN_TIME', '300');

// ldap
define('LDAP_INITIALISE_USERS', 'TRUE');							// initialise users based on ldap users
define('LDAP_INITIALISE_PROFILES', 'TRUE');							// initialise profiles base on ldap groups
define('LDAP_AUTHENTICE_CLIENTCODE', 'ldaptest');					// ldap authentication client
define('LDAP_AUTHENTICE_USERS', 'FALSE');							// authenticate users within the ldap authentication client
define('LDAP_RECURSE_GROUPS', 'FALSE');								// allow recursive ldap groups
$g_arrLDAPConfig = array('base_dn'=>'DC=domain,DC=local', 'account_suffix'=>'@domain.local');	// can be an empty array

// emails
define('ENABLE_EMAIL', '%ENABLE_EMAIL%');
define('USE_PHP_MAIL', '%USE_PHP_MAIL%');									// TRUE for PHP mail, FALSE for PHP Mailer
define('USE_SENDGRID', '%USE_SENDGRID%');									// TRUE for SendGrid, FALSE for other
define('PHPMAILER_HOSTS', '%PHPMAILER_HOSTS%');	// Specify main and backup server, eg: 'smtp1.example.com;smtp2.example.com'
define('PHPMAILER_AUTH', '%PHPMAILER_AUTH%');									// Enable SMTP authentication
define('PHPMAILER_LOGIN', '%PHPMAILER_LOGIN%');										// SMTP login
define('PHPMAILER_PASSWORD', '%PHPMAILER_PASSWORD%');									// SMTP password
define('PHPMAILER_SECURITY', '%PHPMAILER_SECURITY%');									// Enable encryption, 'tls' or 'ssl' accepted
define('FAILURE_EMAILS_ENABLED', '%FAILURE_EMAILS_ENABLED%');
define('FAIL_EMAIL_DOMAIN', '%FAIL_EMAIL_DOMAIN%');
define('FAIL_EMAIL_ADDRESS', '%FAIL_EMAIL_ADDRESS%');
define('FAIL_EMAIL_ADDRESSTO', '%FAIL_EMAIL_ADDRESSTO%');
define('FAIL_EMAIL_FROMNAME', '%FAIL_EMAIL_FROMNAME%');
define('GENERAL_EMAIL_DOMAIN', '%GENERAL_EMAIL_DOMAIN%');
define('GENERAL_EMAIL_ADDRESS', '%GENERAL_EMAIL_ADDRESS%');
define('GENERAL_EMAIL_FROMNAME', '%GENERAL_EMAIL_FROMNAME%');

// registration
define('IMMEDIATE_REGISTRATION', '%IMMEDIATE_REGISTRATION%');							// TRUE if we don't want to go through the registration email process, false if we want emails
define('SENDVERIFICATIONEMAIL', '%SENDVERIFICATIONEMAIL%');
define('CONFIRMATION_URL', '%CONFIRMATION_URL%');

// batch processing
define('BATCHPROCESSING_ENABLED', 'FALSE');	// if enabled, batchadd will add batch processes, otherwise, it will execute the processes immediately
define('BATCH_MAX_RUN_TIME', '3000');	// 300 = 5 minute timeout

define('BATCH_JOB_RUNS', '1');
define('BATCH_JOBS_PER_RUN', '10');
define('BATCH_PAUSE_BETWEEN_RUNS', '2');	// in seconds

define('BATCH_DATAFORM_LIMIT', '20'); // number of clients at a time of dataform records within a batch session to process

// email processing
define('EMAIL_RUNS', '1');
define('EMAIL_PER_RUN', '10');
define('EMAIL_PAUSE_BETWEEN_RUNS', '2');
define('EMAIL_NEXT_ATTEMPT_TIMEFRAME', '60');	// in seconds

// importing
define('AUTO_DETECT_LINE_ENDINGS', 'TRUE');	// turning this on allows importing to work on Macs

// special areas
define('DEFAULT_CLIENT', 'default');
define('DEVELOPER_CLIENT', 'developer');
define('PUBLIC_CLIENT', 'public');
define('PUBLIC_LOGIN', 'public');

define('SYSTEM_CLIENT', 'system');
define('SYSTEM_LOGIN', 'sysadmin');

define('BATCH_CLIENT', 'batch');
define('BATCH_LOGIN', 'batch');
define('BATCH_PASSWORD', 'passw0rd');

define('DEFAULT_REGISTRATIONTYPE', 'DEFAULT');

// esb timeout
define('ESB_TIMEOUT', '300'); // 300 = 5 minutes timeout

// abn lookup service
define('ABNLOOKUPGUID', 'STICKYOURABNGUIDINHERE');

// debugging
define('DEBUG_PARAMETER_OUTPUT', 'FALSE');		// is additional debug info turned on, no good for APPLEs XML Parser (echos)
define('DEBUG_OUTPUT', 'FALSE');					// is additional debug info turned on, no good for APPLEs XML Parser (echos)
define('DEBUG_LOG', '%DEBUG_LOG%');						// put output into LOG_DEBUG_FILE (log file)
define('DEBUG_LOG_SESSIONINFO', '%DEBUG_LOG_SESSIONINFO%');			// put output into LOG_DEBUG_FILE every time a connection is made (log file)
define('DEBUG_PHPCONSOLE', '%DEBUG_PHPCONSOLE%');				// debug PHP to the Chrome console (only Chrome supported)
define('DEBUG_LOG_IGNORECALLS', 'core_servereventscheck');	// ignore these calls in the logging

?>