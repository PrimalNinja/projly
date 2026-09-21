<?php

// register an ESB broadcaster
function actionESBRegisterBroadcaster($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$blnResult = false;
    $strResult = "";

    if (dependencies('esb/esbRegisterBroadcaster')) 
	{
        // permission check
        if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) { return false; }

        // parameters
        $strEventQueue = getJSONParameter($arrParameters_a, 'eventqueue');

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
			esbRegisterBroadcaster($objConn_a, $strClientID, $strDeviceID, $strEventQueue);
			$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
		}
		
        if ($blnResult) 
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', array());
        } 
		else 
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error registering as ESB broadcaster.', array());
        }
    }

    return $strResult;
}
