<?php

function permissionUpdateCodeDescription($objConn_a, $strClientID_a, $strPermissionID_a, $strCode_a, $strDescription_a)
{
	$strTableNamePermission = getTableNameEntity("permission", false);

	$strLogin = $_SESSION['server_loggedin_user'];
	
	dbBeginTrans($objConn_a, __FUNCTION__);

	$strSQL = "select jsondata returnvalue from ~TABLENAMEPERMISSION~ where id = ~PERMISSIONID~";
	$strSQL = str_replace("~TABLENAMEPERMISSION~", ff($strTableNamePermission), $strSQL);
	$strSQL = str_replace('~PERMISSIONID~', ff($strPermissionID_a), $strSQL);
	$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	$arrJSONData = json_decode($strJSONData, true);
	
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "0000eae3-e5b8-4ebb-a3a8-50220cee15d5", "CODE", $strCode_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "0000eae3-e5b8-4ebb-a3a8-50220cee15d5", "DESCRIPTION", $strDescription_a);

	$strJSONData = json_encode($arrJSONData);

	$strSQL = "update ~TABLENAMEPERMISSION~ set code = '~CODE~', description = '~DESCRIPTION~', jsondata = '~JSONDATA~' where id = ~PERMISSIONID~";
	$strSQL = str_replace("~TABLENAMEPERMISSION~", ff($strTableNamePermission), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strCode_a), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strDescription_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~PERMISSIONID~', ff($strPermissionID_a), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	
	exposeEntityData($objConn_a, 'SYSTEMFORM', 'PERMISSION', $strPermissionID_a, $strJSONData);

	return dbEndTrans($objConn_a, __FUNCTION__);
}
