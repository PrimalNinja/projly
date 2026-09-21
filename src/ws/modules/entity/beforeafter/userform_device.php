<?php

// $strFormDataID_a = USERFORM_DEVICE

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
function beforeDisplayAddUpdate_userform_device($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;
	
	if ($blnUpdate_a == false)
	{
		$strCode = getGUID();
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "gb044f0b5-48c7-48d6-8d6c-b1f8af44a652", "CODE", $strCode);
	}
	
	return $arrJSONData;
}

function beforeAddUpdate_userform_device($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_userform_device($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{	
	$strTableNameFormSectionField = getTableNameEntity("userform_device", false);
	
	$arrJSONData = $arrJSONData_a;
	$strUserID = $_SESSION['server_loggedin_userid'];
	
	if ($blnUpdate_a)
	{
		// do nothing
	}
	else
	{
		$strSQL = "update ~TABLENAMEUSERFORMDEVICE~ set user_id = ~USERID~ where id = ~ID~";
		$strSQL = str_replace('~TABLENAMEUSERFORMDEVICE~', ff($strTableNameFormSectionField), $strSQL);
		$strSQL = str_replace('~ID~', ff($strFormDataID_a), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	}
	
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_userform_device($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;	
	return $arrJSONData;
}

function beforeDelete_userform_device($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	
}

function afterDelete_userform_device($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_userform_device($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
	
}
