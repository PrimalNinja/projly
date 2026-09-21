<?php

// $strFormDataID_a = REGISTRATION

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
function beforeDisplayAddUpdate_registration($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;

	$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "CLIENTCODE");
	$strCode = $arrJSONField['p_value'];

	$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ACCOUNTEMAILADDRESS");
	$strDescription = $arrJSONField['p_value'];

	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "fh5c1a5480-bd08-44a1-8f8d-1352d27402b5", "CODE", $strCode);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "fh5c1a5480-bd08-44a1-8f8d-1352d27402b5", "DESCRIPTION", $strDescription);

	return $arrJSONData;
}

function beforeAddUpdate_registration($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_registration($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameRegistration = getTableNameEntity("registration", false);

	// bring out fields
	//$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303", "ISCONFIRMED");

	// fixed above code it should be dash and not underscore, error was being returen when i perform
	// test upon update must perform 2 tests
	// 1 new registration just to make sure
	// 2 on secury > registration confirm / edit
	$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ISCONFIRMED");
	$strIsConfirmed = $arrJSONField['p_value'];

	$strSQL = "update ~TABLENAMEREGISTRATION~ set is_confirmed = '~ISCONFIRMED~' where id = ~ID~";
	$strSQL = str_replace('~TABLENAMEREGISTRATION~', ff($strTableNameRegistration), $strSQL);
	$strSQL = str_replace('~ID~', ff($strFormDataID_a), $strSQL);
	$strSQL = str_replace('~ISCONFIRMED~', ff($strIsConfirmed), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_registration($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_registration($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{
}

function afterDelete_registration($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_registration($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
}
