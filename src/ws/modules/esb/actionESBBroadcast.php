<?php

// broadcast a message via the ESB (for now this generic function does not allow cross client broadcasting)
function actionESBBroadcast($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$blnResult = false;
    $strResult = "";

    if (dependencies('esb/esbBroadcast')) 
	{
        // permission check
		if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) { return false; }

        // parameters
        $strEventQueue = getJSONParameter($arrParameters_a, 'eventqueue');
        $strEvent = getJSONParameter($arrParameters_a, 'event');
        $strEventData = getJSONParameter($arrParameters_a, 'eventdata');

        // initialisations
		$blnIsPublic = $_SESSION['server_loggedin_public'];
        $strClientID = $_SESSION['server_loggedin_clientid'];
        $strDeviceID = $_SESSION['server_deviceid'];

		if ($blnIsPublic)
		{
			// do nothing
			$blnResult = true;
		}
		else
		{
			dbBeginTrans($objConn_a, __FUNCTION__);
			esbBroadcast($objConn_a, $strClientID, $strDeviceID, '', $strEventQueue, $strEvent, $strEventData, false, false);
			$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
		}
		
        if ($blnResult) 
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', array());
        } 
		else 
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error broadcasting on ESB.', array());
        }
    }

    return $strResult;
}
