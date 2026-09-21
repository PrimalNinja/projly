<?php

// $strFormDataID_a = PROFILE

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
function beforeDisplayAddUpdate_profile($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeAddUpdate_profile($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_profile($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameProfile = getTableNameEntity("profile", false);

	$arrJSONData = $arrJSONData_a;
	
	if ($blnUpdate_a == false)
	{
		// default is_defined and is_sysadmin both to 'N'
		$strSQL = "update ~TABLENAMEPROFILE~ set is_defined = 'N', is_sysadmin = 'N' where id = ~PROFILEID~";
		$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
		$strSQL = str_replace("~PROFILEID~", ff($strFormDataID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	}
	
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_profile($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_profile($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	$strTableNameProfilePermission = getTableNameEntity("profile_permission", false);

	$strSQL = "delete from ~TABLENAMEPROFILEPERMISSION~ where profile_id = ~PROFILEID~";
	$strSQL = str_replace('~TABLENAMEPROFILEPERMISSION~', ff($strTableNameProfilePermission), $strSQL);
	$strSQL = str_replace("~PROFILEID~", ff($strFormDataID_a), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
}

function afterDelete_profile($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_profile($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
	// $strTableNameProfile = getTableNameEntity("profile", false);

	// $strSQL = "select id from ~TABLENAMEPROFILE~ where jsondata is null";
	// $strSQL = str_replace("~TABLENAMEPROFILE~", ff($strTableNameProfile), $strSQL);
	// $objResultLoop = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	// while ($arrRowLoop = dbReadRecord($objResultLoop)) {
		// $strProfileID = $arrRowLoop['id']; 

		// $strDescription = "";
		// $strIsEnabled = "";
		// $strIsAdmin = "";
		// $strIsDefault = "";

		// $strSQL = "select code, 9a0f134f_54b8_4e11_81b3_02d4f51dadd0_description description, 9a0f134f_54b8_4e11_81b3_02d4f51dadd0_isenabled is_enabled, 9a0f134f_54b8_4e11_81b3_02d4f51dadd0_isadmin is_admin, 9a0f134f_54b8_4e11_81b3_02d4f51dadd0_isdefault is_default from ~TABLENAMEPROFILE~ where id = ~PROFILEID~";
		// $strSQL = str_replace("~TABLENAMEPROFILE~", ff($strTableNameProfile), $strSQL);
		// $strSQL = str_replace("~PROFILEID~", ff($strProfileID), $strSQL);
		// $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		// if ($arrRow = dbReadRecord($objResult)) {
			// $strDescription = $arrRow['description'];
			// $strIsEnabled = $arrRow['is_enabled'];
			// $strIsAdmin = $arrRow['is_admin'];
			// $strIsDefault = $arrRow['is_default'];
		// }
		// dbCloseRecordset($objResult);

		// $arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "PROFILE");
		
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f9a0f134f-54b8-4e11-81b3-02d4f51dadd0", "DESCRIPTION", $strDescription);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f9a0f134f-54b8-4e11-81b3-02d4f51dadd0", "ISENABLED", $strIsEnabled);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f9a0f134f-54b8-4e11-81b3-02d4f51dadd0", "ISADMIN", $strIsAdmin);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f9a0f134f-54b8-4e11-81b3-02d4f51dadd0", "ISDEFAULT", $strIsDefault);

		// $strJSONData = json_encode($arrJSONData);

		// dbBeginTrans($objConn_a, __FUNCTION__);

		// $strSQL = "update ~TABLENAMEPROFILE~ set jsondata = '~JSONDATA~' where id = ~PROFILEID~";
		// $strSQL = str_replace("~TABLENAMEPROFILE~", ff($strTableNameProfile), $strSQL);
		// $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		// $strSQL = str_replace('~PROFILEID~', ff($strProfileID), $strSQL);
		// dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
		// exposeEntityData($objConn_a, 'SYSTEMFORM', 'PROFILE', $strProfileID, $strJSONData);
		
		// dbEndTrans($objConn_a, __FUNCTION__);
	// }
	// dbCloseRecordset($objResultLoop);
}
