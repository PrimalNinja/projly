<?php

// check computer authorisation
function actionPublicAuthenticationCheck($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a, $strDeviceIDCookie_a)
{
    $strResult = "";

    if (dependencies('security/userLoginByCookie') &&
        dependencies('system/analytics')) 
	{
		// permission check
		if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}
		
        // parameters
        $strUserAgent = getJSONParameter($arrParameters_a, 'useragent');
        $strCapabilities = getJSONParameter($arrParameters_a, 'capabilities');
        $strIPAddress = $_SERVER["REMOTE_ADDR"];

        $blnResult = userLoginByCookie($objConn_a, $strSecurityToken_a, $strDeviceIDCookie_a, $strUserAgent, $strIPAddress, $strCapabilities);

        if ($blnResult) 
		{
            analyticsTrack($strIPAddress, $strUserAgent, __FUNCTION__, 'authentication success');
            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', array());
        } 
		else 
		{
            analyticsTrack($strIPAddress, $strUserAgent, __FUNCTION__, 'authentication failure');
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Invalid authentication.', array());
        }
    }

    return $strResult;
}
