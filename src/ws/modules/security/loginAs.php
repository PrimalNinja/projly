<?php

function loginAs($objConn_a, $strClientID_a, $blnLoginAs_a, $strLoginAs_a)
{
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameUser = getTableNameEntity("user", false);
	
    $blnResult = false;

    if (dependencies('security/userLogin,security/userLogout')) 
	{
		$strReturnToSecurityToken = "";
		$strReturnToDeviceIDCookie = "";
		$strReturnToClientDB = "";
		$strReturnToLoginAs = "";
		$strReturnToClientCode = "";
		$strReturnToLogin = "";
		$strReturnToUserAgent = "";
		$strReturnToIPAddress = "";

		if ($blnLoginAs_a)
		{
			// if logging in as we can return to here, maybe future we can have multiple levels of login by making an array here
			$strReturnToSecurityToken = $_SESSION['server_loggedin_token'];
			$strReturnToDeviceIDCookie = $_SESSION['server_loggedin_cookie'];
			$strReturnToClientDB = $_SESSION['server_clientdb'];
			$strReturnToLoginAs = $_SESSION['server_loginas'];
			$strReturnToClientCode = $_SESSION['server_loggedin_client'];
			$strReturnToLogin = $_SESSION['server_loggedin_user'];
			$strReturnToUserAgent = $_SESSION['server_loggedin_agent'];
			$strReturnToIPAddress = $_SESSION['server_loggedin_ipaddress'];
		}

		$strSecurityToken = $_SESSION['server_loggedin_token'];
		$strDeviceIDCookie = $_SESSION['server_loggedin_cookie'];
        $strClientID = $strClientID_a;
        $strUserID = "";
		$strUserAgent = $_SESSION['server_loggedin_agent'];
		$strIPAddress = $_SESSION['server_loggedin_ipaddress'];
		$strCapabilities = "";
		
		userLogout();
		
		$strSQL = "select user_id returnvalue from ~TABLENAMEACCOUNT~ where client_id = ~CLIENTID~";
		$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
		$strUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

		$strSQL = "select code returnvalue from ~TABLENAMECLIENT~ where id = ~CLIENTID~";
		$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
		$strClientCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

		$strSQL = "select login returnvalue from ~TABLENAMEUSER~ where client_id = ~CLIENTID~ and id = ~USERID~";
		$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
		$strLogin = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		$strClientDB = getSessionDB(__FUNCTION__);
		
		$blnResult = userLogin($objConn_a, $strSecurityToken, $strDeviceIDCookie, $strClientCode, $strLogin, '', '', 'N', $strUserAgent, $strIPAddress, $strCapabilities, true, $strClientDB, true, $strLoginAs_a);
		
		$_SESSION['server_returnto_token'] = $strReturnToSecurityToken;
		$_SESSION['server_returnto_cookie'] = $strReturnToDeviceIDCookie;
		$_SESSION['server_returnto_clientdb'] = $strReturnToClientDB;
		$_SESSION['server_returnto_loginas'] = $strReturnToLoginAs;
		$_SESSION['server_returnto_client'] = $strReturnToClientCode;
		$_SESSION['server_returnto_user'] = $strReturnToLogin;
		$_SESSION['server_returnto_agent'] = $strReturnToUserAgent;
		$_SESSION['server_returnto_ipaddress'] = $strReturnToIPAddress;
    }

    return $blnResult;
}
