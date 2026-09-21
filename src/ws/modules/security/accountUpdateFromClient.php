<?php

// update an account from the client form
function accountUpdateFromClient($objConn_a, $strClientID_a, $strCode_a, $strDescription_a, $strEmailAddress_a, $strPhoneNumber_a, $strEnabled_a)
{
	$strTableNameAccount = getTableNameEntity("account", false);
	
	dbBeginTrans($objConn_a, __FUNCTION__);

	$strLogin = $_SESSION['server_loggedin_user'];

	$strSQL = "select id returnvalue from ~TABLENAMEACCOUNT~ where client_id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strAccountID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	$strSQL = "select jsondata returnvalue from ~TABLENAMEACCOUNT~ where id = ~ACCOUNTID~";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace('~ACCOUNTID~', ff($strAccountID), $strSQL);
	$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	$arrJSONData = json_decode($strJSONData, true);
		
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "CLIENTCODE", $strCode_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "ACCOUNTNAME", $strDescription_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "ACCOUNTEMAILADDRESS", $strEmailAddress_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "PHONENUMBER", $strPhoneNumber_a);
	
	$strJSONData = json_encode($arrJSONData);

	$strSQL = "update ~TABLENAMEACCOUNT~ set code = '~CODE~', description = '~DESCRIPTION~', is_enabled = '~ENABLED~', jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where id = ~ACCOUNTID~";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace('~ACCOUNTID~', ff($strAccountID), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strCode_a), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strDescription_a), $strSQL);
	$strSQL = str_replace('~ENABLED~', ff($strEnabled_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	exposeEntityData($objConn_a, 'SYSTEMFORM', 'ACCOUNT', $strAccountID, $strJSONData);

	return dbEndTrans($objConn_a, __FUNCTION__);
}
