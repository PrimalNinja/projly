<?php

define('APPNAME', 'Projly');

// installation information (mainly for analytics & licensing)
define('PRODUCT_ID', 'PROJLY01');	// the software's product ID (alphanumeric)
define('INSTALLATION_ID', '1');	// the software's installation ID (numeric version)
define('LICENCEWARNINGDAYS', '30');	// number of days to start warning the user of pending expiry
	
// security (defaults)
define('DEFAULTADMINPROFILE', 'Client Administrator');	// default profile assigned to the newly created client admin
define('DEFAULTADMINLOGIN', 'admin');					// default admin login for new clients
define('DEFAULTADMINPASSWORD', 'passw0rd');				// default password for new clients
define('USE_CLIENTSIDE_SESSIONS', 'FALSE');				// usually is false, unless the client is PHP

// administrator client access controls
define('PROFILEDEFAULTS_SYSTEM', 'CUSTOMER_ADMIN,DEVELOPER_ADMIN,SYSTEM_ADMIN,SYSTEM_DEFAULT_ADMIN,SYSTEM_BATCH_ADMIN,SYSTEM_BATCH_PROCESS,SYSTEM_PUBLIC_ADMIN,SYSTEM_PUBLIC_USER');
define('PROFILEDEFAULTS_PROJLYCLIENT', 'OWNER_ADMIN');
define('PROFILEDEFAULTS_PROFILES', 'PROD_PROJLY,PROD_LICENCEMANAGER,PROD_WORKQUEUES,PROD_REMINDERS');
define('PROFILEDEFAULTS_EXTRA', PROFILEDEFAULTS_PROJLYCLIENT . ',' . PROFILEDEFAULTS_PROFILES);

?>