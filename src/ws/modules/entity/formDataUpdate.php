<?php

// ********* WARNING *********
//
// SIMILAR FUNCTIONS: entityDataUpdate, formDataUpdate - DO NOT CHANGE THIS WITHOUT REVIEWING ALL SIMILAR FUNCTIONS
// 
// PSEUDOCODE:
//		A: read header details
//		B: work out which table to insert to
//		C: read old for later inserting into history
//		D: update the table
//		E: insert old data into history
//		F: not applicable
//		G: not applicable
//
function formDataUpdate($objConn_a, $strClientID_a, $strEntityID_a, $strEntityCode_a, $strFormEntityCode_a, $strFormDataID_a, $strDataClientID_a, $arrJSONData_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{ 
	$strTableNameEntity = getTableNameEntity($strFormEntityCode_a, false);
	$strTableNameEntityHistory = getTableNameEntity($strFormEntityCode_a, true);
	
	$strFormEntityCodePlugin = $strFormEntityCode_a;
	$arrJSONData = $arrJSONData_a;
    $strLogin = $_SESSION['server_loggedin_user'];
   
    if (dependencies('entity/entityHistoryAddSystemForm')) 
    {
		// data has a heirarchical lookup, first data section, then formheader section
		// get the values from the data section
		// A: note, for formData, we read from the DATA section then the FORMHEADER section if not found yet
		$strCode = formValueGetBySectionTypeFieldCode($arrJSONData, 'DATA', 'code');
		$strDescription = formValueGetBySectionTypeFieldCode($arrJSONData, 'DATA', 'description');
		$strIsEnabled = formValueGetBySectionTypeFieldCode($arrJSONData, 'DATA', 'isenabled');
		
		// get the values from the data header if not yet found
		if (strlen($strCode) == 0) { $strCode = formValueGetBySectionTypeFieldCode($arrJSONData, 'FORMHEADER', 'code'); }
		if (strlen($strDescription) == 0) { $strDescription = formValueGetBySectionTypeFieldCode($arrJSONData, 'FORMHEADER', 'description'); }
		if (strlen($strIsEnabled) == 0) { $strIsEnabled = formValueGetBySectionTypeFieldCode($arrJSONData, 'FORMHEADER', 'isenabled'); }
		// A: end

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
		
        // massage data (such as dates and times) that might not be in the correct format
        $arrJSONData = formMassageData($arrJSONData);

		$strJSONData = json_encode($arrJSONData);
		
		// B:
//		if ((strlen($strTableNameEntity) > 0) && (strlen($strFormDataID_a) > 0))
//		{
			// fetch the record first. to be use in history table  
			// let's pull the current saved record first before updating it.
			// C:
			$strSQL = "";
			if ($blnIgnoreClient)
			{
				$strSQL = "select id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime from ~TABLENAMEENTITY~ where id = ~FORMDATAID~";
			}
			else
			{
				$strSQL = "select id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime from ~TABLENAMEENTITY~ where client_id = ~CLIENTID~ and id = ~FORMDATAID~";
			}
			$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
			$strSQL = str_replace('~FORMDATAID~', ff($strFormDataID_a), $strSQL);
			
			$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
			$arrRow = dbReadRecord($objResult);
			// C:end
			
//		}

		// update
		//check CRC's before updating the record
		//if (crc32($arrRow['jsondata']) != crc32($strJSONData))
		//{
			//get dataversion 
			$intDataVersion = intval(formValueGetBySectionTypeFieldCode($arrJSONData, 'DATAHEADER', 'dataversion'));
			//set new dataversion
			$intNewDataVersion = $intDataVersion + 1;
			$arrJSONData = formValueUpdateBySectionTypeFieldCode($arrJSONData, 'DATAHEADER', 'dataversion', $intNewDataVersion);

			dbBeginTrans($objConn_a, __FUNCTION__);

			// BEFORE UPDATE
			if (dependencies('entity/beforeafter/' . ffel($strFormEntityCode_a), true)) 
			{ 
				$strBeforeAfterCallFunc = 'beforeAddUpdate_' . ffel($strFormEntityCode_a);        
				$arrJSONData = call_user_func($strBeforeAfterCallFunc, $objConn_a, $arrRow, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, true);
			}
										
			$strJSONData = json_encode($arrJSONData);
			
			// D:
			$strSQL = "";
			if ($blnIgnoreClient)
			{
				$strSQL = "update ~TABLENAMEENTITY~ set code = '~CODE~', description = '~DESCRIPTION~', is_enabled = '~IS_ENABLED~', jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where id = ~FORMDATAID~";
			}
			else
			{
				$strSQL = "update ~TABLENAMEENTITY~ set code = '~CODE~', description = '~DESCRIPTION~', is_enabled = '~IS_ENABLED~', jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where client_id = ~CLIENTID~ and id = ~FORMDATAID~";
			}
			$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
			$strSQL = str_replace('~FORMDATAID~', ff($strFormDataID_a), $strSQL);
			$strSQL = str_replace('~CODE~', ff($strCode), $strSQL);
			$strSQL = str_replace('~DESCRIPTION~', ff($strDescription), $strSQL);
			$strSQL = str_replace('~IS_ENABLED~', ff($strIsEnabled), $strSQL);
			$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
			$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
			$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
			
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			// D:end
			
			// JC: the following code for now is not needed as when we update a form's data, we are not changing the schema
			//if (strlen($strFormEntityCode_a) > 0)
			//{
//dbRaiseCustomError($objConn_a, "formDataUpdate");
				//exposeEntityFields($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strDataEntityID, $strClientID_a, $strFormDataID_a, $strJSONData);
			//}
		
			if ($arrRow) 
			{ 
				// E:
				entityHistoryAddSystemForm($objConn_a, $strTableNameEntityHistory, $strClientID_a, $arrRow['id'], $arrRow['entity_id'], $arrRow['dataentity_id'], $arrRow['code'], $arrRow['description'], $arrRow['is_enabled'], $arrRow['data_client_id'], $arrRow['jsondata'], $arrRow['modifyuser'], $arrRow['modifydatetime']);
				// E:end
				
				// F: not applicable
				// G: not applicable
			}
	
			dbCloseRecordset($objResult);
			
			// AFTER UPDATE
			if (dependencies('entity/beforeafter/' . ffel($strFormEntityCode_a), true)) 
			{ 
				$strBeforeAfterCallFunc = 'afterAddUpdate_' . ffel($strFormEntityCode_a);        
				$arrJSONData = call_user_func($strBeforeAfterCallFunc, $objConn_a, $arrRow, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, true);
			}

			if (strlen($strFormEntityCode_a) > 0)
			{
				transferEntityData($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strJSONData);
			}
			
			$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
				
			//if ($blnResult && (strlen($strFormEntityCode_a) > 0))
			//{
				//exposeEntityData($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strFormDataID_a, $strJSONData);	// updating a form we don't have schema changes
			//}

			// AFTER UPDATE
			if (dependencies('entity/beforeafter/' . ffel($strFormEntityCode_a), true)) 
			{ 
				$strBeforeAfterCallFunc = 'afterAddUpdateExpose_' . ffel($strFormEntityCode_a);        
				$arrJSONData = call_user_func($strBeforeAfterCallFunc, $objConn_a, $arrRow, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, true);
			}
		//}

		// B:end
    }
    
	// logging
    if (dependencies('utils/log')) 
	{
		createAuditEntityEditDataLog($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strFormDataID_a);
	}
	
    return $strFormDataID_a;
}
