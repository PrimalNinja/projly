<?php

// listen to the ESB
function actionESBListen($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strResult = "";
    $arrResult = array();

    if (dependencies('esb/esbListen')) 
	{
        // permission check
		if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) { return false; }

        // parameters
        $strEventQueueList = getJSONParameter($arrParameters_a, 'eventqueuelist');
        $arrEventQueueList = explode(' ', $strEventQueueList);

        // initialisations
		$blnIsPublic = $_SESSION['server_loggedin_public'];
        $strClientID = $_SESSION['server_loggedin_clientid'];
        $strDeviceID = $_SESSION['server_deviceid'];

		if ($blnIsPublic)
		{
			// do nothing
		}
		else
		{
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

			if (strlen($strESBEventQueueList) > 0) 
			{
				$arrResult = esbListen($objConn_a, $strClientID, $strDeviceID, $strESBEventQueueList);
			}
		}

        $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
    }

    return $strResult;
}
