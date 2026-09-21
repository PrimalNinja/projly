<?php

// $strFormDataID_a = PERMISSION

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
function beforeDisplayAddUpdate_permission($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeAddUpdate_permission($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;

	$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "0000eae3-e5b8-4ebb-a3a8-50220cee15d5", "CODE");
	$strPermissionCode = ffeu($arrJSONField['p_value']);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "0000eae3-e5b8-4ebb-a3a8-50220cee15d5", "CODE", $strPermissionCode);
		
	return $arrJSONData;
}

function afterAddUpdate_permission($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameEntity = getTableNameEntity("entity", false);
	$strTableNameEntityOperation = getTableNameEntity("entityoperation", false);
	$strTableNamePermission = getTableNameEntity("permission", false);

	$arrJSONData = $arrJSONData_a;
	
	if (dependencies('entity/entityOperationUpdateCodeDescription'))
	{
		if ($blnUpdate_a)
		{
			$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "0000eae3-e5b8-4ebb-a3a8-50220cee15d5", "CODE");
			$strPermissionCode = ffeu($arrJSONField['p_value']);

			$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "0000eae3-e5b8-4ebb-a3a8-50220cee15d5", "DESCRIPTION");
			$strPermissionDesc = $arrJSONField['p_value'];
	
			$strSQL = "select o.id returnvalue
		from ~TABLENAMEENTITY~ e, ~TABLENAMEPERMISSION~ p, ~TABLENAMEENTITYOPERATION~ o
		where o.relatedentity_id = e.id and p.code = o.code and p.id = ~PERMISSIONID~";
			$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
			$strSQL = str_replace('~TABLENAMEENTITYOPERATION~', ff($strTableNameEntityOperation), $strSQL);
			$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
			$strSQL = str_replace('~PERMISSIONID~', ff($strFormDataID_a), $strSQL);
			$strOperationID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

			if (strlen($strOperationID) > 0)
			{
				entityOperationUpdateCodeDescription($objConn_a, $strClientID_a, $strOperationID, $strPermissionCode, $strPermissionDesc);
			}
		}
	}
	
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_permission($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_permission($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

function afterDelete_permission($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_permission($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
}
