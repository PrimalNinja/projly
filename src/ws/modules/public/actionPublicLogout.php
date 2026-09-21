<?php

// logout
function actionPublicLogout($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strResult = "";
	$strTableNameClient = getTableNameEntity("client", false);

//file_put_contents("d:\dev\session.log", " actionCoreLogout.php 1:" . $strFunction . "\n", FILE_APPEND);	// DEBUGSESSION

    if (dependencies('security/deviceAuthenticate,security/userLogout,system/analytics')) {
		// permission check
		if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}

        // initialisations
        $strClientID = $_SESSION['server_loggedin_clientid'];
        $strUserID = $_SESSION['server_loggedin_userid'];
        $strDeviceID = $_SESSION['server_deviceid'];
        $strDeviceIDCookie = $_SESSION['server_loggedin_cookie'];
		$strLogin = $_SESSION['server_loggedin_user'];

		if (strlen($strClientID) > 0)
		{
			$strSQL = "select code returnvalue from ~TABLENAMECLIENT~ where id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
			$strClientCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
			$blnIsPublic = ((strtolower($strClientCode) == strtolower(PUBLIC_CLIENT)) && (strtolower($strLogin) == strtolower(PUBLIC_LOGIN))); // this one doesn't let the actual public user login;
			
			if (($blnIsPublic) || (strlen($strDeviceID) == 0))
			{
				// do nothing for totally public users here as it wastes our log table space
			}
			else
			{
				$strDevicePushToken_a = $strSecurityToken_a;
				deviceAuthenticate($objConn_a, $strClientID, $strUserID, $strDeviceID, $strDeviceIDCookie, $strDevicePushToken_a, 'N');
			}

			analyticsTrack($_SESSION['server_loggedin_ipaddress'], $_SESSION['server_loggedin_agent'], __FUNCTION__, 'logout');
		}
//file_put_contents("d:\dev\session.log", " actionPublicLogout.php 2:" . $strFunction . "\n", FILE_APPEND);	// DEBUGSESSION

        userLogout();
//file_put_contents("d:\dev\session.log", " actionPublicLogout.php 3:" . $strFunction . "\n", FILE_APPEND);	// DEBUGSESSION
        $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', array());
    }

    return $strResult;
}
