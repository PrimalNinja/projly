<?php

// login
function verifyClientDB($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a, $strDeviceIDCookie_a)
{
	$strTableNameMyClient = getTableNameEntity("myclient", false);

    $blnResult = false;

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

	$strSQL = "select actualclient_id returnvalue from ~TABLENAMEMYCLIENT~ where g52b14f56_ab19_4418_bf74_b25b5c2519ee_code = '~CLIENTCODE~' and is_defined = 'Y'";
	$strSQL = str_replace('~TABLENAMEMYCLIENT~', ff($strTableNameMyClient), $strSQL);
	$strSQL = str_replace('~CLIENTCODE~', ff($strClientCode), $strSQL);
	$strClientID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	logSecurity('verifyClientDB: ' . $strSQL, '');

	if (strlen($strClientID) > 0)
	{
		updateSessionDB("client", "Client", __FUNCTION__);	// temporary, in future it might be a lookup to cater for multiple hosts
		$blnResult = true;
	}

    return $blnResult;
}
