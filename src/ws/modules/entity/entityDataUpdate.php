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
function entityDataUpdate($objConn_a, $strClientID_a, $strEntityCode_a, $strFormEntityCode_a, $strEntityDataID_a, $arrJSONData_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{ 
	$strTableNameEntity = getTableNameEntity('entity', false);
	$strTableNameEntityCode = getTableNameEntity($strEntityCode_a, false);
	$strTableNameEntityCodeHistory = getTableNameEntity($strEntityCode_a, true);
	
	$blnIsExtended = isExtended($objConn_a, $strFormEntityCode_a);
	
	$arrJSONData = $arrJSONData_a;
	$blnResult = false;
    $strLogin = $_SESSION['server_loggedin_user'];

	$strJSONData = json_encode($arrJSONData);

    if (dependencies('entity/entityHistoryAdd'))
    { 
		// get the values from the form header section always for entities
		// A: note, for entity, we read from the FORMHEADER section
		$strCode = formValueGetBySectionTypeFieldCode($arrJSONData, 'FORMHEADER', 'code');
		$strDescription = formValueGetBySectionTypeFieldCode($arrJSONData, 'FORMHEADER', 'description');
		$strIsEnabled = formValueGetBySectionTypeFieldCode($arrJSONData, 'FORMHEADER', 'isenabled');
		// A:end

		// B:
		// B:end
		
		// fetch the record first. to be use in history table  
		// let's pull the current saved record first before updating it.
		// C:
		$strSQL = "select id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime from ~TABLENAMEENTITYCODE~ where id = ~ENTITYDATAID~";
		$strSQL = str_replace('~TABLENAMEENTITYCODE~', ff($strTableNameEntityCode), $strSQL);
		$strSQL = str_replace('~ENTITYDATAID~', ff($strEntityDataID_a), $strSQL);
		
		$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		$arrRow = dbReadRecord($objResult);
		// C:end
        
        //check CRC's before updating the record
		//if (crc32($arrRow['jsondata']) != crc32($strJSONData))
        //{
			// temporarily fetch based on entitycode if entityid is not known
			$strSQL = "select id returnvalue from ~TABLENAMEENTITY~ where code = '~ENTITYCODE~'";
			$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
			$strSQL = str_replace('~ENTITYCODE~', ffel($strFormEntityCode_a), $strSQL);
			$strDataEntityID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);    
			
			if (strlen($strDataEntityID) > 0)
			{
				dbBeginTrans($objConn_a, __FUNCTION__);

				// BEFORE UPDATE
				if (dependencies('entity/beforeafter/' . ffel($strEntityCode_a), true)) 
				{ 
					$strBeforeAfterCallFunc = 'beforeAddUpdate_' . ffel($strEntityCode_a);        
					$arrJSONData = call_user_func($strBeforeAfterCallFunc, $objConn_a, $arrRow, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData, "", $strRelativeID_a, $strRelative_a, $strRelationship_a, true);
				}
	
				//get formversion 
				//$intFormVersion = intval(formValueGetBySectionTypeFieldCode($arrJSONData, 'FORMHEADER', 'FORMVERSION'));
				//set new formversion
				//$intNewFormVersion = $intFormVersion + 1;
				//$arrJSONData = formValueUpdateBySectionTypeFieldCode($arrJSONData, 'FORMHEADER', 'FORMVERSION', $intNewFormVersion);
				$strJSONData = json_encode($arrJSONData);
//logDebug('jc3:' . print_r($arrJSONData, true), '');
//die();					
				// D:
				$strSQL = "update ~TABLENAMEENTITYCODE~ set dataentity_id = ~DATAENTITYID~, code = '~CODE~', description = '~DESCRIPTION~', is_enabled = '~IS_ENABLED~', jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where client_id = ~CLIENTID~ and id = ~ENTITYDATAID~";
				$strSQL = str_replace('~TABLENAMEENTITYCODE~', ff($strTableNameEntityCode), $strSQL);
				$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
				$strSQL = str_replace('~ENTITYDATAID~', ff($strEntityDataID_a), $strSQL);
				$strSQL = str_replace('~DATAENTITYID~', ffn($strDataEntityID), $strSQL);
				$strSQL = str_replace('~CODE~', ff($strCode), $strSQL);
				$strSQL = str_replace('~DESCRIPTION~', ff($strDescription), $strSQL);
				$strSQL = str_replace('~IS_ENABLED~', ff($strIsEnabled), $strSQL);
				$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
				$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
				$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
				
				dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				// D:end
				
				if (strlen($strFormEntityCode_a) > 0)
				{
					exposeEntityFields($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strDataEntityID, $strClientID_a, $strEntityDataID_a, $strJSONData);
				}
				
				if ($arrRow) 
				{ 
					// E:
					entityHistoryAdd($objConn_a, $strTableNameEntityCodeHistory, $strClientID_a, $arrRow['id'], $arrRow['entity_id'], $arrRow['dataentity_id'], $arrRow['code'], $arrRow['description'], $arrRow['is_enabled'], $arrRow['data_client_id'], $arrRow['jsondata'], $arrRow['modifyuser'], $arrRow['modifydatetime']);
					// E:end
				}
				
				dbCloseRecordset($objResult);

				// AFTER UPDATE
				if (dependencies('entity/beforeafter/' . ffel($strEntityCode_a), true)) 
				{					
					$strBeforeAfterCallFunc = 'afterAddUpdate_' . ffel($strEntityCode_a);        
					$arrJSONData = call_user_func($strBeforeAfterCallFunc, $objConn_a, $arrRow, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData, $strEntityDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, true);
				}
	
				if (strlen($strFormEntityCode_a) > 0)
				{
					//transferEntityData($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strEntityDataID_a, $strJSONData);	// updating a form template we don't have anything to transfer
				}
				
				$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
				
				if ($blnResult && (strlen($strFormEntityCode_a) > 0))
				{
					if ($blnIsExtended)
					{
						exposeEntityDataDeferred($objConn_a, $strClientID_a, $strEntityCode_a, $strFormEntityCode_a, $strJSONData);// we want to update all rows
					}
					else
					{
						exposeEntityDataAll($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strJSONData);// we want to update all rows
					}

					// AFTER UPDATE
					if (dependencies('entity/beforeafter/' . ffel($strEntityCode_a), true)) 
					{ 
						$strBeforeAfterCallFunc = 'afterAddUpdateExpose_' . ffel($strEntityCode_a);        
						$arrJSONData = call_user_func($strBeforeAfterCallFunc, $objConn_a, $arrRow, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData, "", $strRelativeID_a, $strRelative_a, $strRelationship_a, true);
					}
				}
			}
			else
			{
				$blnResult = false;
			}
			//}
        //else 
        //{
            //$blnResult = true;
        //}
	}
    
    return $blnResult;
}
