<?php 

require 'inc-constants.php';
require 'app/ws/inc-dbsettings-client.php';
require 'app/ws/inc-dbsettings-server.php';
require 'utils/logging.php'; 
require 'utils/general.php'; 
require 'utils/dates.php'; 
require 'utils/file.php'; 
require 'utils/tasks.php'; 
require 'utils/database.php'; 

//logDebug('test', '');
//dbExport('localhost', 'root', 'passw0rd', 'fms', 'D:/dev/fms-config/config/temp/database/test.dat', false, true);

//deleteFolderContent('D:/dev/fms-config/config/temp', [], true);
//zipFolder('D:/dev/fms-config', 'D:/dev/fms-config/config/temp/application.zip', ["backup", "config", "repository", "temp"], true);
//unzipFile('D:/dev/fms/config/updates/20140602234358-application.zip', 'D:/dev/fms/config/temp/application');

// $strTemp = '';
// foreach(explode('/', 'app/bundle') AS $strFolderName)
// {
	// $strTemp .= $strFolderName . '/';
	// echo($strTemp . "<br>");
// }

//createFolder('d:/t/a', false);

//dbImport("localhost", "root", "passw0rd", "fms", "D:/dev/fms-config/config/temp/database/data.dat");

//$objConn = dbOpen(DBSYSTEMMAIN_HOSTNAME, DBSYSTEMMAIN_LOGIN, DBSYSTEMMAIN_PASSWORD, DBSYSTEMMAIN_DATABASENAME);
//dbExecuteScript($objConn, "D:/dev/fms/config/updates/20140529035731-database/schema.sql");
//$strValue = dbGetSchemaVersion(DBSYSTEMMAIN_HOSTNAME, DBSYSTEMMAIN_LOGIN, DBSYSTEMMAIN_DBSYSTEMMAIN_PASSWORD, DBSYSTEMMAIN_DATABASENAME);
//echo($strValue . "<br>");
//dbClose($objConn);
//echo('finished.');

?>