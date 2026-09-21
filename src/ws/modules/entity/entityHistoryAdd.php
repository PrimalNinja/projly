<?php

function entityHistoryAdd($objConn_a, $strTableNameEntityHistory_a, $strClientID_a, $strEntityDataID_a, $strEntityID_a, $strDataEntityID_a, $strCode_a, $strDescription_a, $strIsEnabled_a, $strDataClientID_a, $strJSONData_a, $strModifyUser_a, $strModifyDateTime_a) 
{ 
	$strResult = '';
	
    dbBeginTrans($objConn_a, __FUNCTION__);
    
	dbExecuteSQL($objConn_a,"set FOREIGN_KEY_CHECKS = 0", __FUNCTION__);
	$strSQL = "insert into ~TABLENAMEENTITYHISTORY~ (client_id, entitydata_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime) values(~CLIENTID~, ~ENTITYDATAID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~IS_ENABLED~', ~DATACLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~')";
	$strSQL = str_replace('~TABLENAMEENTITYHISTORY~', ff($strTableNameEntityHistory_a), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~ENTITYDATAID~', ff($strEntityDataID_a), $strSQL);
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID_a), $strSQL);
	$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID_a), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strCode_a), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strDescription_a), $strSQL);
	$strSQL = str_replace('~IS_ENABLED~', ff($strIsEnabled_a), $strSQL);
	$strSQL = str_replace('~DATACLIENTID~', ff($strDataClientID_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData_a), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strModifyUser_a), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', ff($strModifyDateTime_a), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	dbExecuteSQL($objConn_a,"set FOREIGN_KEY_CHECKS = 1", __FUNCTION__);
	$strResult = dbLastInsertID($objConn_a);    
    
    dbEndTrans($objConn_a, __FUNCTION__);
    
    return $strResult;
}