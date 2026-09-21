<?php

require_once('ws/inc-env.php');
require_once('ws/inc-constants.php');
require_once('ws/inc-settings.php');
require_once('ws/inc-app-' . APP_CODE . '.php');
require_once('ws/inc-dbsettings-client.php');
require_once('ws/inc-dbsettings-system.php');
require_once('ws/modules/core/inc-constants.php');
require_once(WS_PATH . 'modules/system/framework.php'); 

$intExecutionTime = ini_get('max_execution_time');
set_time_limit(intval(WS_MAX_RUN_TIME, 10));
date_default_timezone_set(TIMEZONE);

if (dependencies('system/debug,system/generic,system/logging,system/sessions,system/webService') &&
	dependencies('utils/database,utils/dbConnection,utils/entity,utils/file,utils/forms,utils/formtools,utils/general,utils/dates,utils/import,utils/json,utils/messaging,utils/security'))
{    
	$strConfirm = "";
    $strVerify = "";
	$strDownload = "";
	$strURLToken = "";
	$strURLDocumentID = "";
	$strJSON = "";
	$strCallback = "";
	$blnUseJSONP = false;

	$strURLToken = "";
	if (isset($_GET["token"]))
	{
		$strURLToken = $_GET["token"];
	}
	
	// image
	$strImage = "";
	if (isset($_GET["image"]))
	{
		$strImage = $_GET["image"];
	}

	$strURLDocumentID = "";
	if (isset($_GET["document"]))
	{
		$strURLDocumentID = $_GET["document"];
	}

	$strDownload = "";
	if (isset($_GET["download"]))
	{
		$strDownload = $_GET["download"];
	}

	$strMetaData = "";
	if (isset($_GET["metadata"]))
	{
		$strMetaData = $_GET["metadata"];
	}

	$strWiki = "";
	if (isset($_GET["wiki"]))
	{
		$strWiki = $_GET["wiki"];
	}

	// if changing the parameters to serviceJson, don't forget confirm.php as well as fetch.php
	$api = new webService;
	$strResult = $api->serviceJson($strConfirm, $strVerify, $strImage, $strURLToken, $strURLDocumentID, $strMetaData, $strWiki, $strDownload, $blnUseJSONP, $strJSON, $strCallback);
	echo($strResult);
}

set_time_limit($intExecutionTime);

?>