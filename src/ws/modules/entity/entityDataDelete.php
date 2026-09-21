<?php

// ********* WARNING *********
//
// SIMILAR FUNCTIONS: entityDataDelete, formDataDelete - DO NOT CHANGE THIS WITHOUT REVIEWING ALL SIMILAR FUNCTIONS
// MATCH THE LETTERS IN THE SIMILAR FUNCTIONS: EVEN LINE NUMBERING BETWEEN SIMILAR FUNCTIONS IS THE SAME - KEEP THEM THE SAME
// notes: a client can delete their own entity data
// 
// PSEUDOCODE:
//		A: work out which table to insert to
//		B: read previous record to later write to history
//		C: delete the record
//		D: add the previous record to history
//
function entityDataDelete($objConn_a, $strClientID_a, $strEntityCode_a, $strEntityDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{ 
	$strTableNameEntityCode = getTableNameEntity($strEntityCode_a, false);
	$strTableNameEntityCodeExtension = getTableNameEntityExtension($strEntityCode_a);
	$strTableNameEntityCodeHistory = getTableNameEntity($strEntityCode_a, true);

	$blnIsExtended = isExtended($objConn_a, $strEntityCode_a);

	$blnResult = false;
	$strEntityCodePlugin = $strEntityCode_a;
	
	// client bypass
	$blnIgnoreClient = false;
	if (InStr(',' . IGNORECLIENT_LISTERENTITIES . ',', ',' . ffeu($strEntityCode_a) . ',') >= 0)
	{
		$blnIgnoreClient = true;
	}
	
	if ($blnIgnoreClient == false)
	{
		if (InStr(',' . IGNORECLIENT_SYSTEMOWNERENTITES . ',', ',' . ffeu($strEntityCode_a) . ',') >= 0)
		{
			$blnIgnoreClient = ($strClientID_a == getSystemOwnerClientID($objConn_a));
		}
	}
	
	if ($blnIgnoreClient == false)
	{
		if (InStr(',' . IGNORECLIENT_SYSTEMADMIN . ',', ',' . ffeu($strEntityCode_a) . ',') >= 0)
		{
			$blnIgnoreClient = ($strClientID_a == getSystemClientID($objConn_a));
		}
	}
	
	$blnNoHistoryOnDelete = false;
	if (InStr(',' . IGNOREHISTORYONDELETE_ENTITES . ',', ',' . ffeu($strEntityCode_a) . ',') >= 0)
	{
		$blnNoHistoryOnDelete = true;
	}

	if (dependencies('entity/entityHistoryAdd')) 
	{ 
		
		
		
		
		
		
		
		
		
		
		
		// fetch the record first. to be use in history table  
		// let's pull the current saved record first before updating it.
		// B:
		$strSQL = "";
		if ($blnIgnoreClient)
		{
			$strSQL = "select id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime from ~TABLENAMEENTITYCODE~ where id = ~ENTITYDATAID~";
		}
		else
		{
			$strSQL = "select id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime from ~TABLENAMEENTITYCODE~ where client_id = ~CLIENTID~ and id = ~ENTITYDATAID~";
		}
		$strSQL = str_replace('~TABLENAMEENTITYCODE~', ff($strTableNameEntityCode), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~ENTITYDATAID~', ff($strEntityDataID_a), $strSQL);

		$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		$arrRow = dbReadRecord($objResult);
		// B:end
			
		dbBeginTrans($objConn_a, __FUNCTION__);

		// BEFORE DELETE
		if (dependencies('entity/beforeafter/' . ffel($strEntityCodePlugin), true)) 
		{ 
			$strBeforeAfterCallFunc = 'beforeDelete_' . ffel($strEntityCodePlugin);        
			call_user_func($strBeforeAfterCallFunc, $objConn_a, $arrRow, $strEntityCode_a, '', $strClientID_a, $strEntityDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a);
		}
		
		// C:
        
		$strSQL = "";
		if ($blnIsExtended)
		{
			if ($blnIgnoreClient)
			{
				$strSQL = "delete from ~TABLENAMEENTITYCODEEXTENSION~ where id = ~ENTITYDATAID~";
			}
			else
			{
				$strSQL = "delete from ~TABLENAMEENTITYCODEEXTENSION~ where client_id = ~CLIENTID~ and id = ~ENTITYDATAID~";
			}
			$strSQL = str_replace('~TABLENAMEENTITYCODEEXTENSION~', ff($strTableNameEntityCodeExtension), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
			$strSQL = str_replace('~ENTITYDATAID~', ff($strEntityDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		}
		
		if ($blnIgnoreClient)
		{
			$strSQL = "delete from ~TABLENAMEENTITYCODE~ where id = ~ENTITYDATAID~";
		}
		else
		{
			$strSQL = "delete from ~TABLENAMEENTITYCODE~ where client_id = ~CLIENTID~ and id = ~ENTITYDATAID~";
		}
		$strSQL = str_replace('~TABLENAMEENTITYCODE~', ff($strTableNameEntityCode), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~ENTITYDATAID~', ff($strEntityDataID_a), $strSQL);
        
        
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		// C:end
		
		if ($arrRow) 
		{
			// D:
			if ($blnNoHistoryOnDelete == false)
			{
				entityHistoryAdd($objConn_a, $strTableNameEntityCodeHistory, $strClientID_a, $arrRow['id'], $arrRow['entity_id'], $arrRow['dataentity_id'], $arrRow['code'], $arrRow['description'], $arrRow['is_enabled'], $arrRow['data_client_id'], $arrRow['jsondata'], $arrRow['modifyuser'], $arrRow['modifydatetime']);
			}
			// D:end
		}        
		
		// AFTER DELETE
		if (dependencies('entity/beforeafter/' . ffel($strEntityCodePlugin), true)) 
		{ 
			$strBeforeAfterCallFunc = 'afterDelete_' . ffel($strEntityCodePlugin);        
			call_user_func($strBeforeAfterCallFunc, $objConn_a, $arrRow, $strEntityCode_a, '', $strClientID_a, $strEntityDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a);
		}
		
		$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
	}
	
	return $blnResult;
}
