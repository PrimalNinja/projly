<?php

function schemaChangeErrorSet($objConn_a, $strSchemaChangeID_a, $strError_a) 
{ 
    $strTableNameSchemaChange = getTableNameEntity('schemachange', false);
    
    // initialisations
    $strLogin = $_SESSION['server_loggedin_user'];

	$strSQL = "select jsondata returnvalue from ~TABLENAMESCHEMACHANGE~ where id = ~SCHEMACHANGEID~";
	$strSQL = str_replace('~TABLENAMESCHEMACHANGE~', ff($strTableNameSchemaChange), $strSQL);
	$strSQL = str_replace('~SCHEMACHANGEID~', ff($strSchemaChangeID_a), $strSQL);
	$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	$arrJSONData = json_decode($strJSONData, true);
	
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g523b5f4a-fc16-4f51-ad0c-c1cee86223bd", "ERROR", $strError_a);
	$strJSONData = json_encode($arrJSONData);
	
    dbBeginTrans($objConn_a, __FUNCTION__);

    $strSQL = "update ~TABLENAMESCHEMACHANGE~ set jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where id = ~SCHEMACHANGEID~";
    $strSQL = str_replace('~TABLENAMESCHEMACHANGE~', ff($strTableNameSchemaChange), $strSQL);
	$strSQL = str_replace('~SCHEMACHANGEID~', ff($strSchemaChangeID_a), $strSQL);
    $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
    $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
    $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	
	exposeEntityData($objConn_a, 'SYSTEMFORM', 'SCHEMACHANGE', $strSchemaChangeID_a, $strJSONData);
    
    return dbEndTrans($objConn_a, __FUNCTION__);
}