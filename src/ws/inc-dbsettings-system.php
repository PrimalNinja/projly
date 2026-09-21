<?php

// database connection settings
define('DBSYSTEM_REQUIREDSCHEMAVERSION', '5');
define('DBSYSTEM_ISOLATIONLEVEL', 'READ UNCOMMITTED');

define('DBSYSTEM_CREATEDROP', 'TRUE');						// TRUE for wamp, FALSE for ventra
define('DBSYSTEM_FILESIZE', 'TRUE');							// TRUE for wamp, FALSE for ventra
define('DBSYSTEM_LOADDATA', 'TRUE');							// TRUE for wamp, FALSE for ventra
define('DBSYSTEM_LOCALINFILE', 'FALSE');						// TRUE for ventra, FALSE for wamp
define('DBSYSTEM_SIMULATEDLOCALOUTFILE', 'FALSE');	// TRUE for ventra, TRUE or FALSE for wamp
define('DBSYSTEM_ENGLISHNAME', 'Development');

define('DBSYSTEMMAIN_HOSTNAME', 'localhost');
define('DBSYSTEMMAIN_LOGIN', 'root');
define('DBSYSTEMMAIN_PASSWORD', 'passw0rd');
define('DBSYSTEMMAIN_DATABASENAME', 'projly_systemmain');

define('DBSYSTEMHISTORY_HOSTNAME', 'localhost');
define('DBSYSTEMHISTORY_LOGIN', 'root');
define('DBSYSTEMHISTORY_PASSWORD', 'passw0rd');
define('DBSYSTEMHISTORY_DATABASENAME', 'projly_systemhistory');

define('DBSYSTEMTEMP_HOSTNAME', 'localhost');
define('DBSYSTEMTEMP_LOGIN', 'root');
define('DBSYSTEMTEMP_PASSWORD', 'passw0rd');
define('DBSYSTEMTEMP_DATABASENAME', 'projly_systemtemp');

?>