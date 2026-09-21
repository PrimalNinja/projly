<?php

// database connection settings
define('DBCLIENT_REQUIREDSCHEMAVERSION', '5');
define('DBCLIENT_ISOLATIONLEVEL', 'READ UNCOMMITTED');

define('DBCLIENT_CREATEDROP', 'TRUE');						// TRUE for wamp, FALSE for ventra
define('DBCLIENT_FILESIZE', 'TRUE');							// TRUE for wamp, FALSE for ventra
define('DBCLIENT_LOADDATA', 'TRUE');							// TRUE for wamp, FALSE for ventra
define('DBCLIENT_LOCALINFILE', 'FALSE');						// TRUE for ventra, FALSE for wamp
define('DBCLIENT_SIMULATEDLOCALOUTFILE', 'FALSE');	// TRUE for ventra, TRUE or FALSE for wamp
define('DBCLIENT_ENGLISHNAME', 'Development');

define('DBCLIENTMAIN_HOSTNAME', 'localhost');
define('DBCLIENTMAIN_LOGIN', 'root');
define('DBCLIENTMAIN_PASSWORD', 'passw0rd');
define('DBCLIENTMAIN_DATABASENAME', 'projly_clientmain');

define('DBCLIENTHISTORY_HOSTNAME', 'localhost');
define('DBCLIENTHISTORY_LOGIN', 'root');
define('DBCLIENTHISTORY_PASSWORD', 'passw0rd');
define('DBCLIENTHISTORY_DATABASENAME', 'projly_clienthistory');

define('DBCLIENTTEMP_HOSTNAME', 'localhost');
define('DBCLIENTTEMP_LOGIN', 'root');
define('DBCLIENTTEMP_PASSWORD', 'passw0rd');
define('DBCLIENTTEMP_DATABASENAME', 'projly_clienttemp');

?>