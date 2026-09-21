<?php

// register a device
function deviceRegister($objConn_a, $strClientID_a, $strUserID_a, $strCode_a, $strDescription_a, $strDevicePushToken_a, $strIPAddress_a, $strUserAgent_a, $strCapabilities_a)
{
	$strTableNameDevice = getTableNameEntity("device", false);
	
    $strDeviceID = "";

    if (dependencies('security/deviceAdd,security/deviceUpdateIPAgent')) 
	{
        $strCode = 'Unnamed';
        if (strlen($strCode_a) > 0) 
		{
            $strCode = $strCode_a;
        }

        dbBeginTrans($objConn_a, __FUNCTION__);

        // register the computer (if not already)
        $strSQL = "select id returnvalue from ~TABLENAMEDEVICE~ where client_id = ~CLIENTID~ and user_id = ~USERID~ and info_devicecode = '~DEVICECODE~'";
		$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
        $strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
        $strSQL = str_replace('~DEVICECODE~', ff($strCode), $strSQL);
        $strDeviceID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        if (strlen($strDeviceID) == 0) 
		{
            $strDeviceID = deviceAdd($objConn_a, $strClientID_a, $strUserID_a, $strCode, $strDescription_a, $strIPAddress_a, $strUserAgent_a, $strCapabilities_a);
        } 
		else 
		{
            $strSQL = "select description returnvalue from ~TABLENAMEDEVICE~ where client_id = ~CLIENTID~ and info_devicecode = '~DEVICECODE~'";
			$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
            $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
            $strSQL = str_replace('~DEVICECODE~', ff($strCode), $strSQL);
            $strDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

            deviceUpdateIPAgent($objConn_a, $strClientID_a, $strUserID_a, $strDeviceID, $strCode, $strDescription, $strIPAddress_a, $strUserAgent_a);
        }

        dbEndTrans($objConn_a, __FUNCTION__);
    }

    return $strDeviceID;
}
