<?php

// $strFormDataID_a = SUBURB

// code in before display add events:
//		default fields within the JSON for display purposes before a user starts filling in a form
//
// code in after events: 
//		exposing fields
//		populating manually created fields
//
// code in before events: 
// 		modifying the json that is to be stored (it is stored automatically)
//		validation such as uniqueness (don't forget to put unique indexes on field combinations you need to be unique)
//
// event order:  
//		before events, saving of json and common fields, after events, transfer of exposed field's values
//
function beforeDisplayAddUpdate_suburb($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeAddUpdate_suburb($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_suburb($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameState = getTableNameEntity("state", false);
	$strTableNameSuburb = getTableNameEntity("suburb", false);

	$arrJSONData = $arrJSONData_a;
	
    $strLogin = $_SESSION['server_loggedin_user'];
	
	// bring out fields
	$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "f33f67299-5383-4cd3-8455-89e470fb519a", "SUBURB");
	$strSuburb = $arrJSONField['p_value'];
	
	$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "f33f67299-5383-4cd3-8455-89e470fb519a", "STATE");
	$strState = $arrJSONField['p_value'];
	
	$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "f33f67299-5383-4cd3-8455-89e470fb519a", "POSTCODE");
	$strPostcode = $arrJSONField['p_value'];
	
	$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "f33f67299-5383-4cd3-8455-89e470fb519a", "COUNTRY");
	$strCountry = $arrJSONField['p_value'];
	
	$strJSONData = json_encode($arrJSONData);
			
	// get the table		
	$strSQL = "update ~TABLENAMESUBURB~ set jsondata = '~JSONDATA~', code = '~CODE~', description = '~DESCRIPTION~', suburb = '~SUBURB~', state = '~STATE~', postcode = '~POSTCODE~', country = '~COUNTRY~' where id = ~ID~";
	$strSQL = str_replace('~TABLENAMESUBURB~', ff($strTableNameSuburb), $strSQL);
	$strSQL = str_replace('~ID~', ff($strFormDataID_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strPostcode), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strSuburb), $strSQL);
	$strSQL = str_replace('~SUBURB~', ff($strSuburb), $strSQL);
	$strSQL = str_replace('~STATE~', ff($strState), $strSQL);
	$strSQL = str_replace('~POSTCODE~', ff($strPostcode), $strSQL);
	$strSQL = str_replace('~COUNTRY~', ff($strCountry), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	$strResult = dbLastInsertID($objConn_a);  
	
	// update the states

	// disable all states
	$strSQL = "update ~TABLENAMESTATE~ set is_enabled = 'N' where client_id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMESTATE~', ff($strTableNameState), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	// enable all states that exist
	$strSQL = "update ~TABLENAMESTATE~ set is_enabled = 'Y' where client_id = ~CLIENTID~ and code in (select distinct state from ~TABLENAMESUBURB~ where client_id = ~CLIENTID~)";
	$strSQL = str_replace('~TABLENAMESTATE~', ff($strTableNameState), $strSQL);
	$strSQL = str_replace('~TABLENAMESUBURB~', ff($strTableNameSuburb), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	// insert all unique states from within the suburb entity that do not already exist
	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, "state");
	
	$strSQL = "insert into ~TABLENAMESTATE~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime) ";
	$strSQL .= "select distinct ~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, state, state, 'Y', ~CLIENTID~, null, '~MODIFYUSER~', '~MODIFYDATETIME~' from ~TABLENAMESUBURB~ ";
	$strSQL .= "where client_id = ~CLIENTID~ and state not in (select code from ~TABLENAMESTATE~)";
	$strSQL = str_replace('~TABLENAMESTATE~', ff($strTableNameState), $strSQL);
	$strSQL = str_replace('~TABLENAMESUBURB~', ff($strTableNameSuburb), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
	$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);		
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	// delete unused states
	$strSQL = "delete from ~TABLENAMESTATE~ where client_id = ~CLIENTID~ and is_enabled = 'N'";
	$strSQL = str_replace('~TABLENAMESTATE~', ff($strTableNameState), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_suburb($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_suburb($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

function afterDelete_suburb($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	$strTableNameState = getTableNameEntity("state", false);
	$strTableNameSuburb = getTableNameEntity("suburb", false);
	
    $strLogin = $_SESSION['server_loggedin_user'];
	
	// update the states

	// disable all states
	$strSQL = "update ~TABLENAMESTATE~ set is_enabled = 'N' where client_id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMESTATE~', ff($strTableNameState), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	// enable all states that exist
	$strSQL = "update ~TABLENAMESTATE~ set is_enabled = 'Y' where client_id = ~CLIENTID~ and code in (select distinct state from ~TABLENAMESUBURB~ where client_id = ~CLIENTID~)";
	$strSQL = str_replace('~TABLENAMESTATE~', ff($strTableNameState), $strSQL);
	$strSQL = str_replace('~TABLENAMESUBURB~', ff($strTableNameSuburb), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	// insert all unique states from within the suburb entity that do not already exist
	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, "state");
	
	$strSQL = "insert into ~TABLENAMESTATE~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime) ";
	$strSQL .= "select distinct ~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, state, state, 'Y', ~CLIENTID~, null, '~MODIFYUSER~', '~MODIFYDATETIME~' from ~TABLENAMESUBURB~ ";
	$strSQL .= "where client_id = ~CLIENTID~ and state not in (select code from ~TABLENAMESTATE~)";
	$strSQL = str_replace('~TABLENAMESTATE~', ff($strTableNameState), $strSQL);
	$strSQL = str_replace('~TABLENAMESUBURB~', ff($strTableNameSuburb), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
	$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);		
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	// delete unused states
	$strSQL = "delete from ~TABLENAMESTATE~ where client_id = ~CLIENTID~ and is_enabled = 'N'";
	$strSQL = str_replace('~TABLENAMESTATE~', ff($strTableNameState), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_suburb($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
	$strTableNameSuburb = getTableNameEntity("suburb", false);
	$strTableNameUser = getTableNameEntity("user", false);

	$strSQL = "select id from ~TABLENAMESUBURB~ where jsondata is null and id = ~SUBURBID~";
	$strSQL = str_replace("~TABLENAMESUBURB~", ff($strTableNameSuburb), $strSQL);
	$strSQL = str_replace("~SUBURBID~", ff($strFormDataID_a), $strSQL);
	$objResultLoop = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	while ($arrRowLoop = dbReadRecord($objResultLoop)) {
		$strSuburbID = $arrRowLoop['id'];
	
		//$strCode = "";
		//$strDescription = "";
		//$strIsEnabled = "";
		$strSuburb = "";
		$strState = "";
		$strPostcode = "";
		$strCountry = "";

		$strSQL = "select suburb, state, postcode, country from ~TABLENAMESUBURB~ where id = ~SUBURBID~";			
		$strSQL = str_replace("~TABLENAMESUBURB~", ff($strTableNameSuburb), $strSQL);
		$strSQL = str_replace("~SUBURBID~", ff($strSuburbID), $strSQL);
		$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		if ($arrRow = dbReadRecord($objResult)) {
			//$strCode = $arrRow['code'];
			//$strDescription = $arrRow['description'];
			//$strIsEnabled = $arrRow['is_enabled'];
			
			$strSuburb = $arrRow['suburb'];
			$strState = $arrRow['state'];
			$strPostcode = $arrRow['postcode'];
			$strCountry = $arrRow['country'];
		}
		dbCloseRecordset($objResult);
		
		$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "SUBURB");
		
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f33f67299-5383-4cd3-8455-89e470fb519a", "SUBURB", $strSuburb);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f33f67299-5383-4cd3-8455-89e470fb519a", "STATE", $strState);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f33f67299-5383-4cd3-8455-89e470fb519a", "POSTCODE", $strPostcode);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f33f67299-5383-4cd3-8455-89e470fb519a", "COUNTRY", $strCountry);

		$strJSONData = json_encode($arrJSONData);

		dbBeginTrans($objConn_a, __FUNCTION__);

		$strSQL = "update ~TABLENAMESUBURB~ set jsondata = '~JSONDATA~' where id = ~SUBURBID~";
		$strSQL = str_replace("~TABLENAMESUBURB~", ff($strTableNameSuburb), $strSQL);
		$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		$strSQL = str_replace('~SUBURBID~', ff($strSuburbID), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
		//exposeEntityData($objConn_a, 'SYSTEMFORM', 'SUBURB', $strSuburbID, $strJSONData);
		
		dbEndTrans($objConn_a, __FUNCTION__);
	}
	dbCloseRecordset($objResultLoop);
}
