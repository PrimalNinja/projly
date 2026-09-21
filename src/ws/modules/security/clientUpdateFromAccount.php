<?php

// update a client from the account form
function clientUpdateFromAccount($objConn_a, $strAccountID_a, $strDescription_a, $strEmailAddress_a)
{
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNameClient = getTableNameEntity("client", false);
	
	dbBeginTrans($objConn_a, __FUNCTION__);

	$strLogin = $_SESSION['server_loggedin_user'];

	$strSQL = "select client_id returnvalue from ~TABLENAMEACCOUNT~ where id = ~ACCOUNTID~";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace('~ACCOUNTID~', ff($strAccountID_a), $strSQL);
	$strClientID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	$strSQL = "select jsondata returnvalue from ~TABLENAMECLIENT~ where id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
	$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	$arrJSONData = json_decode($strJSONData, true);
	
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f8d9bfb3a-3eb0-4620-ad86-af7b4e222325", "DESCRIPTION", $strDescription_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f8d9bfb3a-3eb0-4620-ad86-af7b4e222325", "EMAILADDRESS", $strEmailAddress_a);
	
	$strJSONData = json_encode($arrJSONData);
	
	$strSQL = "update ~TABLENAMECLIENT~ set description = '~DESCRIPTION~', jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strDescription_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	
	exposeEntityData($objConn_a, 'SYSTEMFORM', 'CLIENT', $strClientID, $strJSONData);

	return dbEndTrans($objConn_a, __FUNCTION__);
}
