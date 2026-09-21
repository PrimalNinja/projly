<?php

// formerly processTask but getting conflict in name in other processes
function configTask($objTask_a)
{
	$blnResult = false;
	
	$arrFunctions = array(
		"alert" => "taskAlert",
		"archiveFolder" => "taskArchiveFolder",
		"buildNav" => "taskBuildNav",
		"configureApplication" => "taskConfigureApplication",
		"copyFile" => "taskCopyFile",
		"copyFolder" => "taskCopyFolder",
		"createDatabase" => "taskCreateDatabase",
		"createFolder" => "taskCreateFolder",
		"createMetaData" => "taskCreateMetaData",
		"deleteDatabase" => "taskDeleteDatabase",
		"deleteFile" => "taskDeleteFile",
		"deleteFolder" => "taskDeleteFolder",
		"deleteFolderContent" => "taskDeleteFolderContent",
		"executeSQL" => "taskExecuteSQL",
		"exportData" => "taskExportData",
		"exportTable" => "taskExportTable",
		"getExportedFiles" => "taskGetExportedFiles",
		"getSchemaElements" => "taskGetSchemaElements",            
		"importData" => "taskImportData",
		"importFile" => "taskImportFile",
		"importTable" => "taskImportTable",
		"injectFile" => "taskInjectFile",
		"initialise" => "taskInitialise",
		"installOption" => "taskInstallOption",
		"installProfiles" => "taskInstallProfiles",
		"migrateData" => "taskMigrateData",
		"resetTablePermission" => "taskResetTablePermission",
		"set" => "taskSet",
		"setSchema" => "taskSetSchema",
		"nextTaskSet" => "taskNextTaskSet",
		"stop" => "taskStop",
		"unarchive" => "taskUnarchive"
	);

	$strTask = $objTask_a['exec'];
	if (array_key_exists($strTask, $arrFunctions))
	{			
		$blnResult = call_user_func($arrFunctions[$strTask], $objTask_a);

		//echo "processed " . $strTask . " (" . $arrFunctions[$strTask] . ")";
	}
	else
	{
		die('invalid command');
	}
	
	return $blnResult;
}

function addDictionary($strKey_a, $strValue_a)
{
	global $g_arrDictionary;
	$blnFound = false;

	for ($intI = 0; $intI < count($g_arrDictionary); $intI++)
	{
		$strKey = $g_arrDictionary[$intI]['key'];

		if ($strKey == $strKey_a)
		{   
			$g_arrDictionary[$intI]['value'] = $strValue_a;
			$blnFound =  true;
		}
	}

	if (!$blnFound)
	{
		$g_arrDictionary[] = array("key"=>$strKey_a, "value"=>$strValue_a);
	}

	/*
	if (array_key_exists($strKey_a, $g_arrDictionary))
	{
		$g_arrDictionary_a[$strKey_a] = $strValue_a;
	}
	else
	{
		$g_arrDictionary[] = array("key"=>$strKey_a, "value"=>$strValue_a);
	}
	*/
}

function addDefaultDictionary($strKey_a, $strValue_a)
{
	global $g_arrDefaultDictionary;
	
	$blnFound = false;

	for ($intI = 0; $intI < count($g_arrDefaultDictionary); $intI++)
	{
		$strKey = $g_arrDefaultDictionary[$intI]['key'];

		if ($strKey == $strKey_a)
		{   
			$g_arrDefaultDictionary[$intI]['value'] = $strValue_a;
			$blnFound =  true;
		}
	}

	if (!$blnFound)
	{
		$g_arrDefaultDictionary[] = array("key"=>$strKey_a, "value"=>$strValue_a);
	}

	/*
	if (array_key_exists($strKey_a, $g_arrDefaultDictionary))
	{
		$g_arrDefaultDictionary[$strKey_a] = $strValue_a;
	}
	else
	{
		$g_arrDefaultDictionary[] = array("key"=>$strKey_a, "value"=>$strValue_a);
	}
	*/
}

function getFlag($arr_a, $strFlag_a)
{
	$blnResult = false;

	$arrFlags = array();
	if (array_key_exists('flags', $arr_a)) { $arrFlags = $arr_a['flags']; }

	foreach ($arrFlags as $strFlag)
	{
		if ($strFlag == $strFlag_a)
		{
			$blnResult = true;
		}
	}
	
	return $blnResult;
}

// reads the parameter with full dictionary substitution
function replaceDictionary($str_a)
{
	global $g_arrDictionary;
	
	$strResult = $str_a;
	
	for ($intI = 0; $intI < count($g_arrDictionary); $intI++)
	{
		$strKey = $g_arrDictionary[$intI]['key'];
		$strValue = $g_arrDictionary[$intI]['value'];
		$strResult = str_replace($strKey, $strValue, $strResult);
	}
	
	return $strResult;
}

function taskAlert($objTask_a)
{
	global $g_strAlert;
	
	$blnResult = true;

	$strMessage = replaceDictionary(elementString($objTask_a, 'message', ''));
	
	if (strlen($g_strAlert) > 0)
	{
		$g_strAlert .= ", ";
	}
	
	$g_strAlert .= $strMessage;
	
	return $blnResult;
}

// eg: { "exec": "archiveFolder", "folder": "%PATH_APP%", "archive": "%PATH_TEMP%/application.zip", "flags": ["recurse"], "exclude": ["backup", "config", "repository", "temp"] }
// flags: recurse
function taskArchiveFolder($objTask_a)
{
	$strFolder = replaceDictionary(elementString($objTask_a, 'folder', ''));
	$strArchive = replaceDictionary(elementString($objTask_a, 'archive', ''));
	$arrExclude = replaceDictionary(elementArray($objTask_a, 'exclude', array()));
	$blnRecurse = getFlag($objTask_a, 'recurse');
	
	return zipFolder($strFolder, $strArchive, $arrExclude, $blnRecurse);
}

function taskBuildNav($objTask_a)
{
	global $g_arrDictionary;
	$blnResult = false;
	

	$strTableNameSystemBuild = getTableNameEntity("systembuild", false);
	$strTableNameSystemModuleNavItem = getTableNameEntity("systemmodule_navitem", false);
	$strTableNameNavItem = getTableNameEntity("navitem", false);

	$strDatabaseHostname = replaceDictionary($objTask_a['database']['hostname']);
	$strDatabaseDBName = replaceDictionary($objTask_a['database']['dbname']);
	$strDatabaseLogin = replaceDictionary($objTask_a['database']['login']);
	$strDatabasePassword = replaceDictionary($objTask_a['database']['password']);
					
	$strFile = replaceDictionary(elementString($objTask_a, 'file', ''));
	$strSystemModuleID = replaceDictionary(elementString($objTask_a, 'systemmoduleid', ''));
	$strContent = "";

	logDebug('JNXDEBUG', '');
	logDebug(__FILE__ . ':' . __METHOD__, '');
	logDebug(json_encode([$strFile, $strSystemModuleID]), '');
	//logDebug(json_encode([$strDatabaseHostname, $strDatabaseLogin, $strDatabasePassword, $strDatabaseDBName]), '');
	$objConn = dbOpen($strDatabaseHostname, $strDatabaseLogin, $strDatabasePassword, $strDatabaseDBName);

	if ($objConn == null)
	{
		$blnResult = false;
	}
	else
	{            
		if (strlen($strSystemModuleID))
		{
			if (file_exists($strFile))
			{
				$strContent = loadFile($strFile);
			}
			
			$strSQL = "select n.jsondata from ~TABLENAMESYSTEMMODULENAVITEM~ sn, ~TABLENAMENAVITEM~ n where sn.navitem_id=n.id and sn.systemmodule_id = ~SYSTEMMODULEID~";
			$strSQL = str_replace('~TABLENAMESYSTEMMODULENAVITEM~', ff($strTableNameSystemModuleNavItem), $strSQL);
			$strSQL = str_replace('~TABLENAMENAVITEM~', ff($strTableNameNavItem), $strSQL);
			$strSQL = str_replace('~SYSTEMMODULEID~', ff($strSystemModuleID), $strSQL);
			$objResult = dbOpenRecordset($objConn, $strSQL, __FUNCTION__);
			
			$arrTiles = [];

			while ($arrRow = dbReadRecord($objResult))
			{
				$arrJSONData = json_decode($arrRow['jsondata'], true);

				$strParameters = formValueGetBySectionCodeFieldCode($arrJSONData, 'g871c4f64-236d-4e98-939d-8d5a88b7d62b', "PARAMETERS");

				if (strlen($strContent) > 0)
				{
					$strContent .= ',';
				}

				$strContent .= $strParameters;
			}

			dbCloseRecordset($objResult);
						
			saveFile($strFile, $strContent);

			$blnResult = true;
		}
		
		dbClose($objConn, __FUNCTION__);
	}

	return $blnResult;
}

function taskConfigureApplication($objTask_a)
{
	$blnResult = false;

	$strFolder = replaceDictionary(elementString($objTask_a, 'folder', ''));
	$strFile = replaceDictionary(elementString($objTask_a, 'file', ''));
	
	$strConfiguration = "{\"code\":\"\",\"description\":\"\",\"files\":[]}";
	if (file_exists($strFile))
	{
		$strConfiguration = loadFile($strFile);
	}
	$objConfiguration = json_decode($strConfiguration, true);
	
	return configureApplication($strFolder, $objConfiguration);
}

// eg: { "exec": "copyFile", "source": "%PATH_TEMP%/application.zip", "destination": "%PATH_BACKUP%/%FILE_BACKUP%-application.zip", "flags": ["replace"] }
// flags: replace
function taskCopyFile($objTask_a)
{
	$blnResult = false;
	
	$strSource = replaceDictionary(elementString($objTask_a, 'source', ''));
	$strDestination = replaceDictionary(elementString($objTask_a, 'destination', ''));
	$blnReplace = getFlag($objTask_a, 'replace');
	
	if ((is_file($strSource)) && ($blnReplace || !file_exists($strDestination)))
	{
		$blnResult = copyFile($strSource, $strDestination);
	}
	
	return $blnResult;
}


// eg: { "exec": "copyFolder", "source": "%PATH_TEMP%/%APP_NAME%", "destination": "%PATH_APP%", "flags": ["recurse"] }
// flags: recurse, replace
function taskCopyFolder($objTask_a)
{
	$blnResult = false;
	
	$strSource = replaceDictionary(elementString($objTask_a, 'source', ''));
	$strDestination = replaceDictionary(elementString($objTask_a, 'destination', ''));
	$blnRecurse = getFlag($objTask_a, 'recurse');
	
	if (is_dir($strSource) && is_dir($strDestination))
	{
		$blnResult = copyFolder($strSource, $strDestination, $blnRecurse);
	}
	
	return $blnResult;
}

// eg: { "exec": "createDatabase", "database": { "hostname": "localhost", "dbname": "%NEW_DATABASE_APP%", "login": "%LOGIN%", "password": "%PASSWORD%" }, "script": "%PATH_TEMP%/blah/scripts/schema.sql" }
function taskCreateDatabase($objTask_a)
{
	$blnResult = true;
	
	$strDatabaseHostname = replaceDictionary($objTask_a['database']['hostname']);
	$strDatabaseDBName = replaceDictionary($objTask_a['database']['dbname']);
	$strDatabaseLogin = replaceDictionary($objTask_a['database']['login']);
	$strDatabasePassword = replaceDictionary($objTask_a['database']['password']);
	$strScript = replaceDictionary(elementString($objTask_a, 'script', ''));
	
	$objConn = dbCreate($strDatabaseHostname, $strDatabaseLogin, $strDatabasePassword, $strDatabaseDBName, __FUNCTION__);
	if ($objConn == null)
	{
		$blnResult = false;
	}
	else
	{
		if (strlen($strScript) > 0)
		{
			if (file_exists($strScript))
			{
				$blnResult = dbExecuteScript($objConn, $strScript, '', __FUNCTION__);
			}
		}
		dbClose($objConn, __FUNCTION__);
	}
	
	return $blnResult;
}

// eg: { "exec": "createFolder", "folder": "%PATH_TEMP%/application" }
function taskCreateFolder($objTask_a)
{
	$strFolder = replaceDictionary($objTask_a['folder']);
	$blnRecurse = getFlag($objTask_a, 'recurse');
	
	return createFolder($strFolder, $blnRecurse);
}

// eg: { "exec": "createMetaData", "source": "%PATH_APP%/application.json", "destination": "%PATH_TEMP%/application.json", "filename": "%PATH_TEMP%/application.zip", "description": "Application Backup at %TODAY%." }
function taskCreateMetaData($objTask_a)
{
	$strSource = replaceDictionary(elementString($objTask_a, 'source', ''));
	$strDestination = replaceDictionary(elementString($objTask_a, 'destination', ''));
	$strDescription = replaceDictionary(elementString($objTask_a, 'description', ''));
	$strUpdate = replaceDictionary(elementString($objTask_a, 'update', ''));
	$strFilename = replaceDictionary(elementString($objTask_a, 'filename', ''));

	$strMetaData = "{\"update\":\"\",\"date\":\"\",\"version\":\"unknown\",\"dbversion\":\"unknown\",\"build\":\"\",\"description\":\"\",\"filename\":\"\",\"url\":\"\"}";
	if (file_exists($strSource))
	{
		$strMetaData = loadFile($strSource);
	}
	$objMetaData = json_decode($strMetaData, true);
	$objMetaData["update"] = $strUpdate;
	$objMetaData["date"] = getToday();
	$objMetaData["description"] = $strDescription;
	$objMetaData["filename"] = $strFilename;
	$strMetaData = json_encode($objMetaData);
	saveFile($strDestination, $strMetaData);

	return true;
}

// eg: { "exec": "deleteDatabase", "database": { "hostname": "localhost", "dbname": "%CURRENT_DATABASE_APP%", "login": "%LOGIN%", "password": "%PASSWORD%" } }
function taskDeleteDatabase($objTask_a)
{
	$strDatabaseHostname = replaceDictionary($objTask_a['database']['hostname']);
	$strDatabaseDBName = replaceDictionary($objTask_a['database']['dbname']);
	$strDatabaseLogin = replaceDictionary($objTask_a['database']['login']);
	$strDatabasePassword = replaceDictionary($objTask_a['database']['password']);
	
	return dbDrop($strDatabaseHostname, $strDatabaseLogin, $strDatabasePassword, $strDatabaseDBName, __FUNCTION__);
}

// eg: { "exec": "deleteFile", "file": "%PATH_APP%/database.json" }
function taskDeleteFile($objTask_a)
{
	$strFile = replaceDictionary(elementString($objTask_a, 'file', ''));
	
	return deleteFile($strFile);
}

// eg: { "exec": "deleteFolder", "folder": "%PATH_APP%", "flags": ["recurse"] }
// flags: recurse
function taskDeleteFolder($objTask_a)
{
	$strFolder = replaceDictionary(elementString($objTask_a, 'folder', ''));
	$blnRecurse = getFlag($objTask_a, 'recurse');
	
	return deleteFolder($strFolder, $blnRecurse);
}

// eg: { "exec": "deleteFolderContent", "folder": "%PATH_APP%", "flags": ["recurse"], "exclude": ["backup", "config", "repository", "temp"] }
// flags: recurse
function taskDeleteFolderContent($objTask_a)
{
	$strFolder = replaceDictionary(elementString($objTask_a, 'folder', ''));
	$arrExclude = replaceDictionary(elementArray($objTask_a, 'exclude', array()));
	$blnRecurse = getFlag($objTask_a, 'recurse');
	
	return deleteFolderContent($strFolder, $arrExclude, $blnRecurse);
}

// eg: { "exec": "executeSQL", "database": { "hostname": "%DBHOSTNAME%", "dbname": "%DBNAME%", "login": "%DBLOGIN%", "password": "%DBPASSWORD%" }, "script": "%SYS_PATH_CONFIG%/sql/package.sql" }
function taskExecuteSQL($objTask_a)
{
	$blnResult = true;
	
	$strDatabaseHostname = replaceDictionary($objTask_a['database']['hostname']);
	$strDatabaseDBName = replaceDictionary($objTask_a['database']['dbname']);
	$strDatabaseLogin = replaceDictionary($objTask_a['database']['login']);
	$strDatabasePassword = replaceDictionary($objTask_a['database']['password']);
	$strScript = replaceDictionary(elementString($objTask_a, 'script', ''));
	
	$objConn = dbOpen($strDatabaseHostname, $strDatabaseLogin, $strDatabasePassword, $strDatabaseDBName, __FUNCTION__);
	if ($objConn == null)
	{
		$blnResult = false;
	}
	else
	{
		if (strlen($strScript) > 0)
		{
			if (file_exists($strScript))
			{
				$blnResult = dbExecuteScript($objConn, $strScript, '', __FUNCTION__);
			}
		}
		dbClose($objConn, __FUNCTION__);
	}
	
	return $blnResult;
}

// eg: { "exec": "exportData", "database": { "hostname": "localhost", "dbname": "%CURRENT_DATABASE_APP%", "login": "%LOGIN%", "password": "%PASSWORD%", "chunksize": 1000 }, "target": "%PATH_TEMP%/database/schema.sql", "flags": ["schema"] }
// flags: data, schema
function taskExportData($objTask_a)
{
	$intChunk = 0;
	$intChunkOf = 0;

	$strDatabaseHostname = replaceDictionary($objTask_a['database']['hostname']);
	$strDatabaseDBName = replaceDictionary($objTask_a['database']['dbname']);
	$strDatabaseLogin = replaceDictionary($objTask_a['database']['login']);
	$strDatabasePassword = replaceDictionary($objTask_a['database']['password']);

	if (isset($objTask_a['chunk']))
	{
		$intChunk = intval($objTask_a['chunk'], 10);
	}

	if (isset($objTask_a['chunkof']))
	{
		$intChunkOf = intval($objTask_a['chunkof'], 10);
	}

	$intChunkSize = intval($objTask_a['chunksize'], 10);
	$strTarget = replaceDictionary(elementString($objTask_a, 'target', ''));
	$blnData = getFlag($objTask_a, 'data');
	$blnSchema = getFlag($objTask_a, 'schema');
	
	return dbExport($strDatabaseHostname, $strDatabaseLogin, $strDatabasePassword, $strDatabaseDBName, $strTarget, $blnSchema, $blnData, $intChunk, $intChunkOf, $intChunkSize, __FUNCTION__);
}

function taskGetExportedFiles($objTask_a)
{
	//$strDatabaseHostname = replaceDictionary($objTask_a['database']['hostname']);
	//$strDatabaseDBName = replaceDictionary($objTask_a['database']['dbname']);
	//$strDatabaseLogin = replaceDictionary($objTask_a['database']['login']);
	//$strDatabasePassword = replaceDictionary($objTask_a['database']['password']);
	$strSource = replaceDictionary($objTask_a['source']);		
	$strType = replaceDictionary($objTask_a['type']);		
	
	return dbGetExportedFiles($strSource, $strType, __FUNCTION__);
}

// eg: { "exec": "exportTable", "database": { "hostname": "localhost", "dbname": "%CURRENT_DATABASE_APP%", "login": "%LOGIN%", "password": "%PASSWORD%", "chunksize": 1000 }, "tablename": "%TABLENAME%", "target": "%PATH_TEMP%/database/schema.sql", "flags": ["schema"] }
// flags: data, schema
function taskExportTable($objTask_a)
{
	$strDatabaseHostname = replaceDictionary($objTask_a['database']['hostname']);
	$strDatabaseDBName = replaceDictionary($objTask_a['database']['dbname']);
	$strDatabaseLogin = replaceDictionary($objTask_a['database']['login']);
	$strDatabasePassword = replaceDictionary($objTask_a['database']['password']);
	$intChunk = intval($objTask_a['chunk'], 10);
	$intChunkOf = intval($objTask_a['chunkof'], 10);
	$intChunkSize = intval($objTask_a['chunksize'], 10);
	$strTableName = replaceDictionary(elementString($objTask_a, 'tablename', ''));
	$strTarget = replaceDictionary(elementString($objTask_a, 'target', ''));
	$blnData = getFlag($objTask_a, 'data');
	$blnSchema = getFlag($objTask_a, 'schema');
	
	return dbExportTable($strDatabaseHostname, $strDatabaseLogin, $strDatabasePassword, $strDatabaseDBName, $strTableName, $strTarget, $blnSchema, $blnData, $intChunk, $intChunkOf, $intChunkSize, __FUNCTION__);
}

function taskGetSchemaElements($objTask_a)
{
	$strDatabaseHostname = replaceDictionary($objTask_a['database']['hostname']);
	$strDatabaseDBName = replaceDictionary($objTask_a['database']['dbname']);
	$strDatabaseLogin = replaceDictionary($objTask_a['database']['login']);
	$strDatabasePassword = replaceDictionary($objTask_a['database']['password']);
	
	return dbGetSchemaElements($strDatabaseHostname, $strDatabaseLogin, $strDatabasePassword, $strDatabaseDBName, __FUNCTION__);
}

// eg: { "exec": "importData", "database": { "hostname": "localhost", "dbname": "%NEW_DATABASE_TEMP%", "login": "%LOGIN%", "password": "%PASSWORD%" }, "source": "%PATH_TEMP%/blah/scripts/schema_temp.sql" }
function taskImportData($objTask_a)
{
	$strDatabaseHostname = replaceDictionary($objTask_a['database']['hostname']);
	$strDatabaseDBName = replaceDictionary($objTask_a['database']['dbname']);
	$strDatabaseLogin = replaceDictionary($objTask_a['database']['login']);
	$strDatabasePassword = replaceDictionary($objTask_a['database']['password']);
	$strSource = replaceDictionary($objTask_a['source']);

	return dbImport($strDatabaseHostname, $strDatabaseLogin, $strDatabasePassword, $strDatabaseDBName, $strSource, __FUNCTION__);
}

// eg: { "exec": "importFile", "database": { "hostname": "localhost", "dbname": "%NEW_DATABASE_TEMP%", "login": "%LOGIN%", "password": "%PASSWORD%" }, "table": "datafile", "source": "%PATH_TEMP%/blah/datafile.dat" }
function taskImportFile($objTask_a)
{
	$strDatabaseHostname = replaceDictionary($objTask_a['database']['hostname']);
	$strDatabaseDBName = replaceDictionary($objTask_a['database']['dbname']);
	$strDatabaseLogin = replaceDictionary($objTask_a['database']['login']);
	$strDatabasePassword = replaceDictionary($objTask_a['database']['password']);
	$strTableName = replaceDictionary(elementString($objTask_a, 'tablename', ''));
	$strSource = replaceDictionary($objTask_a['source']);

	return dbImportFile($strDatabaseHostname, $strDatabaseLogin, $strDatabasePassword, $strDatabaseDBName, $strTableName, $strSource, __FUNCTION__);
}

// eg: { "exec": "importTable", "database": { "hostname": "localhost", "dbname": "%NEW_DATABASE_TEMP%", "login": "%LOGIN%", "password": "%PASSWORD%" }, "tablename": "%TABLENAME%", "source": "%PATH_TEMP%/blah/scripts/schema_temp.sql" }
function taskImportTable($objTask_a)
{
	$strDatabaseHostname = replaceDictionary($objTask_a['database']['hostname']);
	$strDatabaseDBName = replaceDictionary($objTask_a['database']['dbname']);
	$strDatabaseLogin = replaceDictionary($objTask_a['database']['login']);
	$strDatabasePassword = replaceDictionary($objTask_a['database']['password']);
	$strTableName = replaceDictionary(elementString($objTask_a, 'tablename', ''));
	$strSource = replaceDictionary($objTask_a['source']);

	return dbImportTable($strDatabaseHostname, $strDatabaseLogin, $strDatabasePassword, $strDatabaseDBName, $strTableName, $strSource, __FUNCTION__);
}

// clear the dictionary
function taskInitialise($objTask_a)
{
	global $g_arrDefaultDictionary;
	global $g_arrDictionary;
	
	$g_arrDictionary = array();
	$g_arrDictionary = array_merge($g_arrDefaultDictionary, $g_arrDictionary);
	deleteFile("dictionary.json");
	saveFile("dictionary.json", json_encode($g_arrDictionary));
}

function taskInjectFile($objTask_a)
{
	$strSourceFile = replaceDictionary($objTask_a['source']);    
	$strDestinationFile = replaceDictionary($objTask_a['destination']);    
	$strPlaceholder = replaceDictionary($objTask_a['placeholder']);    

	$strSourceContent = loadFile($strSourceFile);
	$strContent = loadFile($strDestinationFile);

	$strContent = str_replace($strPlaceholder, $strSourceContent, $strContent);

	saveFile($strDestinationFile, $strContent);

	$strContent = loadFile($strDestinationFile);

	return true;
}

// install option
function taskInstallOption($objTask_a)
{		
	require_once( "utils/entity.php" );
	require_once( "utils/forms.php" );
	require_once( "utils/formtools.php" );
	require_once( "utils/reports.php" );

	$blnResult = false;

	$strTableNameConfig = getTableNameEntity("config", false);
	$strTableNameConfigTask = getTableNameEntity("configtask", false);

	$strDatabaseHostname = replaceDictionary($objTask_a['database']['hostname']);
	$strDatabaseDBName = replaceDictionary($objTask_a['database']['dbname']);
	$strDatabaseLogin = replaceDictionary($objTask_a['database']['login']);
	$strDatabasePassword = replaceDictionary($objTask_a['database']['password']);

	$objConn = dbOpen($strDatabaseHostname, $strDatabaseLogin, $strDatabasePassword, $strDatabaseDBName, __FUNCTION__);

	$strTaskCode = $objTask_a['code'];
	$strConfigFile = replaceDictionary(elementString($objTask_a, 'file', ''));

	$strConfigJSON = file_get_contents($strConfigFile);

	$arrConfigJSON = json_decode($strConfigJSON, true);
	
	$arrTaskOptions = [];

	foreach ($arrConfigJSON['optionSets'] as $arrConfig)
	{
		$arrOptions = $arrConfig['options'];

		foreach ($arrOptions as $arrOption)
		{
			if ($arrOption['code'] === $strTaskCode)
			{
				$arrTaskOptions[] = [
					'code' => $arrOption['code'],
					'description' => $arrOption['description'],
					'taskSets' => $arrOption['taskSets']
				];
			}
		}
	}

	if ($objConn)
	{
		$strClientID = getSystemClientID($objConn); //$_SESSION['server_loggedin_clientid'];
		$strLogin = ""; //$_SESSION['server_loggedin_user'];

		$strEntityID = getEntityID($objConn, "systemform");

		foreach ($arrTaskOptions as $arrTaskOption)
		{
			$strCode = $arrTaskOption['code'];
			$strDescription = $arrTaskOption['description'];
			$arrJSONData = formTemplateGetFromDBByEntityCode($objConn, "CONFIG");			
			
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb018840e-bc4f-45fc-b8a6-2c6338ca1be1', "CODE", $strCode);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb018840e-bc4f-45fc-b8a6-2c6338ca1be1', "DESCRIPTION", $strDescription);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb018840e-bc4f-45fc-b8a6-2c6338ca1be1', "IS_PROCESSED", "N");
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb018840e-bc4f-45fc-b8a6-2c6338ca1be1', "IS_READY", "N");
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb018840e-bc4f-45fc-b8a6-2c6338ca1be1', "STARTTIME", "");
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb018840e-bc4f-45fc-b8a6-2c6338ca1be1', "ENDTIME", "");
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb018840e-bc4f-45fc-b8a6-2c6338ca1be1', "SORTORDER", "");
			$strJSONData = json_encode($arrJSONData);

			$strSQL = "insert into ~TABLENAMECONFIG~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime) values(~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~')";
			$strSQL = str_replace('~TABLENAMECONFIG~', ff($strTableNameConfig), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
			$strSQL = str_replace('~CODE~', $strCode, $strSQL);
			$strSQL = str_replace('~DESCRIPTION~', $strDescription, $strSQL);
			$strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
			$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
			$strSQL = str_replace('~DATAENTITYID~', ff(getEntityID($objConn, "config")), $strSQL);
			$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
			$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
			$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
			dbExecuteSQL($objConn, $strSQL, __FUNCTION__);
			$strConfigID = dbLastInsertID($objConn, __FUNCTION__);
			
			exposeEntityData($objConn, 'SYSTEMFORM', 'CONFIG', $strConfigID, $strJSONData);
			
			// to be use later below
			$arrConfigJSONData = json_decode($strJSONData, true);

			$arrConfigTaskSets = $arrConfigJSON['taskSets'];

			foreach ($arrOption['taskSets'] as $strTaskSetCode)
			{
				if (isset($arrConfigTaskSets[$strTaskSetCode]))
				{	
					$arrTaskSet = $arrConfigTaskSets[$strTaskSetCode];
					
					$strCode = $strTaskSetCode;
					$strDescription = $arrTaskSet['description'];
					$arrTaskSetTasks = $arrTaskSet['tasks'];

					foreach ($arrTaskSetTasks as $arrTask)
					{
						$strCommand = $arrTask['exec'];
						$strParameters = json_encode($arrTask, JSON_PRETTY_PRINT);

						$arrJSONData = formTemplateGetFromDBByEntityCode($objConn, "CONFIGTASK");

						$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g15aeaa60-835c-4974-87c5-e82ec903629f', "CODE", $strCode);
						$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g15aeaa60-835c-4974-87c5-e82ec903629f', "DESCRIPTION", $strDescription);
						$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g15aeaa60-835c-4974-87c5-e82ec903629f', "COMMAND", $strCommand);
						$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g15aeaa60-835c-4974-87c5-e82ec903629f', "PARAMETERS", $strParameters);
						$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g15aeaa60-835c-4974-87c5-e82ec903629f', "IS_PROCESSED", "N");
						$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g15aeaa60-835c-4974-87c5-e82ec903629f', "STARTTIME", "");
						$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g15aeaa60-835c-4974-87c5-e82ec903629f', "ENDTIME", "");
						$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g15aeaa60-835c-4974-87c5-e82ec903629f', "SORTORDER", "");
						$strJSONData = json_encode($arrJSONData);

						$strSQL = "insert into ~TABLENAMECONFIGTASK~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, config_id) values(~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~', ~CONFIGID~)";
						$strSQL = str_replace('~TABLENAMECONFIGTASK~', ff($strTableNameConfigTask), $strSQL);
						$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
						$strSQL = str_replace('~CODE~', $strCode, $strSQL);
						$strSQL = str_replace('~DESCRIPTION~', $strDescription, $strSQL);
						$strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
						$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
						$strSQL = str_replace('~DATAENTITYID~', ff(getEntityID($objConn, "config")), $strSQL);
						$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
						$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
						$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
						$strSQL = str_replace('~CONFIGID~', $strConfigID, $strSQL);
						dbExecuteSQL($objConn, $strSQL, __FUNCTION__);
						$strConfigTaskID = dbLastInsertID($objConn);
						
						exposeEntityData($objConn, 'SYSTEMFORM', 'CONFIGTASK', $strConfigTaskID, $strJSONData);
					}
				}
			}

			//update sortorder and is ready 
			$arrJSONData = $arrConfigJSONData;
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb018840e-bc4f-45fc-b8a6-2c6338ca1be1', "IS_READY", 'Y');
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb018840e-bc4f-45fc-b8a6-2c6338ca1be1', "SORTORDER", $strConfigID);
			$strJSONData = json_encode($arrJSONData);

			$strSQL = "update ~TABLENAMECONFIG~ set jsondata = '~JSONDATA~' where id = ~CONFIGID~";
			$strSQL = str_replace('~TABLENAMECONFIG~', ff($strTableNameConfig), $strSQL);
			$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
			$strSQL = str_replace('~CONFIGID~', ff($strConfigID), $strSQL);
			dbExecuteSQL($objConn, $strSQL, __FUNCTION__);
			
			exposeEntityData($objConn, 'SYSTEMFORM', 'CONFIG', $strConfigID, $strJSONData);
		}

		dbClose($objConn, __FUNCTION__); 

		$blnResult = true;
	}
	else
	{
		$blnResult = false;
	}

	return $blnResult;
}

function taskInstallProfiles($objTask_a)
{        
	$strTableNameProfile = getTableNameEntity("profile", false);

	$strDatabaseHostname = replaceDictionary($objTask_a['database']['hostname']);
	$strDatabaseDBName = replaceDictionary($objTask_a['database']['dbname']);
	$strDatabaseLogin = replaceDictionary($objTask_a['database']['login']);
	$strDatabasePassword = replaceDictionary($objTask_a['database']['password']);
	
	$blnResult = false;
	$arrProfiles = replaceDictionary($objTask_a['profiles']);
			
	$objConn = dbOpen($strDatabaseHostname, $strDatabaseLogin, $strDatabasePassword, $strDatabaseDBName, __FUNCTION__);
		  
	if (is_array($arrProfiles) && count($arrProfiles) > 0)
	{
		if (dependencies('security/profileInstall')) 
		{   
			// default area
			$strClientIDFrom = getDefaultClientID($objConn);
			
			// system area
			$strClientIDTo = getSystemClientID($objConn);;

			foreach ($arrProfiles as $strProfile)
			{
				$strSQL = "select id returnvalue from ~TABLENAMEPROFILE~ where code = '~PROFILECODE~'";
				$strSQL = str_replace('~TABLENAMEPROFILE~', $strTableNameProfile, $strSQL);
				$strSQL = str_replace('~PROFILECODE~', ff($strProfile), $strSQL);
				$strProfileIDFrom = dbReadValue($objConn, $strSQL, __FUNCTION__);

				if (strlen($strProfileIDFrom) > 0)
				{
					$strProfileIDTo = profileInstall($objConn, $strClientIDFrom, $strClientIDTo, $strProfileIDFrom);  
					
					if (strlen($strProfileIDTo) > 0)
					{
						$blnResult = true;
					}
				}
			}
		}
	}

	return $blnResult;
}

// eg: { "exec": "migrateData", "source": { "hostname": "localhost", "dbname": "%CURRENT_DATABASE_APP%", "login": "%LOGIN%", "password": "%PASSWORD%" }, "destination": { "hostname": "localhost", "dbname": "%NEW_DATABASE_APP%", "login": "%LOGIN%", "password": "%PASSWORD%" }, "script": "%PATH_APP%/scripts/update%CURRENT_SCHEMA%to%NEW_SCHEMA%.sql" }
function taskMigrateData($objTask_a)
{
	$blnResult = true;
	
	$strSourceHostname = replaceDictionary($objTask_a['source']['hostname']);
	$strSourceDBName = replaceDictionary($objTask_a['source']['dbname']);
	$strSourceLogin = replaceDictionary($objTask_a['source']['login']);
	$strSourcePassword = replaceDictionary($objTask_a['source']['password']);
	$strDestinationHostname = replaceDictionary($objTask_a['destination']['hostname']);
	$strDestinationDBName = replaceDictionary($objTask_a['destination']['dbname']);
	$strDestinationLogin = replaceDictionary($objTask_a['destination']['login']);
	$strDestinationPassword = replaceDictionary($objTask_a['destination']['password']);
	$strScript = replaceDictionary(elementString($objTask_a, 'script', ''));
	
	$objConn = dbOpen($strDestinationHostname, $strDestinationLogin, $strDestinationPassword, $strDestinationDBName, __FUNCTION__);
	if ($objConn == null)
	{
		$blnResult = false;
	}
	else
	{
		if (strlen($strScript) > 0)
		{
			$blnResult = dbExecuteScript($objConn, $strScript, $strSourceDBName, __FUNCTION__);
		}
		dbClose($objConn, __FUNCTION__);
	}

	return $blnResult;
}

function taskNextTaskSet($objTask_a)
{
	global $g_blnSkipTaskset;
	
	$blnResult = true;
	
	$strFilename = replaceDictionary(elementString($objTask_a, 'checkfile', ''));
	if (!file_exists($strFilename))
	{
		$g_blnSkipTaskset = true;
	}
	
	return $blnResult;
}

// eg: { "exec": "set", "name": "%CURRENT_DATABASE_APP%", "value": "current_blah" }
function taskSet($objTask_a)
{
	$strName = replaceDictionary(elementString($objTask_a, 'name', ''));
	$strValue = replaceDictionary(elementString($objTask_a, 'value', ''));

	addDictionary($strName, $strValue);
	
	return true;
}

// eg: { "exec": "setSchema", "name": "%CURRENT_SCHEMA%", "metadata": "%PATH_APP%/application.json" }
function taskSetSchema($objTask_a)
{
	$strName = replaceDictionary(elementString($objTask_a, 'name', ''));
	$strSource = replaceDictionary(elementString($objTask_a, 'metadata', ''));

	$strMetaData = "{\"update\":\"\",\"date\":\"\",\"version\":\"0\",\"dbversion\":\"0\",\"build\":\"\",\"description\":\"\",\"filename\":\"\",\"url\":\"\"}";
	if (file_exists($strSource))
	{
		$strMetaData = loadFile($strSource);
	}
	$objMetaData = json_decode($strMetaData, true);
	$strValue = $objMetaData["dbversion"];

	addDictionary($strName, $strValue);
	
	return true;
}

// for debug purposes
function taskStop($objTask_a)
{
	return false;
}

function taskResetTablePermission($objTask_a)
{
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNamePermission = getTableNameEntity("permission", false);
	$strTableNameProfile = getTableNameEntity("profile", false);
	$strTableNameProfilePermission = getTableNameEntity("profile_permission", false);
	$strTableNameSystemForm = getTableNameEntity("systemform", false);

	$blnResult = false;

	$strDatabaseHostname = replaceDictionary($objTask_a['database']['hostname']);
	$strDatabaseDBName = replaceDictionary($objTask_a['database']['dbname']);
	$strDatabaseLogin = replaceDictionary($objTask_a['database']['login']);
	$strDatabasePassword = replaceDictionary($objTask_a['database']['password']);
	
	$objConn = dbOpen($strDatabaseHostname, $strDatabaseLogin, $strDatabasePassword, $strDatabaseDBName);
			
	if ($objConn == null)
	{
		$blnResult = false;
	}
	else
	{
		if (dependencies('entity/fixPermissionJSON'))
		{
			$strLogin = $_SESSION['server_loggedin_user'];
			
			$arrPermissionFields = [];
			$arrProfilePermissionJSON = [];

			$strProfiles = PROFILEDEFAULTS_SYSTEM;
			if (strlen(PROFILEDEFAULTS_EXTRA) > 0)
			{
				$strProfiles .= "," . PROFILEDEFAULTS_EXTRA;
			}
			
			$arrProfiles = explode("," , $strProfiles);
			

			dbBeginTrans($objConn, __FUNCTION__);
			
			// fetch fields in permission for check if a profile field already exists
			$strSQL = "show columns from ~TABLENAMEPERMISSION~";
			$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
			$objResult = dbOpenRecordset($objConn, $strSQL, __FUNCTION__);               
			while ($arrRow = dbReadRecord($objResult)) 
			{   
				$arrPermissionFields[] = $arrRow['Field'];
			}
			dbCloseRecordset($objResult);
			
			foreach ($arrProfiles as $strProfileCode)
			{
				$strFieldName = "def_" . $strProfileCode;
				$strFieldName = strtolower($strFieldName);

				$strProfilePermissionJSONFile = WS_PATH . 'modules/security/profiles/' . $strFieldName . '.json';

				if (file_exists($strProfilePermissionJSONFile))
				{
					$arrProfilePermissionJSONResult = json_decode(loadFile($strProfilePermissionJSONFile), true);
					$arrProfilePermissionJSON[$strFieldName] = $arrProfilePermissionJSONResult['permissions'];                             
				}

				// add the column field in permission table if does not exists
				if (!in_array($strFieldName, $arrPermissionFields))
				{
					$strSQL = "alter table ~TABLENAMEPERMISSION~ add column ~FIELDNAME~ varchar(1) null";
					$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
					$strSQL = str_replace('~FIELDNAME~', ff($strFieldName), $strSQL);
					dbExecuteSQL($objConn, $strSQL, __FUNCTION__); 
				}  
				
				if (isset($arrProfilePermissionJSON[$strFieldName]))
				{
					$arrPermissions = $arrProfilePermissionJSON[$strFieldName];
					$strPermissionCodeList = "'" . implode('\', \'', $arrPermissions) . "'";
					
					// mark Y field in permission table                
					$strSQL = "update ~TABLENAMEPERMISSION~ set ~FIELDNAME~ = 'Y' where code in (~PERMISSIONCODELIST~)";
					$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
					$strSQL = str_replace('~FIELDNAME~', $strFieldName, $strSQL);
					$strSQL = str_replace('~PERMISSIONCODELIST~', $strPermissionCodeList, $strSQL);
					dbExecuteSQL($objConn, $strSQL, __FUNCTION__);   
				}
			}

			$strSQL = "select id from ~TABLENAMECLIENT~ order by client_id";
			$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
			$objResultClient = dbOpenRecordset($objConn, $strSQL, __FUNCTION__);
			while ($arrRowClient = dbReadRecord($objResultClient)) 
			{
				$strClientID = $arrRowClient['id'];

				foreach ($arrProfiles as $strProfileCode)
				{
					$strFieldName = "def_" . $strProfileCode;
					$strFieldName = strtolower($strFieldName);
									
					$strSQL = "select id returnvalue from ~TABLENAMEPROFILE~ where client_id = ~CLIENTID~ and code = '~PROFILECODE~'";
					$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
					$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
					$strSQL = str_replace('~PROFILECODE~', ff($strProfileCode), $strSQL);
					$strProfileID = dbReadValue($objConn, $strSQL, __FUNCTION__);

					if (strlen($strProfileID) > 0)
					{
						// reset the profiles across all clients
						$strSQL = "delete from ~TABLENAMEPROFILEPERMISSION~ where client_id = ~CLIENTID~ and profile_id = ~PROFILEID~";
						$strSQL = str_replace('~TABLENAMEPROFILEPERMISSION~', ff($strTableNameProfilePermission), $strSQL);
						$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
						$strSQL = str_replace('~PROFILEID~', ff($strProfileID), $strSQL);
						dbExecuteSQL($objConn, $strSQL, __FUNCTION__);
						
						if (isset($arrProfilePermissionJSON[$strFieldName]))
						{
							$arrPermissions = $arrProfilePermissionJSON[$strFieldName];
							$strPermissionCodeList = "'" . implode('\', \'', $arrPermissions) . "'";

							$strSQL = 
							"
							insert into ~TABLENAMEPROFILEPERMISSION~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, profile_id, permission_id)
							select pro.client_id, perm.entity_id, perm.dataentity_id, perm.code, perm.description, 'Y', pro.client_id, null, '~MODIFYUSER~', '~MODIFYDATETIME~', pro.id, perm.id
							from ~TABLENAMEPROFILE~ pro, ~TABLENAMEPERMISSION~ perm where perm.code in (~PERMISSIONCODELIST~) and pro.id = ~PROFILEID~
							";
							$strSQL = str_replace('~TABLENAMEPROFILEPERMISSION~', ff($strTableNameProfilePermission), $strSQL);
							$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
							$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
							$strSQL = str_replace('~PERMISSIONCODELIST~', $strPermissionCodeList, $strSQL);
							$strSQL = str_replace('~PROFILEID~', ff($strProfileID), $strSQL);
							$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
							$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
							dbExecuteSQL($objConn, $strSQL, __FUNCTION__);                                                                      
						}
						
					}
				}
				
				fixPermissionJSON($objConn, $strClientID);
			}
			dbCloseRecordset($objResultClient);
					
			$blnResult = dbEndTrans($objConn, __FUNCTION__);

			dbClose($objConn, __FUNCTION__);
		}
	}

	return $blnResult;
}

function taskResetTablePermissionxxx($objTask_a)
{
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNamePermission = getTableNameEntity("permission", false);
	$strTableNameProfile = getTableNameEntity("profile", false);
	$strTableNameProfilePermission = getTableNameEntity("profile_permission", false);
	$strTableNameSystemForm = getTableNameEntity("systemform", false);

	$blnResult = false;

	$strDatabaseHostname = replaceDictionary($objTask_a['database']['hostname']);
	$strDatabaseDBName = replaceDictionary($objTask_a['database']['dbname']);
	$strDatabaseLogin = replaceDictionary($objTask_a['database']['login']);
	$strDatabasePassword = replaceDictionary($objTask_a['database']['password']);
	
	$objConn = dbOpen($strDatabaseHostname, $strDatabaseLogin, $strDatabasePassword, $strDatabaseDBName, __FUNCTION__);
			
	if ($objConn == null)
	{
		$blnResult = false;
	}
	else
	{
		if (dependencies('entity/fixPermissionJSON'))
		{
			$strLogin = $_SESSION['server_loggedin_user'];
			
			$strProfiles = PROFILEDEFAULTS_SYSTEM;
			if (strlen(PROFILEDEFAULTS_EXTRA) > 0)
			{
				$strProfiles .= "," . PROFILEDEFAULTS_EXTRA;
			}
			
			$arrProfiles = explode("," , $strProfiles);
			
			dbBeginTrans($objConn, __FUNCTION__);
			
			$strSQL = "select id from ~TABLENAMECLIENT~ order by client_id";
			$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
			$objResultClient = dbOpenRecordset($objConn, $strSQL, __FUNCTION__);
			while ($arrRowClient = dbReadRecord($objResultClient)) 
			{
				$strClientID = $arrRowClient['id'];

				foreach ($arrProfiles as $strProfileCode)
				{
					$strFieldName = "def_" . $strProfileCode;
					$strFieldName = strtolower($strFieldName);

					$strSQL = "select id returnvalue from ~TABLENAMEPROFILE~ where client_id = ~CLIENTID~ and code = '~PROFILECODE~'";
					$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
					$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
					$strSQL = str_replace('~PROFILECODE~', ff($strProfileCode), $strSQL);
					$strProfileID = dbReadValue($objConn, $strSQL, __FUNCTION__);

					if (strlen($strProfileID) > 0)
					{
						// reset the profiles across all clients
						$strSQL = "delete from ~TABLENAMEPROFILEPERMISSION~ where client_id = ~CLIENTID~ and profile_id = ~PROFILEID~";
						$strSQL = str_replace('~TABLENAMEPROFILEPERMISSION~', ff($strTableNameProfilePermission), $strSQL);
						$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
						$strSQL = str_replace('~PROFILEID~', ff($strProfileID), $strSQL);
						dbExecuteSQL($objConn, $strSQL, __FUNCTION__);

						$strSQL = 
				"
				insert into ~TABLENAMEPROFILEPERMISSION~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, profile_id, permission_id)
				select pro.client_id, perm.entity_id, perm.dataentity_id, perm.code, perm.description, 'Y', pro.client_id, null, '~MODIFYUSER~', '~MODIFYDATETIME~', pro.id, perm.id
				from ~TABLENAMEPROFILE~ pro, ~TABLENAMEPERMISSION~ perm where perm.~FIELDNAME~ = 'Y' and pro.id = ~PROFILEID~
				";
						$strSQL = str_replace('~TABLENAMEPROFILEPERMISSION~', ff($strTableNameProfilePermission), $strSQL);
						$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
						$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
						$strSQL = str_replace('~FIELDNAME~', ff($strFieldName), $strSQL);
						$strSQL = str_replace('~PROFILEID~', ff($strProfileID), $strSQL);
						$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
						$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
						dbExecuteSQL($objConn, $strSQL, __FUNCTION__);
					}
				}
				
				fixPermissionJSON($objConn, $strClientID);
			}
			dbCloseRecordset($objResultClient);
			
			$blnResult = dbEndTrans($objConn, __FUNCTION__);

			dbClose($objConn, __FUNCTION__);
		}
	}

	return $blnResult;
}

// eg: { "exec": "unarchive", "archive": "updates/%UPDATEFILENAME%", "folder": "%PATH_TEMP%" }
function taskUnarchive($objTask_a)
{
	$strArchive = replaceDictionary(elementString($objTask_a, 'archive', ''));
	$strFolder = replaceDictionary(elementString($objTask_a, 'folder', ''));

	return unzipFile($strArchive, $strFolder . '/');
}

?>
