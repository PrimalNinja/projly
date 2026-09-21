<?php

// deactivate a client
function clientDeactivate($objConn_a, $strClientID_a)
{
	$strTableNameClient = getTableNameEntity("client", false);
	
	dbBeginTrans($objConn_a, __FUNCTION__);

	$strLogin = $_SESSION['server_loggedin_user'];

	$strSQL = "select jsondata returnvalue from ~TABLENAMECLIENT~ where id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	$arrJSONData = json_decode($strJSONData, true);

	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, "client");

	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f8d9bfb3a-3eb0-4620-ad86-af7b4e222325", "ISENABLED", 'N');
	
	$strJSONData = json_encode($arrJSONData);

	$strSQL = "update ~TABLENAMECLIENT~ set jsondata = '~JSONDATA~', is_enabled = 'N', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where client_id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	exposeEntityData($objConn_a, 'SYSTEMFORM', 'CLIENT', $strClientID_a, $strJSONData);

	return dbEndTrans($objConn_a, __FUNCTION__);
}
