<?php

// ********* WARNING *********
//
// SIMILAR FUNCTIONS: entityDataAdd, formDataAdd - DO NOT CHANGE THIS WITHOUT REVIEWING ALL SIMILAR FUNCTIONS
// MATCH THE LETTERS IN THE SIMILAR FUNCTIONS: EVEN LINE NUMBERING BETWEEN SIMILAR FUNCTIONS IS THE SAME - KEEP THEM THE SAME
// notes: a client can only add their own systemform data
// 
// PSEUDOCODE:
//		A: read header details
//		B: work out which table to insert to
//		C: insert into the table
//		D: not applicable
//		E: not applicable
//
function formDataAdd($objConn_a, $strClientID_a, $strEntityID_a, $strDataEntityID_a, $strEntityCode_a, $strFormEntityCode_a, $strDataClientID_a, $arrJSONData_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{ 
	$strTableNameEntity = getTableNameEntity($strFormEntityCode_a, false);
	$strTableNameEntityExtension = getTableNameEntityExtension($strFormEntityCode_a);

	$blnIsExtended = isExtended($objConn_a, $strFormEntityCode_a);

	$arrJSONData = $arrJSONData_a;
	$strFormEntityCodePlugin = $strFormEntityCode_a;
	$strResult = '';
	
	$strLogin = $_SESSION['server_loggedin_user'];

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
	// A:end

	dbBeginTrans($objConn_a, __FUNCTION__);

	// BEFORE ADD
	if (dependencies('entity/beforeafter/' . ffel($strFormEntityCode_a), true)) 
	{ 
		$strBeforeAfterCallFunc = 'beforeAddUpdate_' . ffel($strFormEntityCode_a);        
		$arrJSONData = call_user_func($strBeforeAfterCallFunc, $objConn_a, null, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData, '', $strRelativeID_a, $strRelative_a, $strRelationship_a, false);
	}
		
    // massage data (such as dates and times) that might not be in the correct format
    $arrJSONData = formMassageData($arrJSONData);
        
	$strJSONData = json_encode($arrJSONData);
	
	// B:
	if (strlen($strTableNameEntity) > 0)
	{
		$strSQL = "insert into ~TABLENAMEENTITY~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime) values(~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~','~DESCRIPTION~','~IS_ENABLED~', ~DATA_CLIENT_ID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~')";
		$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID_a), $strSQL);
		$strSQL = str_replace('~DATAENTITYID~', ffn($strDataEntityID_a), $strSQL);
		$strSQL = str_replace('~CODE~', ff($strCode), $strSQL);
		$strSQL = str_replace('~DESCRIPTION~', ff($strDescription), $strSQL);
		$strSQL = str_replace('~IS_ENABLED~', ff($strIsEnabled), $strSQL);
		$strSQL = str_replace('~DATA_CLIENT_ID~', ff($strDataClientID_a), $strSQL);
		$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
		$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		$strResult = dbLastInsertID($objConn_a);    
		
		if ($blnIsExtended)
		{
			$strSQL = "insert into ~TABLENAMEENTITYEXTENSION~ (id, client_id, is_exposed) values(~ID~, ~CLIENTID~, 'Y')";
			$strSQL = str_replace('~TABLENAMEENTITYEXTENSION~', ff($strTableNameEntityExtension), $strSQL);
			$strSQL = str_replace('~ID~', ff($strResult), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		}

		if (strlen($strFormEntityCode_a) > 0)
		{
			//exposeEntityFields($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strDataEntityID, $strClientID_a, $strResult, $strJSONData);		// creating a form we don't have schema changes
		}
		
		// AFTER ADD
		if (dependencies('entity/beforeafter/' . ffel($strFormEntityCode_a), true)) 
		{ 
			$strBeforeAfterCallFunc = 'afterAddUpdate_' . ffel($strFormEntityCode_a);        
			$arrJSONData = call_user_func($strBeforeAfterCallFunc, $objConn_a, null, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData, $strResult, $strRelativeID_a, $strRelative_a, $strRelationship_a, false);
			$strJSONData = json_encode($arrJSONData);
		}

		if (strlen($strFormEntityCode_a) > 0)
		{
			transferEntityData($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strResult, $strJSONData);
		}
			
		$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
		
		// logging
		if (dependencies('utils/log')) 
		{
			createAuditEntityAddDataLog($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strResult);
		}

		if (!$blnResult)
		{
			$strResult = '';
		}
		
		//if ($blnResult && (strlen($strFormEntityCode_a) > 0))
		//{
			//exposeEntityData($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strResult, $strJSONData);	// creating a form we don't have schema changes
		//}
		
		// AFTER EXPOSE
		if (dependencies('entity/beforeafter/' . ffel($strFormEntityCode_a), true)) 
		{ 
			$strBeforeAfterCallFunc = 'afterAddUpdateExpose_' . ffel($strFormEntityCode_a);        
			$arrJSONData = call_user_func($strBeforeAfterCallFunc, $objConn_a, null, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData, $strResult, $strRelativeID_a, $strRelative_a, $strRelationship_a, false);
		}
	}
	// B:end
	// D: not applicable
	// E: not applicable
	
    return $strResult;
}