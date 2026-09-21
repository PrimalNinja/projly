<?php

// $strFormDataID_a = ENTITY

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
function beforeDisplayAddUpdate_entity($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeAddUpdate_entity($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	
	$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "ffe65a2521-c3d0-42fc-8d25-dedd43876fff", "CODE");
	$strEntityCode = ffeu($arrJSONField['p_value']);

	$intLiveRowCount =  0;
	$intHistoryRowCount = 0;
	if ($blnUpdate_a)
	{
		$strTableName = getTableNameEntity($strEntityCode, false);
		$strSQL = "select count(*) returnvalue from ~TABLENAMEENTITY~";
		$strSQL = str_replace("~TABLENAMEENTITY~", ff($strTableName), $strSQL);
		$intLiveRowCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
		$strTableName = getTableNameEntity($strEntityCode, true);
		$strSQL = "select count(*) returnvalue from ~TABLENAMEENTITY~";
		$strSQL = str_replace("~TABLENAMEENTITY~", ff($strTableName), $strSQL);
		$intHistoryRowCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	}
	
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffe65a2521-c3d0-42fc-8d25-dedd43876fff", "CODE", $strEntityCode);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffe65a2521-c3d0-42fc-8d25-dedd43876fff", "LIVEROWCOUNT", $intLiveRowCount);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffe65a2521-c3d0-42fc-8d25-dedd43876fff", "HISTORYROWCOUNT", $intHistoryRowCount);
		
	return $arrJSONData;
}

function afterAddUpdate_entity($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	
	$strTableNameEntityOperationDefault = getTableNameEntity("entityoperation_default", false);
	$strTableNamePermissionCategory = getTableNameEntity("permissioncategory", false);
	
	if (dependencies('entity/createEntityTables,entity/entityOperationAdd') &&
		dependencies('security/permissionAdd'))
	{
		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "ffe65a2521-c3d0-42fc-8d25-dedd43876fff", "CODE");
		$strEntityCode = ffeu($arrJSONField['p_value']);

		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "ffe65a2521-c3d0-42fc-8d25-dedd43876fff", "DESCRIPTION");
		$strEntityDescription = $arrJSONField['p_value'];

		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffe65a2521-c3d0-42fc-8d25-dedd43876fff", "CODE", $strEntityCode);

		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "ffe65a2521-c3d0-42fc-8d25-dedd43876fff", "PERMISSIONCATEGORY");
		$strEntityPermissionCategoryID = $arrJSONField['p_value'];
		$strPermissionCategoryCode = "DEFAULT";
		
		if (strlen($strEntityPermissionCategoryID) > 0)
		{
			$strSQL = "select code returnvalue from ~TABLENAMEPERMISSIONCATEGORY~ where id = ~PERMISSIONCATEGORYID~";
			$strSQL = str_replace("~TABLENAMEPERMISSIONCATEGORY~", ff($strTableNamePermissionCategory), $strSQL);
			$strSQL = str_replace("~PERMISSIONCATEGORYID~", ff($strEntityPermissionCategoryID), $strSQL);
			$strPermissionCategoryCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		}

		if ($blnUpdate_a == false)
		{
			// default operations
			$strSQL = "select ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_code, ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_description, ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_command, ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_parameters, ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_flags, ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_prompt, ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_isinternal, ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_requiresselection, ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_allowmultiple, ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_iscustom, ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_displayorder, ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_isenabled from ~TABLENAMEENTITYOPERATIONDEFAULT~ order by id";
			$strSQL = str_replace("~TABLENAMEENTITYOPERATIONDEFAULT~", ff($strTableNameEntityOperationDefault), $strSQL);
			$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
			while ($arrRow = dbReadRecord($objResult)) 
			{
				$strCode = $arrRow['ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_code'];
				$strDescription = $arrRow['ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_description'];
				$strCommand = $arrRow['ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_command'];
				$strParameters = $arrRow['ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_parameters'];
				$strFlags = $arrRow['ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_flags'];
				$strPrompt = $arrRow['ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_prompt'];
				$strIsInternal = $arrRow['ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_isinternal'];
				$strRequiresSelection = $arrRow['ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_requiresselection'];
				$strAllowMultiple = $arrRow['ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_allowmultiple'];
				$strIsCustom = $arrRow['ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_iscustom'];
				$strDisplayOrder = $arrRow['ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_displayorder'];
				$strIsEnabled = $arrRow['ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_isenabled'];
				$strNotes = ""; // no default notes
				
				$strCode = str_replace("%ENTITY%", $strEntityCode, $strCode);
				$strCode = ffeu($strCode);
				
				$strPermissionDesc = $strDescription . " " . $strEntityDescription;
				
				// operationcode and permissioncode are the same by default
				entityOperationAdd($objConn_a, $strClientID_a, $strFormDataID_a, $strCode, $strDescription, $strPermissionDesc, $strCommand, $strParameters, $strFlags, $strPrompt, $strIsInternal, $strRequiresSelection, $strAllowMultiple, $strIsCustom, $strDisplayOrder, $strIsEnabled, $strNotes);
				permissionAdd($objConn_a, $strClientID_a, $strFormDataID_a, $strCode, $strPermissionDesc, "Y", "N", "Y", "Y", "Y", $strPermissionCategoryCode);
			}
			dbCloseRecordset($objResult);
			
			createEntityTables($objConn_a, $strEntityCode);
		}
	}
	
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_entity($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_entity($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	if (dependencies('entity/entityDelete'))
	{
		entityDelete($objConn_a, $strFormDataID_a);
	}
}

function afterDelete_entity($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_entity($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
}
