<?php

require_once('inc-env.php');
require_once('inc-constants.php');
require_once('inc-settings.php');
require_once('inc-temporaryvalues.php');
require_once('inc-app-' . APP_CODE . '.php');
require_once('inc-dbsettings-client.php');
require_once('inc-dbsettings-system.php');
require_once('modules/core/inc-constants.php');
require_once(WS_PATH . 'modules/system/framework.php'); 

// globals
$strGlobalClientDB = '';

$g_arrDefaultDictionary = array();	// defaults go in here        
$g_arrDictionary = array();

$intExecutionTime = ini_get('max_execution_time');
set_time_limit(intval(BATCH_MAX_RUN_TIME, 10));
date_default_timezone_set(TIMEZONE);

if (dependencies('batch/batchProcessor') &&
	dependencies('process/processTask') &&
	dependencies('security/userLogin,security/userLogout') &&
	dependencies('system/debug,system/generic,system/logging,system/sessions') &&
	dependencies('utils/database,utils/dbConnection,utils/docs,utils/entity,utils/file,utils/forms,utils/formtools,utils/general,utils/dates,utils/import,utils/json,utils/messaging,utils/money,utils/reports,utils/security'))
{
	$blnResult = false;

	//echo("processbatch started...");

    $strBatchDB = "";
    if (isset($_GET["batchdb"]))
	{
		$strBatchDB = $_GET["batchdb"];
		
		if (strlen($strBatchDB) == 0)
		{
			$strBatchDB = "system";
		}
    }
    
    $blnProcessBuild = false;
    if (isset($_GET["processbuild"]))
	{
		$blnProcessBuild = toBoolean($_GET["processbuild"]);
    }
    
    $blnProcessFiles = false;
    if (isset($_GET["processfiles"]))
	{
		$blnProcessFiles = toBoolean($_GET["processfiles"]);
    }
    
	$blnProcessSchemaChanges = false;
	if (isset($_GET["processschemachanges"]))
	{
		$blnProcessSchemaChanges = toBoolean($_GET["processschemachanges"]);
	}

	$blnProcessEntityStats = false;
	if (isset($_GET["processentitystats"]))
	{
		$blnProcessEntityStats = toBoolean($_GET["processentitystats"]);
	}

	$blnBatchJobs = false;
	if (isset($_GET["batchjobs"]))
	{
		$blnBatchJobs = toBoolean($_GET["batchjobs"]);
	}
	
	$blnEventExpired = false;
	if (isset($_GET["eventexpired"]))
	{
		$blnEventExpired = toBoolean($_GET["eventexpired"]);
	}
	
	$blnEventRenewal = false;
	if (isset($_GET["eventrenewal"]))
	{
		$blnEventRenewal = toBoolean($_GET["eventrenewal"]);
	}

	$blnProcessIntegrationTaskInbound = false;
	if (isset($_GET["processintegrationtaskinbound"]))
	{
		$blnProcessIntegrationTaskInbound = toBoolean($_GET["processintegrationtaskinbound"]);
	}

    $blnProcessIntegrationTaskOutbound = false;
	if (isset($_GET["processintegrationtaskoutbound"]))
	{
		$blnProcessIntegrationTaskOutbound = toBoolean($_GET["processintegrationtaskoutbound"]);
	}


	$blnReminders = false;
	if (isset($_GET["reminders"]))
	{
		$blnReminders = toBoolean($_GET["reminders"]);
	}

	$blnSendEmails = false;
	if (isset($_GET["sendemails"]))
	{
		$blnSendEmails = toBoolean($_GET["sendemails"]);
	}

    $blnWidgets = false;
	if (isset($_GET["widgets"]))
	{
		$blnWidgets = toBoolean($_GET["widgets"]);
	}
	
	$blnLogData = false;
	if (isset($_GET["logdata"]))
	{
		$blnLogData = toBoolean($_GET["logdata"]);
	}

	$blnConfig = false;
	if (isset($_GET['config']))
	{
		$blnConfig = toBoolean($_GET["config"]);
	}
    
    $blnHousekeeping = false;
    if (isset($_GET['housekeeping']))
    {
        $blnHousekeeping = toBoolean($_GET['housekeeping']);
    }

    $blnRoster = false;
    if (isset($_GET['roster']))
    {
        $blnRoster = toBoolean($_GET['roster']);
    }

	$bp = new batchProcessor;
	$bp->initialise($strBatchDB);
	$bp->processBatch($blnProcessSchemaChanges, $blnProcessEntityStats, $blnBatchJobs, $blnEventExpired, $blnEventRenewal, $blnReminders, $blnSendEmails, $blnWidgets, $blnLogData, $blnConfig, $blnHousekeeping, $blnProcessBuild, $blnProcessFiles, $blnProcessIntegrationTaskInbound, $blnProcessIntegrationTaskOutbound, $blnRoster);
   
	echo("processbatch finished...");
}	

set_time_limit($intExecutionTime);

?>