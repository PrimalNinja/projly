<?php

// $strFormDataID_a = PERMISSIONCATEGORY
// $strRelativeID_a = PERMISSION

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
function beforeDisplayAddUpdate_permissioncategory($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeAddUpdate_permissioncategory($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_permissioncategory($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNamePermission = getTableNameEntity("permission", false);

	$arrJSONData = $arrJSONData_a;
	
	if ($blnUpdate_a)
	{
		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "f8b3c85a6-ea90-4610-be1c-f8945f809def", "DESCRIPTION");
		$strCategoryDesc = $arrJSONField['p_value'];

		$strSQL = "select id from ~TABLENAMEPERMISSION~ where permissioncategory_id = ~PERMISSIONCATEGORYID~";
		$strSQL = str_replace("~TABLENAMEPERMISSION~", ff($strTableNamePermission), $strSQL);
		$strSQL = str_replace("~PERMISSIONCATEGORYID~", ff($strFormDataID_a), $strSQL);
		$objResultLoop = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		while ($arrRowLoop = dbReadRecord($objResultLoop)) {
			$strPermissionID = $arrRowLoop['id']; 

			$strSQL = "select jsondata returnvalue from ~TABLENAMEPERMISSION~ where id = ~PERMISSIONID~";
			$strSQL = str_replace("~TABLENAMEPERMISSION~", ff($strTableNamePermission), $strSQL);
			$strSQL = str_replace("~PERMISSIONID~", ff($strPermissionID), $strSQL);
			$strJSONDataPermission = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			
			$arrJSONDataPermission = json_decode($strJSONDataPermission, true);

			$arrJSONDataPermission = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONDataPermission, "0000eae3-e5b8-4ebb-a3a8-50220cee15d5", "PERMISSIONCATEGORY", $strFormDataID_a, $strCategoryDesc);

			$strJSONDataPermission = json_encode($arrJSONDataPermission);

			dbBeginTrans($objConn_a, __FUNCTION__);

			$strSQL = "update ~TABLENAMEPERMISSION~ set jsondata = '~JSONDATA~' where id = ~PERMISSIONID~";
			$strSQL = str_replace("~TABLENAMEPERMISSION~", ff($strTableNamePermission), $strSQL);
			$strSQL = str_replace('~JSONDATA~', ff($strJSONDataPermission), $strSQL);
			$strSQL = str_replace('~PERMISSIONID~', ff($strPermissionID), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			
			exposeEntityData($objConn_a, 'SYSTEMFORM', 'PERMISSION', $strPermissionID, $strJSONDataPermission);
			
			dbEndTrans($objConn_a, __FUNCTION__);
		}
		dbCloseRecordset($objResultLoop);
	}
	
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_permissioncategory($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_permissioncategory($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

function afterDelete_permissioncategory($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_permissioncategory($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
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
