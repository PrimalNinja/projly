<?php

// $strFormDataID_a = PRINTQUEUE

// code in before display add events:
//      default fields within the JSON for display purposes before a user starts filling in a form
//
// code in after events:
//      exposing fields
//      populating manually created fields
//
// code in before events:
//      modifying the json that is to be stored (it is stored automatically)
//      validation such as uniqueness (don't forget to put unique indexes on field combinations you need to be unique)
//
// event order:
//      before events, saving of json and common fields, after events, transfer of exposed field's values
//
function beforeDisplayAddUpdate_printqueue($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
    $arrJSONData = $arrJSONData_a;
    return $arrJSONData;
}

function beforeAddUpdate_printqueue($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    $arrJSONData = $arrJSONData_a;
    return $arrJSONData;
}


function afterAddUpdate_printqueue($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    $strTableNamePrintQueue = getTableNameEntity("printqueue", false);

    $arrJSONData = $arrJSONData_a;
	
	$strUserID = $_SESSION['server_loggedin_userid'];

	$strIsPublic = formValueGetBySectionCodeFieldCode($arrJSONData, "gff9b32c5-96c5-4850-8834-3ed24e70d727", "IS_PUBLIC");
	
	$strSQL = "update ~TABLENAMEPRINTQUEUE~ set user_id = ~USERID~, is_public = '~ISPUBLIC~' where id = ~PRINTQUEUEID~";
	$strSQL = str_replace('~TABLENAMEPRINTQUEUE~', ff($strTableNamePrintQueue), $strSQL);
	$strSQL = str_replace('~PRINTQUEUEID~', ff($strFormDataID_a), $strSQL);
	$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
	$strSQL = str_replace('~ISPUBLIC~', ff($strIsPublic), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);       

    return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_printqueue($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    $arrJSONData = $arrJSONData_a;
    return $arrJSONData;
}

function beforeDelete_printqueue($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{
}

function afterDelete_printqueue($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_printqueue($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
}
