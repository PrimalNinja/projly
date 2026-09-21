<?php

// $strFormDataID_a = VIDEO

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
function beforeDisplayAddUpdate_video($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
    $arrJSONData = $arrJSONData_a;
    return $arrJSONData;
}

function beforeAddUpdate_video($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameDocument = getTableNameEntity("document", false);
	
    $arrJSONData = $arrJSONData_a;

	$arrJSONVideoFileField = formFieldGetBySectionCodeFieldCode($arrJSONData_a, "ff3d1039d7-0f0f-48d1-96d1-596ace8b7185", "VIDEOFILE");
	$arrVideoFileDocument = $arrJSONVideoFileField['p_value'];
	if (count($arrVideoFileDocument) > 0)
	{
		$strURL = "";
		$strDocumentID = $arrVideoFileDocument[0]['documentid'];

		$strSQL = "select filenamebase returnvalue from ~TABLENAMEDOCUMENT~ where id = ~DOCUMENTID~";
		$strSQL = str_replace('~TABLENAMEDOCUMENT~', ff($strTableNameDocument), $strSQL);
		$strSQL = str_replace('~DOCUMENTID~', ff($strDocumentID), $strSQL);
		$strFilenameBase = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

		$strSQL = "select ffe70fd7f1_401b_4c5c_b096_76f67d466982_filename returnvalue from ~TABLENAMEDOCUMENT~ where id = ~DOCUMENTID~";
		$strSQL = str_replace('~TABLENAMEDOCUMENT~', ff($strTableNameDocument), $strSQL);
		$strSQL = str_replace('~DOCUMENTID~', ff($strDocumentID), $strSQL);
		$strFilename = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

		if (strlen($strFilenameBase) > 0)
		{
			$strURL = URL_APP_PATH . getClusterPath(REL_SYSTEMREPOSITORY_PRINT_DOCUMENT, $strFilenameBase, false, false) . $strFilenameBase . '-' . $strFilename;
		}
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff3d1039d7-0f0f-48d1-96d1-596ace8b7185", "URL", $strURL);
	}

    return $arrJSONData;
}


function afterAddUpdate_video($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    $arrJSONData = $arrJSONData_a;
    return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_video($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    $arrJSONData = $arrJSONData_a;
    return $arrJSONData;
}

function beforeDelete_video($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{
}

function afterDelete_video($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_video($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
}
