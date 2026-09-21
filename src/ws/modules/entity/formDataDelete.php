<?php

// ********* WARNING *********
//
// SIMILAR FUNCTIONS: entityDataDelete, formDataDelete - DO NOT CHANGE THIS WITHOUT REVIEWING ALL SIMILAR FUNCTIONS
// MATCH THE LETTERS IN THE SIMILAR FUNCTIONS: EVEN LINE NUMBERING BETWEEN SIMILAR FUNCTIONS IS THE SAME - KEEP THEM THE SAME
// notes: a client can delete their own systemform data
// 
// PSEUDOCODE:
//		A: work out which table to insert to
//		B: read previous record to later write to history
//		C: delete the record
//		D: add the previous record to history
//
function formDataDelete($objConn_a, $strClientID_a, $strEntityCode_a, $strFormEntityCode_a, $strFormEntityDataIDList_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{ 
	$strTableNameEntity = getTableNameEntity($strFormEntityCode_a, false);
	$strTableNameEntityExtension = getTableNameEntityExtension($strFormEntityCode_a);
	$strTableNameEntityHistory = getTableNameEntity($strFormEntityCode_a, true);
	
	$blnIsExtended = isExtended($objConn_a, $strFormEntityCode_a);

	$blnResult = false;
	$strFormEntityCodePlugin = $strFormEntityCode_a;
	$arrFormEntityDataIDList = explode(",", $strFormEntityDataIDList_a);
	$strFormDataID = '';

	// client bypass
	$blnIgnoreClient = false;
	if (InStr(',' . IGNORECLIENT_LISTERENTITIES . ',', ',' . ffeu($strFormEntityCodePlugin) . ',') >= 0)
	{
		$blnIgnoreClient = true;
	}

	if ($blnIgnoreClient == false)
	{
		if (InStr(',' . IGNORECLIENT_SYSTEMOWNERENTITES . ',', ',' . ffeu($strFormEntityCodePlugin) . ',') >= 0)
		{
			$blnIgnoreClient = ($strClientID_a == getSystemOwnerClientID($objConn_a));
		}
	}
	
	if ($blnIgnoreClient == false)
	{
		if (InStr(',' . IGNORECLIENT_SYSTEMADMIN . ',', ',' . ffeu($strFormEntityCodePlugin) . ',') >= 0)
		{
			$blnIgnoreClient = ($strClientID_a == getSystemClientID($objConn_a));
		}
	}
	
	$blnNoHistoryOnDelete = false;
	if (InStr(',' . IGNOREHISTORYONDELETE_ENTITES . ',', ',' . ffeu($strFormEntityCodePlugin) . ',') >= 0)
	{
		$blnNoHistoryOnDelete = true;
	}

	if (dependencies('entity/entityHistoryAddSystemForm')) 
	{
		// A:
		if (strlen($strTableNameEntity) > 0)
		{
			// fetch the record first. to be use in history table  
			// let's pull the current saved record first before updating it.
			// B:
			$strSQL = "";
			if ($blnIgnoreClient)
			{
				$strSQL = "select id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime from ~TABLENAMEENTITY~ where id in (~FORMDATAIDLIST~)";
			}
			else
			{
				$strSQL = "select id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime from ~TABLENAMEENTITY~ where client_id = ~CLIENTID~ and id in (~FORMDATAIDLIST~)";
			}
			$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
			$strSQL = str_replace('~FORMDATAIDLIST~', $strFormEntityDataIDList_a, $strSQL);

			$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
			$arrRow = dbReadRecord($objResult);
			// B:end

			dbBeginTrans($objConn_a, __FUNCTION__);

			// BEFORE DELETE
			if (dependencies('entity/beforeafter/' . ffel($strFormEntityCodePlugin), true)) { 
				$strBeforeAfterCallFunc = 'beforeDelete_' . ffel($strFormEntityCodePlugin);        

				for ($intI = 0; $intI < count($arrFormEntityDataIDList); $intI++) 
				{
					$strFormDataID = $arrFormEntityDataIDList[$intI];
					call_user_func($strBeforeAfterCallFunc, $objConn_a, $arrRow, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID, $strRelativeID_a, $strRelative_a, $strRelationship_a);
				}
			}				
			
			// C:
			$strSQL = "";
			if ($blnIsExtended)
			{
				if ($blnIgnoreClient)
				{
					$strSQL = "delete from ~TABLENAMEENTITYEXTENSION~ where id in (~FORMDATAIDLIST~)";
				}
				else
				{
					$strSQL = "delete from ~TABLENAMEENTITYEXTENSION~ where client_id = ~CLIENTID~ and id in (~FORMDATAIDLIST~)";
				}
				$strSQL = str_replace('~TABLENAMEENTITYEXTENSION~', ff($strTableNameEntityExtension), $strSQL);
				$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
				$strSQL = str_replace('~FORMDATAIDLIST~', $strFormEntityDataIDList_a, $strSQL);

				dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			}
			
			if ($blnIgnoreClient)
			{
				$strSQL = "delete from ~TABLENAMEENTITY~ where id in (~FORMDATAIDLIST~)";
			}
			else
			{
				$strSQL = "delete from ~TABLENAMEENTITY~ where client_id = ~CLIENTID~ and id in (~FORMDATAIDLIST~)";
			}
			$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
			$strSQL = str_replace('~FORMDATAIDLIST~', $strFormEntityDataIDList_a, $strSQL);

			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			// C:end
			
			if ($arrRow) 
			{ 
				// D:
				if ($blnNoHistoryOnDelete == false)
				{
					entityHistoryAddSystemForm($objConn_a, $strTableNameEntityHistory, $strClientID_a, $arrRow['id'], $arrRow['entity_id'], $arrRow['dataentity_id'], $arrRow['code'], $arrRow['description'], $arrRow['is_enabled'], $arrRow['data_client_id'], $arrRow['jsondata'], $arrRow['modifyuser'], $arrRow['modifydatetime']);
				}
				// D:end
			}        
			
			// AFTER DELETE
			if (dependencies('entity/beforeafter/' . ffel($strFormEntityCodePlugin), true)) 
			{ 
				$strBeforeAfterCallFunc = 'afterDelete_' . ffel($strFormEntityCodePlugin);        

				for ($intI = 0; $intI < count($arrFormEntityDataIDList); $intI++) 
				{
					$strFormDataID = $arrFormEntityDataIDList[$intI];
					call_user_func($strBeforeAfterCallFunc, $objConn_a, $arrRow, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID, $strRelativeID_a, $strRelative_a, $strRelationship_a);
				}
			}
			
			$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
		}
		// A:end
	}
	
	// logging
    if (dependencies('utils/log')) 
	{
		for ($intI = 0; $intI < count($arrFormEntityDataIDList); $intI++) 
		{
			$strFormDataID = $arrFormEntityDataIDList[$intI];
			createAuditEntityDeleteDataLog($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strFormDataID);
		}
	}
	
	return $blnResult;
}
