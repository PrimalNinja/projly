<?php

// login via a cookie
function userLoginByCookie($objConn_a, $strSecurityToken_a, $strDeviceIDCookie_a, $strUserAgent_a, $strIPAddress_a, $strCapabilities_a)
{
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameDevice = getTableNameEntity("device", false);
	$strTableNameUser = getTableNameEntity("user", false);
	
    $blnResult = false;

    if (dependencies('security/userLogin')) {
        $blnContinue = true;
        $strClientID = "";
        $strUserID = "";

        $strSQL = "select client_id, user_id from ~TABLENAMEDEVICE~ where info_devicecode = '~DEVICECODE~' and is_authenticated = 'Y'";
		$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
        $strSQL = str_replace('~DEVICECODE~', ff($strDeviceIDCookie_a), $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        if ($arrRow = dbReadRecord($objResult)) 
		{
            $strClientID = $arrRow['client_id'];
            $strUserID = $arrRow['user_id'];
        } 
		else 
		{
            $blnContinue = false;
        }
        dbCloseRecordset($objResult);

        if ($blnContinue == true) {
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
			
            $blnResult = userLogin($objConn_a, $strSecurityToken_a, $strDeviceIDCookie_a, $strClientCode, $strLogin, '', '', 'Y', $strUserAgent_a, $strIPAddress_a, $strCapabilities_a, true, $strClientDB, false, '');
        }
    }

    return $blnResult;
}
