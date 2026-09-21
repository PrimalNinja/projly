<?php

// authenticate a device
// note: because the cookie is shared for multiple users on the same pc, we remove it from all previous users if we authenticate against a new user
function deviceAuthenticate($objConn_a, $strClientID_a, $strUserID_a, $strDeviceID_a, $strDeviceIDCookie_a, $strDevicePushToken_a, $strStayLoggedIn_a)
{
	$strTableNameDevice = getTableNameEntity("device", false);
	
    dbBeginTrans($objConn_a, __FUNCTION__);

    $strSQL = "update ~TABLENAMEDEVICE~ set devicepushtoken = '~DEVICEPUSHTOKEN~', is_authenticated = 'N' where info_devicecode = '~DEVICECODE~'";
	$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
    $strSQL = str_replace('~DEVICECODE~', ff($strDeviceIDCookie_a), $strSQL);
	$strSQL = str_replace('~DEVICEPUSHTOKEN~', ff($strDevicePushToken_a), $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

    $strSQL =
        "
update ~TABLENAMEDEVICE~
set is_authenticated = '~ISAUTHENTICATED~'
where client_id = ~CLIENTID~ and user_id = ~USERID~ and id = ~DEVICEID~
";
	$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
    $strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
    $strSQL = str_replace('~DEVICEID~', ff($strDeviceID_a), $strSQL);
    $strSQL = str_replace('~ISAUTHENTICATED~', ff($strStayLoggedIn_a), $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

    return dbEndTrans($objConn_a, __FUNCTION__);
}
