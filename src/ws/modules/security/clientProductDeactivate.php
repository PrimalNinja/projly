<?php

// deactivate a client product
function clientProductDeactivate($objConn_a, $strClientProductID_a)
{
	$strTableNameClientProduct = getTableNameEntity("clientproduct", false);
	
	dbBeginTrans($objConn_a, __FUNCTION__);

	$strLogin = $_SESSION['server_loggedin_user'];

	$strSQL = "select jsondata returnvalue from ~TABLENAMECLIENTPRODUCT~ where id = ~CLIENTPRODUCTID~";
	$strSQL = str_replace('~TABLENAMECLIENTPRODUCT~', ff($strTableNameClientProduct), $strSQL);
	$strSQL = str_replace('~CLIENTPRODUCTID~', ff($strClientProductID_a), $strSQL);
	$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	$arrJSONData = json_decode($strJSONData, true);

	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, "clientproduct");

	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff252aa5f0-e8df-4c52-a5c4-de677e1edf85", "ISENABLED", 'N');
	
	$strJSONData = json_encode($arrJSONData);

	$strSQL = "update ~TABLENAMECLIENTPRODUCT~ set jsondata = '~JSONDATA~', is_enabled = 'N', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where id = ~CLIENTPRODUCTID~";
	$strSQL = str_replace('~TABLENAMECLIENTPRODUCT~', ff($strTableNameClientProduct), $strSQL);
	$strSQL = str_replace('~CLIENTPRODUCTID~', ff($strClientProductID_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	exposeEntityData($objConn_a, 'SYSTEMFORM', 'CLIENTPRODUCT', $strClientProductID_a, $strJSONData);

	return dbEndTrans($objConn_a, __FUNCTION__);
}
