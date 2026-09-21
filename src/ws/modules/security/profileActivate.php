<?php

// activate a profile
function profileActivate($objConn_a, $strClientID_a, $strProfileID_a)
{
	$strTableNameProfile = getTableNameEntity("profile", false);
	
	dbBeginTrans($objConn_a, __FUNCTION__);

	$strLogin = $_SESSION['server_loggedin_user'];

	$strSQL = "select jsondata returnvalue from ~TABLENAMEPROFILE~ where client_id = ~CLIENTID~ and id = ~PROFILEID~";
	$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~PROFILEID~', ff($strProfileID_a), $strSQL);
	$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	$arrJSONData = json_decode($strJSONData, true);

	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, "profile");

	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f9a0f134f-54b8-4e11-81b3-02d4f51dadd0", "ISENABLED", 'Y');
	
	$strJSONData = json_encode($arrJSONData);

	$strSQL = "update ~TABLENAMEPROFILE~ set jsondata = '~JSONDATA~', is_enabled = 'Y', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where client_id = ~CLIENTID~ and id = ~PROFILEID~";
	$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~PROFILEID~', ff($strProfileID_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	exposeEntityData($objConn_a, 'SYSTEMFORM', 'PROFILE', $strProfileID_a, $strJSONData);

	return dbEndTrans($objConn_a, __FUNCTION__);
}
