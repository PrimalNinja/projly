<?php

// check the ESB
function actionESBCheck($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strResult = "";
    $arrResult = array();

    if (dependencies('esb/esbCheck')) 
	{
        // permission check
        if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) { return false; }

        // initialisations
        $strClientID = $_SESSION['server_loggedin_clientid'];
        $strDeviceID = $_SESSION['server_deviceid'];

        // parameters
        $strEventQueueList = getJSONParameter($arrParameters_a, 'eventqueuelist');
        $arrEventQueueList = explode(' ', $strEventQueueList);

        // remaining events are esb ones, process special events first (such as printing)
        $strESBEventQueueList = '';
        foreach ($arrEventQueueList as $strEventQueue) 
		{
            if ($strEventQueue == 'printjob') {
                // do nothing as this is specifically an ESB checker
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

        $arrResult = esbCheck($objConn_a, $strClientID, $strDeviceID, $strESBEventQueueList);
        $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
    }

    return $strResult;
}
