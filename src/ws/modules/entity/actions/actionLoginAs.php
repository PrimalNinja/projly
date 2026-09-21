<?php
 
function actionLoginAs($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strResult = "";

    if (dependencies('security/loginAs'))
    {
        // permission check
        if (!hasPermission($objConn_a, 'LOGIN_AS', __FUNCTION__, true)) {return false;}

        $strClientID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);

        dbBeginTrans($objConn_a, __FUNCTION__);
        $strResult = loginAs($objConn_a, $strClientID, true, "System");

        if (dbEndTrans($objConn_a, __FUNCTION__)) 
		{
			// do NOT set $_SESSION['server_clientdb'] as we do in myclientloginas, because we are logging into the same host
			logSecurity('DB is client', '');
	
            $strResult = createJSONResponse($strDataID_a, RESPONSE_RELOAD, '', array());
        }
        else
        {
			updateSessionDB("system", "Session Lost", __FUNCTION__);	// temporary, in future it might be a lookup to cater for multiple hosts
			logSecurity('DB is system', '');

            $strDescription = dbErrorDescription(true);
            if (strlen($strDescription) == 0)
            {
                $strDescription = "Error logging in as.";
            }
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strDescription, array());
        }
    }

    return $strResult;
}
