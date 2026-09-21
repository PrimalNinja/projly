<?php

function actionPublicVerification($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a, $strDeviceIDCookie_a)
{
    $strResult = "";

    if (dependencies('security/userRegisterVerification') &&
        dependencies('system/analytics')) {
        
		// permission check
		if (!hasPermission($objConn_a, 'NOCHECK',  __FUNCTION__, true)) {return false;}

        // parameters
        $strIPAddress = $_SERVER["REMOTE_ADDR"];
        $strCapabilties = "";
        
        $strResult = userRegisterVerification($objConn_a, false, $strSecurityToken_a, $strIPAddress, $strCapabilties);

        if (strlen($strResult) > 0)
        {
            analyticsTrack($strIPAddress, '', __FUNCTION__, 'register verification success');
            header("Location: " . URL_VERCONFIRM_PATH);
            safetyDie('Registration Verification Success.');
        } 
		else 
		{
            analyticsTrack($strIPAddress, '', __FUNCTION__, 'register verification failure');
            header("Location: " . URL_VERFAIL_PATH);
            safetyDie('Registration Verification Failure.');
        }

		safetyDie('Unknown Confirmation.');
    }
    
    die();
    return $strResult;
}
