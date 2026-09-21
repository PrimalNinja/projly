<?php

// check for server events
function actionCoreServerEventsCheck($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameDevice = getTableNameEntity("device", false);
	
    $strResult = "";
	$strMessage = "";	// for debugging

	// permission check
	if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) { return false; }

    // session variables
	$blnIsPublic = $_SESSION['server_loggedin_public'];
    $strClientID = $_SESSION['server_loggedin_clientid'];
    $strUserID = $_SESSION['server_loggedin_userid'];
    $strDeviceID = $_SESSION['server_deviceid'];

    $arrResultPrintJobs = array();
    $arrResultESB = array();

	if ($blnIsPublic)
	{
		// do nothing
	}
	else
	{
		$strEventQueueList = getJSONParameter($arrParameters_a, 'eventqueuelist');
		$arrEventQueueList = explode(' ', $strEventQueueList);

		// remaining events are esb ones, process special events first (such as printing)
		$strESBEventQueueList = '';
		foreach ($arrEventQueueList as $strEventQueue) 
		{
			if ($strEventQueue == 'printer') 
			{
				if (dependencies('core/serverEventPrintJobsCheck')) 
				{
					$arrResultPrintJobs = serverEventPrintJobsCheck($objConn_a, $strClientID, $strUserID, $strDeviceID, $arrParameters_a);
				}
			} 
			else 
			{
				if (strlen($strESBEventQueueList) > 0) 
				{
					$strESBEventQueueList = $strESBEventQueueList . ",";
				}

				$strESBEventQueueList = $strESBEventQueueList . "'" . ff($strEventQueue) . "'";
			}
		}

		if (strlen($strESBEventQueueList) > 0) 
		{
			if (dependencies('esb/esbListen')) 
			{
				$arrResultESB = esbListen($objConn_a, $strClientID, $strDeviceID, $strESBEventQueueList);
			}
		}
		
		$strDateTime = getDateTime();
		$strDateTimeExpired = getDateTimeMinusSeconds($strDateTime, HEARTBEAT_EXPIRY_INSECONDS);
		
		$strSQL = "update ~TABLENAMEDEVICE~ set is_current = 'Y', heartbeatdatetime = '~HEARTBEATDATETIME~' where id = ~DEVICEID~";
		$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
		$strSQL = str_replace('~HEARTBEATDATETIME~', $strDateTime, $strSQL);
		$strSQL = str_replace('~DEVICEID~', $strDeviceID, $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		$strSQL = "update ~TABLENAMEDEVICE~ set is_current = 'N' where client_id = ~CLIENTID~ and user_id = ~USERID~ and heartbeatdatetime <= '~TIMEEXPIRED~'";
		$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
		$strSQL = str_replace('~TIMEEXPIRED~', $strDateTimeExpired, $strSQL);
		$strSQL = str_replace('~CLIENTID~', $strClientID, $strSQL);
		$strSQL = str_replace('~USERID~', $strUserID, $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	}

    $arrResult = array_merge($arrResultPrintJobs, $arrResultESB);
	//$strMessage = print_r($arrResult, true);
    $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, $strMessage, $arrResult);

    return $strResult;
}
