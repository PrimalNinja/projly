<?php

// $strFormDataID_a = ENTITYOPERATION
// $strRelativeID_a = ENTITY

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
function beforeDisplayAddUpdate_entityoperation($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeAddUpdate_entityoperation($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;

	$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "CODE");
	$strOperationCode = ffeu($arrJSONField['p_value']);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "CODE", $strOperationCode);
		
	return $arrJSONData;
}

function afterAddUpdate_entityoperation($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameEntity = getTableNameEntity("entity", false);
	$strTableNameEntityOperation = getTableNameEntity("entityoperation", false);
	$strTableNamePermission = getTableNameEntity("permission", false);

	$arrJSONData = $arrJSONData_a;

	if (dependencies('security/permissionAdd,security/permissionUpdateCodeDescription'))
	{
		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "CODE");
		$strOperationCode = ffeu($arrJSONField['p_value']);

		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "PERMISSIONDESC");
		$strPermissionDesc = $arrJSONField['p_value'];

		if ($blnUpdate_a)
		{
			$strSQL = "select p.id returnvalue
		from ~TABLENAMEENTITY~ e, ~TABLENAMEPERMISSION~ p, ~TABLENAMEENTITYOPERATION~ o
		where o.relatedentity_id = e.id and p.code = o.code and o.id = ~OPERATIONID~";
			$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
			$strSQL = str_replace('~TABLENAMEENTITYOPERATION~', ff($strTableNameEntityOperation), $strSQL);
			$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
			$strSQL = str_replace('~OPERATIONID~', ff($strFormDataID_a), $strSQL);
			$strPermissionID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

			if (strlen($strPermissionID) > 0)
			{
				permissionUpdateCodeDescription($objConn_a, $strClientID_a, $strPermissionID, $strOperationCode, $strPermissionDesc);
			}
		}
		else
		{
			$strSQL = "update ~TABLENAMEENTITYOPERATION~ set relatedentity_id = ~RELATIVEID~ where id = ~ID~";
			$strSQL = str_replace('~TABLENAMEENTITYOPERATION~', ff($strTableNameEntityOperation), $strSQL);
			$strSQL = str_replace('~ID~', ff($strFormDataID_a), $strSQL);
			$strSQL = str_replace('~RELATIVEID~', ff($strRelativeID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			
			// operationcode and permissioncode are the same by default
			permissionAdd($objConn_a, $strClientID_a, $strRelativeID_a, $strOperationCode, $strPermissionDesc, "Y", "N", "Y", "Y", "Y", "DEFAULT");
		}
	}
				
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_entityoperation($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_entityoperation($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	$strTableNameEntity = getTableNameEntity("entity", false);
	$strTableNameEntityOperation = getTableNameEntity("entityoperation", false);
	$strTableNamePermission = getTableNameEntity("permission", false);
	$strTableNameProfilePermission = getTableNameEntity("profile_permission", false);
	
	$strSQL = "select p.id returnvalue
from ~TABLENAMEENTITY~ e, ~TABLENAMEPERMISSION~ p, ~TABLENAMEENTITYOPERATION~ o
where o.relatedentity_id = e.id and p.code = o.code and o.id = ~OPERATIONID~";
	$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
	$strSQL = str_replace('~TABLENAMEENTITYOPERATION~', ff($strTableNameEntityOperation), $strSQL);
	$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
	$strSQL = str_replace('~OPERATIONID~', ff($strFormDataID_a), $strSQL);
	$strPermissionID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
	if (strlen($strPermissionID) > 0)
	{
		$strSQL = "delete from ~TABLENAMEPROFILEPERMISSION~ where permission_id = ~PERMISSIONID~";
		$strSQL = str_replace('~TABLENAMEPROFILEPERMISSION~', ff($strTableNameProfilePermission), $strSQL);
		$strSQL = str_replace('~PERMISSIONID~', ff($strPermissionID), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		$strSQL = "delete from ~TABLENAMEPERMISSION~ where id = ~PERMISSIONID~";
		$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
		$strSQL = str_replace('~PERMISSIONID~', ff($strPermissionID), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	}
}

function afterDelete_entityoperation($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_entityoperation($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
	// $strTableNameEntityOperation = getTableNameEntity("entityoperation", false);
	// $strTableNameSystemForm = getTableNameEntity("systemform", false);

	// $strSQL = "select id from ~TABLENAMEENTITYOPERATION~"; // where jsondata is null";
	// $strSQL = str_replace("~TABLENAMEENTITYOPERATION~", ff($strTableNameEntityOperation), $strSQL);
	// $objResultLoop = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	// while ($arrRowLoop = dbReadRecord($objResultLoop)) {
		// $strEntityOperationID = $arrRowLoop['id'];

		// $strSQL = "select jsondata returnvalue from ~TABLENAMEENTITYOPERATION~ where id = ~ENTITYOPERATIONID~";
		// $strSQL = str_replace("~TABLENAMEENTITYOPERATION~", ff($strTableNameEntityOperation), $strSQL);
		// $strSQL = str_replace("~ENTITYOPERATIONID~", ff($strEntityOperationID), $strSQL);
		// $strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

		// $arrJSONData = json_decode($strJSONData, true);
		
		// $arrJSONData = formUpdateDataTypeBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "PARAMETERS", "d_multilinetext", "1024", "form-control d_multilinetext-input");

		// $strJSONData = json_encode($arrJSONData);

		// dbBeginTrans($objConn_a, __FUNCTION__);

		// $strSQL = "update ~TABLENAMEENTITYOPERATION~ set jsondata = '~JSONDATA~' where id = ~ENTITYOPERATIONID~";
		// $strSQL = str_replace("~TABLENAMEENTITYOPERATION~", ff($strTableNameEntityOperation), $strSQL);
		// $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		// $strSQL = str_replace('~ENTITYOPERATIONID~', ff($strEntityOperationID), $strSQL);
		// dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
		// dbEndTrans($objConn_a, __FUNCTION__);
	// }
	// dbCloseRecordset($objResultLoop);
		
		
	// $strSQL = "select jsondata returnvalue from ~TABLENAMESYSTEMFORM~ where code = 'ENTITYOPERATION'";
	// $strSQL = str_replace("~TABLENAMESYSTEMFORM~", ff($strTableNameSystemForm), $strSQL);
	// $strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	// $arrJSONData = json_decode($strJSONData, true);
	
	// $arrJSONData = formUpdateDataTypeBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "PARAMETERS", "d_multilinetext", "1024", "form-control d_multilinetext-input");

	// $strJSONData = json_encode($arrJSONData);

	// dbBeginTrans($objConn_a, __FUNCTION__);

	// $strSQL = "update ~TABLENAMESYSTEMFORM~ set jsondata = '~JSONDATA~' where code = 'ENTITYOPERATION'";
	// $strSQL = str_replace("~TABLENAMESYSTEMFORM~", ff($strTableNameSystemForm), $strSQL);
	// $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	// dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	
	// dbEndTrans($objConn_a, __FUNCTION__);
}
