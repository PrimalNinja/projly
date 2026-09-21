<?php

// $strFormDataID_a = REMINDER

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
function beforeDisplayAddUpdate_reminder($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeAddUpdate_reminder($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_reminder($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameReminder = getTableNameEntity("reminder", false);

	$arrJSONData = $arrJSONData_a;
	
	$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData_a, "adee66ca-8443-4e3e-8ddf-b5a480bf2b49", "USER");
	$strUserID = $arrJSONField['p_value'];
	
	$strSQL = "update ~TABLENAMEREMINDER~ set user_id = ~USERID~ where id = ~ID~";
	$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
    $strSQL = str_replace('~TABLENAMEREMINDER~', ff($strTableNameReminder), $strSQL);
    $strSQL = str_replace('~ID~', ff($strFormDataID_a), $strSQL);
    $strSQL = str_replace('~RELATIVEID~', ff($strRelativeID_a), $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
            
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_reminder($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_reminder($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

function afterDelete_reminder($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_reminder($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
}
