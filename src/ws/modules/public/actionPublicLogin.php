<?php

// login
function actionPublicLogin($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a, $strDeviceIDCookie_a)
{
    $strResult = "";

    if (dependencies('security/userLogin') &&
        dependencies('system/analytics')) 
	{
		// permission check
		if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}

        // parameters
        $strClientCode = str_replace(" ", "", getJSONParameter($arrParameters_a, 'clientcode'));
        $strLogin = str_replace(" ", "", getJSONParameter($arrParameters_a, 'login'));
        $strPassword = str_replace(" ", "", getJSONParameter($arrParameters_a, 'password'));
        $strStayLoggedIn = getJSONParameter($arrParameters_a, 'stayloggedin');
        $strUserAgent = getJSONParameter($arrParameters_a, 'useragent');
        $strCapabilities = getJSONParameter($arrParameters_a, 'capabilities');
        $strIPAddress = $_SERVER["REMOTE_ADDR"];
		$strClientDB = getSessionDB(__FUNCTION__);

        $blnResult = userLogin($objConn_a, $strSecurityToken_a, $strDeviceIDCookie_a, $strClientCode, $strLogin, $strPassword, $strStayLoggedIn, $strUserAgent, $strIPAddress, $strCapabilities, false, $strClientDB, false, '');
        if ($blnResult) 
		{
            analyticsTrack($strIPAddress, $strUserAgent, __FUNCTION__, 'login success');
            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', array());
        } 
		else 
		{
			updateSessionDB("system", "Session Lost", __FUNCTION__);	// temporary, in future it might be a lookup to cater for multiple hosts

            analyticsTrack($strIPAddress, $strUserAgent, __FUNCTION__, 'login failure');
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Invalid login.', array());
        }
    }

    return $strResult;
}
