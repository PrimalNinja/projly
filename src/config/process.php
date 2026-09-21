<?php

	require('inc-constants.php');
	require('app/ws/inc-dbsettings-client.php');
	require('app/ws/inc-dbsettings-system.php');
	require('utils/general.php');
	require('utils/dates.php');
	require('utils/logging.php');
	require('utils/file.php');
	require('utils/dbConnection.php');
	require('utils/database.php');
	require('utils/tasks.php');

	$intStartProcess = time();
	$intExecutionTime = ini_get('max_execution_time');
	set_time_limit(MAX_RUN_TIME);
	date_default_timezone_set(TIMEZONE);

	// global dictionary
	$g_arrDefaultDictionary = array();	// defaults go in here
	
	$g_arrDictionary = array();
	if (file_exists("dictionary.json"))
	{
		$g_strDictionary = loadFile("dictionary.json");
		if (strlen($g_strDictionary) > 0)
		{
			$g_arrDictionary = json_decode($g_strDictionary, true);
		}
	}

	$g_blnSkipTaskset = false;
	$g_strAlert = "";
	$strResult = "";
	
	// script checking
	$strConfig = loadFile('config.json');
	$arrConfig = json_decode($strConfig, true);

	$strInstallScript = $arrConfig['awafinstallscript'];
	$strScriptVersion = $arrConfig['scriptversion'];
	if (intval($strScriptVersion) != intval(REQUIRED_SCRIPT_VERSION))
	{
		die("Required scriptversion '" . REQUIRED_SCRIPT_VERSION . "' but found scriptversion '" . $strScriptVersion . "'.");
	}

	$strOptionCode = "";
	if (isset($_GET["optioncode"]))
	{
		$strOptionCode = $_GET["optioncode"];
	}

	$strTask = "";
	if (isset($_GET["task"]))
	{
		$strTask = $_GET["task"];
	}
	
	$strUpdate = "";
	if (isset($_GET["update"]))
	{
		$strUpdate = $_GET["update"];
	}

	$blnStatus = false;
	if (isset($_GET["status"]))
	{
		$blnStatus = ($_GET["status"] == "true");
	}
	
	$arrOptionSets = $arrConfig['optionSets'];
	$arrTaskSets = $arrConfig['taskSets'];

	require('inc-installed-check.php');

	if ($blnStatus)
	{
		// load and return a status update
		$strResult = loadFile('status.json');
		sendJSONResponse($strResult);
	}
	else
	{
		// TODO check permissions for the following flags: installed
		
		// system dictionary entries
		addDefaultDictionary('%SYS_DBCLIENTMAIN_DATABASENAME%', DBCLIENTMAIN_DATABASENAME);
		addDefaultDictionary('%SYS_DBCLIENTMAIN_HOSTNAME%', DBCLIENTMAIN_HOSTNAME);
		addDefaultDictionary('%SYS_DBCLIENTMAIN_LOGIN%', DBCLIENTMAIN_LOGIN);
		addDefaultDictionary('%SYS_DBCLIENTMAIN_PASSWORD%', DBCLIENTMAIN_PASSWORD);

        addDefaultDictionary('%SYS_DBCLIENTHISTORY_DATABASENAME%', DBCLIENTHISTORY_DATABASENAME);
		addDefaultDictionary('%SYS_DBCLIENTHISTORY_HOSTNAME%', DBCLIENTHISTORY_HOSTNAME);
		addDefaultDictionary('%SYS_DBCLIENTHISTORY_LOGIN%', DBCLIENTHISTORY_LOGIN);
		addDefaultDictionary('%SYS_DBCLIENTHISTORY_PASSWORD%', DBCLIENTHISTORY_PASSWORD);

		addDefaultDictionary('%SYS_DBCLIENTTEMP_DATABASENAME%', DBCLIENTTEMP_DATABASENAME);
		addDefaultDictionary('%SYS_DBCLIENTTEMP_HOSTNAME%', DBCLIENTTEMP_HOSTNAME);
		addDefaultDictionary('%SYS_DBCLIENTTEMP_LOGIN%', DBCLIENTTEMP_LOGIN);
        addDefaultDictionary('%SYS_DBCLIENTTEMP_PASSWORD%', DBCLIENTTEMP_PASSWORD);
        
		addDefaultDictionary('%SYS_DBSYSTEMMAIN_DATABASENAME%', DBSYSTEMMAIN_DATABASENAME);
		addDefaultDictionary('%SYS_DBSYSTEMMAIN_HOSTNAME%', DBSYSTEMMAIN_HOSTNAME);
		addDefaultDictionary('%SYS_DBSYSTEMMAIN_LOGIN%', DBSYSTEMMAIN_LOGIN);
		addDefaultDictionary('%SYS_DBSYSTEMMAIN_PASSWORD%', DBSYSTEMMAIN_PASSWORD);

        addDefaultDictionary('%SYS_DBSYSTEMHISTORY_DATABASENAME%', DBSYSTEMHISTORY_DATABASENAME);
		addDefaultDictionary('%SYS_DBSYSTEMHISTORY_HOSTNAME%', DBSYSTEMHISTORY_HOSTNAME);
		addDefaultDictionary('%SYS_DBSYSTEMHISTORY_LOGIN%', DBSYSTEMHISTORY_LOGIN);
		addDefaultDictionary('%SYS_DBSYSTEMHISTORY_PASSWORD%', DBSYSTEMHISTORY_PASSWORD);

		addDefaultDictionary('%SYS_DBSYSTEMTEMP_DATABASENAME%', DBSYSTEMTEMP_DATABASENAME);
		addDefaultDictionary('%SYS_DBSYSTEMTEMP_HOSTNAME%', DBSYSTEMTEMP_HOSTNAME);
		addDefaultDictionary('%SYS_DBSYSTEMTEMP_LOGIN%', DBSYSTEMTEMP_LOGIN);
        addDefaultDictionary('%SYS_DBSYSTEMTEMP_PASSWORD%', DBSYSTEMTEMP_PASSWORD);
        
		addDefaultDictionary('%SYS_YYYYMMDDHHNNSS%', getFileDateTime());
		addDefaultDictionary('%SYS_FILESELECTED%', $strUpdate);
		addDefaultDictionary('%SYS_TODAY%', getToday());
		addDefaultDictionary('%SYS_PATH_CONFIG%', getCurrentWD());
		
		//$g_arrDictionary = array_merge($g_arrDefaultDictionary, $g_arrDictionary);
		
		if (strlen($strTask) > 0)
		{
			$strStatus = '';

			$blnResult = true;
			$objTask = json_decode($strTask, true);
			$strTaskName = $objTask['exec'];

			// process task
			logDebug('start: task ' . $strTask, '');

			$strResult = updateStatus($strStatus, $strOptionCode, 'task', $strTask, 0, 0, $strTaskName, 0, 0, $intStartProcess, "", array());

			$arrResult = array();
			try
			{
				if ($strTaskName == 'getExportedFiles')	// getExportedFiles is not impemented with optionsets, only tasks
				{
					$arrResult = processTask($objTask);
				}
				else if ($strTaskName == 'getSchemaElements')	// getSchemaElements is not impemented with optionsets, only tasks
				{
					$arrResult = processTask($objTask);
				}
				else
				{
					$blnResult = processTask($objTask);
				}
			}
			catch(Exception $e)
			{
				$blnResult = false;
				$strStatus = 'error: ' . $e->getMessage();
				logDebug($strStatus, '');
			}

			$strResult = updateStatus($strStatus, $strOptionCode, 'task', $strTask, 1, 1, '', 1, 1, $intStartProcess, $g_strAlert, $arrResult);
			
			logDebug('end: task', '');
		}
		else
		{
			// find the option set
			$arrOption = null;
			for ($intI = 0; $intI < count($arrOptionSets); $intI++)
			{
				$arrOptionSet = $arrOptionSets[$intI];
				$arrOptions = $arrOptionSet['options'];
				for ($intJ = 0; $intJ < count($arrOptions); $intJ++)
				{
					if ($arrOptions[$intJ]['code'] == $strOptionCode)
					{
						$arrOption = $arrOptions[$intJ];
					}
				}
			}
			
			if ($arrOption == null)
			{
				// do nothing
			}
			else
			{
				$strOptionCode = $arrOption['code'];
				$arrTaskSetList = $arrOption['taskSets'];
				$strStatus = '';
				$strResult = updateStatus($strStatus, $strOptionCode, '', '', 0, 0, '', 0, 0, $intStartProcess, "", array());

				// process tasksets
				$intTaskSets = count($arrTaskSetList);
				if ($intTaskSets > 0)
				{
					logDebug('start: taskset', '');

					$blnResult = true;
					for ($intTaskSet = 0; ($intTaskSet < $intTaskSets) && $blnResult; $intTaskSet++)
					{
						$strTaskSet = $arrTaskSetList[$intTaskSet];
						$arrTaskSet = $arrTaskSets[$strTaskSet];
						$strTaskSetDescription = $arrTaskSet['description'];
						
						$strResult = updateStatus($strStatus, $strOptionCode, $strTaskSet, $strTaskSetDescription, $intTaskSet, $intTaskSets, '', 0, 0, $intStartProcess, "", array());
						$intTaskSetStatus = $intTaskSet + 1;
						
						$arrTasks = $arrTaskSet['tasks'];
						$intTasks = count($arrTasks);
						$g_blnSkipTaskset = false;
						for ($intTask = 0; ($intTask < $intTasks) && $blnResult && ($g_blnSkipTaskset == false); $intTask++)
						{
							$arrTask = $arrTasks[$intTask];
							$strTaskName = $arrTask['exec'];

							$strResult = updateStatus($strStatus, $strOptionCode, $strTaskSet, $strTaskSetDescription, $intTaskSetStatus, $intTaskSets, $strTaskName, $intTask, $intTasks, $intStartProcess, "", array());
							try
							{
								logDebug('start: ' . $strTaskSetDescription . '.' . $strTaskName, '');
								$blnResult = processTask($arrTask);
								logDebug('end: ' . $strTaskSetDescription . '.' . $strTaskName, '');
							}
							catch(Exception $e)
							{
								$blnResult = false;
								logDebug('error: ' . $strTaskSetDescription . '.' . $strTaskName, '');
							}
							
							if ($blnResult == false)
							{
								$strStatus = $intTaskSetStatus . '.' . ($intTask + 1) . ', ' . $strTaskName;
							}
						}
						
						$strResult = updateStatus($strStatus, $strOptionCode, $strTaskSet, $strTaskSetDescription, $intTaskSetStatus, $intTaskSets, '', $intTasks, $intTasks, $intStartProcess, $g_strAlert, array());
					}
					
					if ($blnResult)
					{
						$strResult = updateStatus($strStatus, $strOptionCode, $strTaskSet, $strTaskSetDescription, $intTaskSets, $intTaskSets, '', 1, 1, $intStartProcess, $g_strAlert, array());
					}
					
					logDebug('end: taskset', '');
				}
			}
		}
		
		saveFile("dictionary.json", json_encode($g_arrDictionary));
	}

	set_time_limit($intExecutionTime);
	$intStopProcess = time();
	$intProcessTimeframe = ($intStopProcess - $intStartProcess);

	echo($strResult);
?>
