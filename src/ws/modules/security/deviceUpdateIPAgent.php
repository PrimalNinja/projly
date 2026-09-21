<?php

// update a devices ip address and agent
function deviceUpdateIPAgent($objConn_a, $strClientID_a, $strUserID_a, $strDeviceID_a, $strCode_a, $strDescription_a, $strIPAddress_a, $strUserAgent_a)
{
	$strTableNameDevice = getTableNameEntity("device", false);
	
	dbBeginTrans($objConn_a, __FUNCTION__);

	$strLogin = $_SESSION['server_loggedin_user'];

	$strSQL = "select jsondata returnvalue from ~TABLENAMEDEVICE~ where client_id = ~CLIENTID~ and user_id = ~USERID~ and id = ~DEVICEID~";
	$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
	$strSQL = str_replace('~DEVICEID~', ff($strDeviceID_a), $strSQL);
	$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	$arrJSONData = json_decode($strJSONData, true);

	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "INFO", "IPADDRESS", $strIPAddress_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "INFO", "USERAGENT", $strUserAgent_a);
	
	$strJSONData = json_encode($arrJSONData);
	
	$strSQL =
		"
update ~TABLENAMEDEVICE~
set jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~'
where client_id = ~CLIENTID~ and user_id = ~USERID~ and id = ~DEVICEID~
";
	$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
	$strSQL = str_replace('~DEVICEID~', ff($strDeviceID_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	
	exposeEntityData($objConn_a, 'SYSTEMFORM', 'DEVICE', $strDeviceID_a, $strJSONData);

	$strAlert = "updated device ipaddress '" . $strDescription_a . "' from ipaddress '" . $strIPAddress_a . "'";
	//alertAdd($objConn_a, $strClientID_a, $strUserID_a, $strAlert);
	
	return dbEndTrans($objConn_a, __FUNCTION__);;
}
