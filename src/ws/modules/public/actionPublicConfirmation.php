<?php

// confirmation
function actionPublicConfirmation($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a, $strDeviceIDCookie_a)
{
    $strResult = "";
    $strConfirmationType = "";

    if (dependencies('security/userRegisterConfirmation') &&
        dependencies('security/resetPasswordConfirmation') &&
        dependencies('security/userConfirmationTypeFetch') &&
        dependencies('system/analytics')) 
	{
		
		// permission check
		if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}

        // parameters
		$strIPAddress = $_SERVER["REMOTE_ADDR"];
		$strCapabilities = "";
        $strConfirmationType = userConfirmationTypeFetch($objConn_a, $strSecurityToken_a);

        if ($strConfirmationType == 'RESET_PASSWORD')
        {
            $strResult = resetPasswordConfirmation($objConn_a, false, $strSecurityToken_a, $strIPAddress);

			if (strlen($strResult) > 0)
			{
				header("Location: " . URL_PWDCONFIRM_PATH);
				safetyDie('Password Reset Confirmation Success.');

			} 
			else 
			{
				header("Location: " . URL_PWDFAIL_PATH);
				safetyDie('Password Reset Confirmation Failure.');
			}
        }

        if ($strConfirmationType == 'CONFIRM_REGISTRATION')
        {
            $strResult = userRegisterConfirmation($objConn_a, false, $strSecurityToken_a, $strIPAddress, $strCapabilities);

			if (strlen($strResult) > 0)
			{
				analyticsTrack($strIPAddress, '', __FUNCTION__, 'register confirmation success');
				header("Location: " . URL_REGCONFIRM_PATH);
				safetyDie('Registration Confirmation Success.');

			} 
			else 
			{
				analyticsTrack($strIPAddress, '', __FUNCTION__, 'register confirmation failure');
				header("Location: " . URL_REGFAIL_PATH);
				safetyDie('Registration Confirmation Failure.');
			}
        }

		safetyDie('Unknown Confirmation.');
    }

    return $strResult;
}
