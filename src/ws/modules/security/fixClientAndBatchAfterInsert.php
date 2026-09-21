<?php

// update an account's client and user
function fixClientAndBatchAfterInsert($objConn_a, $strAccountID_a, $strClientID_a, $strUserID_a)
{
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNameClient = getTableNameEntity("client", false);
	
	dbBeginTrans($objConn_a, __FUNCTION__);

	$strSQL = "update ~TABLENAMEACCOUNT~ set client_id = ~CLIENTID~, data_client_id = ~CLIENTID~, user_id = ~USERID~ where id = ~ACCOUNTID~";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace('~ACCOUNTID~', ff($strAccountID_a), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	//$strSQL = "update ~TABLENAMECLIENT~ set client_id = ~CLIENTID~, data_client_id = ~CLIENTID~ where id = ~CLIENTID~";
	//$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	//$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	//dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	
	return dbEndTrans($objConn_a, __FUNCTION__);
}
