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

$g_arrDefaultDictionary = array();	// defaults go in here        
$g_arrDictionary = array();

$intExecutionTime = ini_get('max_execution_time');
set_time_limit(intval(WS_MAX_RUN_TIME, 60));
date_default_timezone_set(TIMEZONE);

if (dependencies('system/debug,system/generic,system/logging,system/sessions,system/webService') &&
	dependencies('utils/database,utils/dbConnection,utils/docs,utils/entity,utils/file,utils/forms,utils/formtools,utils/general,utils/dates,utils/import,utils/json,utils/messaging,utils/money,utils/reports,utils/security,utils/xml'))
{        
	// read URL parameters for direct webservice access via URL
	$strURLToken = "";
	if (isset($_GET["token"]))
	{
		$strURLToken = $_GET["token"];
	}

	// image
	$strImage = "";
	//if (isset($_GET["image"]))
	//{
		//$strImage = $_GET["image"];
	//}

	// used for registration confirmation
	$strConfirm = "";
	//if (isset($_GET["confirm"]))
	//{
		//$strConfirm = $_GET["confirm"];
	//}
    
    $strVerify = "";

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

	$strJSON = "";
	if (isset($_GET["json"]))
	{
		$strJSON = $_GET["json"];
	}

	$strCallback = "";
	if (isset($_GET["callback"]))
	{
		$strCallback = $_GET["callback"];
	}
	
	$blnUseJSONP = false;
	if ((strlen($strJSON) > 0) && (strlen($strCallback) > 0))
	{
		$blnUseJSONP = true;
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
	sendJSONResponse($strResult);
}

set_time_limit($intExecutionTime);

?>