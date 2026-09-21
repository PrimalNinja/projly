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
	dependencies('utils/database,utils/dbConnection,utils/entity,utils/file,utils/forms,utils/general,utils/dates,utils/import,utils/json,utils/messaging,utils/security'))
{
    $strConfirm = "";
	$strType = "";
	$strImage = "";
	$strURLToken = "";
	$strURLDocumentID = "";
	$strDownload = "";
	$strJSON = "";
	$strCallback = "";
	$blnUseJSONP = false;
	$strMetaData = "";
	$strWiki = "";
       
	// used for registration verification
	$strVerify = "";
	if (isset($_GET["verify"]))
	{
		$strVerify = $_GET["verify"];
	}

	// if changing the parameters to serviceJson, don't forget confirm.php as well as fetch.php
	$api = new webService;
	$strResult = $api->serviceJson($strConfirm, $strVerify, $strType, $strImage, $strURLToken, $strURLDocumentID, $strMetaData, $strWiki, $strDownload, $blnUseJSONP, $strJSON, $strCallback);
	sendJSONResponse($strResult);
}

set_time_limit($intExecutionTime);

?>