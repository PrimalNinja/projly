<?php

// return
function userReturn($objConn_a)
{
	global $api;	// the global webservice
	
	$blnResult = false;
	
    if (dependencies('security/userLogin')) 
	{
		$strReturnToSecurityToken = $_SESSION['server_returnto_token'];
		$strReturnToDeviceIDCookie = $_SESSION['server_returnto_cookie'];
		$strReturnToClientDB = $_SESSION['server_returnto_clientdb'];
		$strReturnToLoginAs = $_SESSION['server_returnto_loginas'];
		$strReturnToClientCode = $_SESSION['server_returnto_client'];
		$strReturnToLogin = $_SESSION['server_returnto_user'];
		$strReturnToUserAgent = $_SESSION['server_returnto_agent'];
		$strReturnToIPAddress = $_SESSION['server_returnto_ipaddress'];
		$strCapabilities = "";

		if (strlen($strReturnToClientDB) > 0)
		{
			$objConn = $objConn_a;
			if ($_SESSION['server_clientdb'] == $strReturnToClientDB)
			{
				logSecurity('DB not switched on return to user in userReturn', '');
			}
			else
			{
				//clearSession();
				logSecurity('DB switched to ' . $strReturnToClientDB . ' in userReturn', '');
				$objConn = $api::newDB($strReturnToClientDB); 	// since we are likely in a different db, then re-initialise the DB before actually loggingin
			}
			
			$blnResult = userLogin($objConn, $strReturnToSecurityToken, $strReturnToDeviceIDCookie, $strReturnToClientCode, $strReturnToLogin, '', '', 'N', $strReturnToUserAgent, $strReturnToIPAddress, $strCapabilities, true, $strReturnToClientDB, true, $strReturnToLoginAs);
			if ($blnResult)
			{
				updateSessionDB($strReturnToClientDB, $strReturnToLoginAs, __FUNCTION__);
			}
		}
	}

    return $blnResult;
}
