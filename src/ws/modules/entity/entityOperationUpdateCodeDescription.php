<?php

function entityOperationUpdateCodeDescription($objConn_a, $strClientID_a, $strEntityOperationID_a, $strCode_a, $strPermissionDesc_a)
{
	$strTableNameEntityOperation = getTableNameEntity("entityoperation", false);

	dbBeginTrans($objConn_a, __FUNCTION__);

	$strSQL = "select jsondata returnvalue from ~TABLENAMEENTITYOPERATION~ where id = ~ENTITYOPERATIONID~";
	$strSQL = str_replace("~TABLENAMEENTITYOPERATION~", ff($strTableNameEntityOperation), $strSQL);
	$strSQL = str_replace('~ENTITYOPERATIONID~', ff($strEntityOperationID_a), $strSQL);
	$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	$arrJSONData = json_decode($strJSONData, true);
	
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "CODE", $strCode_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "PERMISSIONDESC", $strPermissionDesc_a);

	$strJSONData = json_encode($arrJSONData);

	$strSQL = "update ~TABLENAMEENTITYOPERATION~ set code = '~CODE~', jsondata = '~JSONDATA~' where id = ~ENTITYOPERATIONID~";
	$strSQL = str_replace("~TABLENAMEENTITYOPERATION~", ff($strTableNameEntityOperation), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strCode_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~ENTITYOPERATIONID~', ff($strEntityOperationID_a), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	
	exposeEntityData($objConn_a, 'SYSTEMFORM', 'ENTITYOPERATION', $strEntityOperationID_a, $strJSONData);

	return dbEndTrans($objConn_a, __FUNCTION__);
}
