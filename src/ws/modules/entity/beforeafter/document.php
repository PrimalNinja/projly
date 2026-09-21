<?php

// $strFormDataID_a = DOCUMENT

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
function beforeDisplayAddUpdate_document($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeAddUpdate_document($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_document($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameDocument = getTableNameEntity("document", false);
	
	$arrJSONData = $arrJSONData_a;
    
    $strTags = formValueGetBySectionCodeFieldCode($arrJSONData, "ffe70fd7f1-401b-4c5c-b096-76f67d466982", "TAGS");
    	
    if (strlen(trim($strTags)) > 0) 
    { 
       $strIsTagless = 'N';
    }
    else { 
       $strIsTagless = 'Y'; 
    }
    
    $strSQL = "update ~TABLENAMEDOCUMENT~ set is_tagless = '~ISTAGLESS~' where id = ~ID~";
    $strSQL = str_replace('~TABLENAMEDOCUMENT~', ff($strTableNameDocument), $strSQL);
    $strSQL = str_replace('~ISTAGLESS~', ff($strIsTagless), $strSQL);
    $strSQL = str_replace('~ID~', ff($strFormDataID_a), $strSQL);
    
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
    
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_document($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{	
	$arrJSONData = $arrJSONData_a;			
	return $arrJSONData;
}

function beforeDelete_document($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	$strTableNameDocument = getTableNameEntity("document", false);
	
	$strFilename = "";
	$strFilenameBase = "";
	
	$strSQL = "select filenamebase, ffe70fd7f1_401b_4c5c_b096_76f67d466982_filename filename from ~TABLENAMEDOCUMENT~ where id = ~DOCUMENTID~";
	$strSQL = str_replace('~TABLENAMEDOCUMENT~', ff($strTableNameDocument), $strSQL);
	$strSQL = str_replace('~DOCUMENTID~', ff($strFormDataID_a), $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	if ($arrRow = dbReadRecord($objResult)) {
		$strFilename = $arrRow['filename'];
		$strFilenameBase = $arrRow['filenamebase'];
	}
	dbCloseRecordset($objResult);			
		
	$strDocumentRoot = getDocumentRootPathByDocumentID($objConn_a, $strFormDataID_a);
	$strSourcePath = getClusterPath($strDocumentRoot, $strFilenameBase, false, false) . $strFilenameBase . '-' . $strFilename;		
	$intFilesize = floatval(@filesize($strSourcePath));

    updateDocumentRepositoryDocumentCountDocumentID($objConn_a, $strFormDataID_a);
	updateDocumentRepositoryStorageSizeByDocumentID($objConn_a, $strFormDataID_a, -$intFilesize);
	updateDocumentRepositoryDeletedStorageSizeByDocumentID($objConn_a, $strFormDataID_a, $intFilesize);		
}

function afterDelete_document($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_document($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
	
}
