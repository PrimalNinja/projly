<?php

// ********* WARNING *********
//
// SIMILAR FUNCTIONS: entityDataAdd, formDataAdd - DO NOT CHANGE THIS WITHOUT REVIEWING ALL SIMILAR FUNCTIONS
// MATCH THE LETTERS IN THE SIMILAR FUNCTIONS: EVEN LINE NUMBERING BETWEEN SIMILAR FUNCTIONS IS THE SAME - KEEP THEM THE SAME
// notes: a client can only add their own entity data
// 
// PSEUDOCODE:
//		A: read header details
//		B: work out which table to insert to
//		C: insert into the table
//		D: not applicable
//		E: not applicable
//
function entityDataAdd($objConn_a, $strClientID_a, $strEntityID_a, $strEntityCode_a, $strFormEntityCode_a, $arrJSONData_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{ 
	$strTableNameEntity = getTableNameEntity('entity', false);
	$strTableNameEntityCode = getTableNameEntity($strEntityCode_a, false);
			
	$blnIsExtended = isExtended($objConn_a, $strFormEntityCode_a);
	
	$arrJSONData = $arrJSONData_a;
	$strResult = "";
    $strLogin = $_SESSION['server_loggedin_user'];
   
	// get the values from the form header section always for entities
	// A: note, for entity, we read from the FORMHEADER section
	$strCode = formValueGetBySectionTypeFieldCode($arrJSONData, 'FORMHEADER', 'code');		// form code
	$strDescription = formValueGetBySectionTypeFieldCode($arrJSONData, 'FORMHEADER', 'description');	// form description
	$strIsEnabled = formValueGetBySectionTypeFieldCode($arrJSONData, 'FORMHEADER', 'isenabled');
	// A:end
	$strJSONData = json_encode($arrJSONData);
	
	
	// temporarily fetch based on entitycode if entityid is not known
	$strSQL = "select id returnvalue from ~TABLENAMEENTITY~ where code = '~ENTITYCODE~'";
	$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
	$strSQL = str_replace('~ENTITYCODE~', ffel($strFormEntityCode_a), $strSQL);
	$strDataEntityID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);    

	if (strlen($strDataEntityID) > 0)
	{
		dbBeginTrans($objConn_a, __FUNCTION__);
		
		// BEFORE ADD
		if (dependencies('entity/beforeafter/' . ffel($strEntityCode_a), true)) { 
			$strBeforeAfterCallFunc = 'beforeAddUpdate_' . ffel($strEntityCode_a);        
			$arrJSONData = call_user_func($strBeforeAfterCallFunc, $objConn_a, null, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData, '', $strRelativeID_a, $strRelative_a, $strRelationship_a, false);
		}
			
		// C:
		$strSQL = "insert into ~TABLENAMEENTITYCODE~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, searchablefields, modifyuser, modifydatetime) values(~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~','~DESCRIPTION~','~IS_ENABLED~', ~DATACLIENTID~, '~JSONDATA~', '', '~MODIFYUSER~', '~MODIFYDATETIME~')";
		$strSQL = str_replace('~TABLENAMEENTITYCODE~', ff($strTableNameEntityCode), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID_a), $strSQL);
		$strSQL = str_replace('~DATAENTITYID~', ffn($strDataEntityID), $strSQL);
		$strSQL = str_replace('~CODE~', ff($strCode), $strSQL);
		$strSQL = str_replace('~DESCRIPTION~', ff($strDescription), $strSQL);
		$strSQL = str_replace('~IS_ENABLED~', ff($strIsEnabled), $strSQL);
		$strSQL = str_replace('~DATACLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
		$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		$strResult = dbLastInsertID($objConn_a);    
		// C:end
		
		// AFTER ADD
		if (dependencies('entity/beforeafter/' . ffel($strEntityCode_a), true)) 
		{ 
			$strBeforeAfterCallFunc = 'afterAddUpdate_' . ffel($strEntityCode_a);        
			$arrJSONData = call_user_func($strBeforeAfterCallFunc, $objConn_a, null, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData, $strResult, $strRelativeID_a, $strRelative_a, $strRelationship_a, false);
			$strJSONData = json_encode($arrJSONData);
		}
			
		if (strlen($strFormEntityCode_a) > 0)
		{
			exposeEntityFields($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strDataEntityID, $strClientID_a, $strResult, $strJSONData);
			//transferEntityData($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strResult, $strJSONData);	// creating a form template we don't have anything to transfer
		}
		
		$blnResult = dbEndTrans($objConn_a, __FUNCTION__);

		if ($blnResult && (strlen($strFormEntityCode_a) > 0))
		{
			if ($blnIsExtended)
			{
				exposeEntityDataDeferred($objConn_a, $strClientID_a, $strEntityCode_a, $strFormEntityCode_a, $strJSONData);	// we want to update all rows
			}
			else
			{
				exposeEntityDataAll($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strJSONData);	// we want to update all rows
			}
		
			// AFTER EXPOSE
			if (dependencies('entity/beforeafter/' . ffel($strEntityCode_a), true)) 
			{ 
				$strBeforeAfterCallFunc = 'afterAddUpdateExpose_' . ffel($strEntityCode_a);        
				$arrJSONData = call_user_func($strBeforeAfterCallFunc, $objConn_a, null, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData, $strResult, $strRelativeID_a, $strRelative_a, $strRelationship_a, false);
			}
		}
	}
    
    return $strResult;
}