<?php

// $strFormDataID_a = FILEFORMAT_FIELD
// $strRelativeID_a = FILEFORMAT

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
function beforeDisplayAddUpdate_fileformat_field($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
    //$strTableNameFileFormatField = getTableNameEntity("fileformat", false);

    $arrJSONData = $arrJSONData_a;

    /*
    $strSQL = "select fileformattemplate_id returnvalue from ~TABLENAMEFILEFORMAT~ where id = ~FILEFORMATID~";
    $strSQL = str_replace('~TABLENAMEFILEFORMAT~', ff($strTableNameFileFormatField), $strSQL);
	$strSQL = str_replace('~FILEFORMATID~', ff($strRelativeID_a), $strSQL);
    $strFileFormatTemplateID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
    
    $strJSONData = json_encode($arrJSONData);

    $id = secureEntityValue('FILEFORMATTEMPLATE', $strFileFormatTemplateID);

    $strJSONData = str_replace('%%FILEFORMATTEMPLATEID%%', $id, $strJSONData);
    
    $arrJSONData = json_decode($strJSONData, true);
    */

	return $arrJSONData;
}

function beforeAddUpdate_fileformat_field($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_fileformat_field($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameFileFormatField = getTableNameEntity("fileformat_field", false);
	
	$arrJSONData = $arrJSONData_a;
				
	if (!$blnUpdate_a)
	{
		$strSQL = "update ~TABLENAMEFILEFORMATFIELD~ set fileformat_id = ~FILEFORMATID~ where id = ~ID~";
		$strSQL = str_replace('~TABLENAMEFILEFORMATFIELD~', ff($strTableNameFileFormatField), $strSQL);
		$strSQL = str_replace('~ID~', ff($strFormDataID_a), $strSQL);
		$strSQL = str_replace('~FILEFORMATID~', ff($strRelativeID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);											
    }
    
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_fileformat_field($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_fileformat_field($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	
}

function afterDelete_fileformat_field($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_fileformat_field($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
	
}
