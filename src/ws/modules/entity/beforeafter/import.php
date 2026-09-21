<?php

// $strFormDataID_a = IMPORT

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
function beforeDisplayAddUpdate_import($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;

	$strImportDate = getDateTime();
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "gdc026800-9533-4d67-a347-7946a5401f72", "IMPORTDATE", $strImportDate);
	
	if (!$blnUpdate_a)
	{
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "gdc026800-9533-4d67-a347-7946a5401f72", "STATUS", "PENDING");
	}

	return $arrJSONData;
}

function beforeAddUpdate_import($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_import($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;			
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_import($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{	
	$strTableNameImport = getTableNameEntity("import", false);
	
	$arrJSONData = $arrJSONData_a;
	
	if (dependencies('entity/dataaccess/import') &&
		dependencies('import/doImport'))
	{
		$strDoImport = formValueGetBySectionCodeFieldCode($arrJSONData, "gdc026800-9533-4d67-a347-7946a5401f72", "DOIMPORT");
			
		if (toBoolean($strDoImport))
		{ 
			update_import($objConn_a, 
				[["DOIMPORT", "N", ""], ["STATUS", "IMPORTING", ""]], [], 
				$strFormDataID_a, []);
			
			// do the import
			$strFileTypeID = formValueGetBySectionCodeFieldCode($arrJSONData, "gdc026800-9533-4d67-a347-7946a5401f72", "FILETYPE");
			$arrFilesToImport = formValueGetBySectionCodeFieldCode($arrJSONData, "gdc026800-9533-4d67-a347-7946a5401f72", "FILETOIMPORT");
			$blnResult = doImport($objConn_a, $strFileTypeID, $arrFilesToImport);

			if ($blnResult)
			{
				update_import($objConn_a, 
					[["DOIMPORT", "N", ""], ["STATUS", "COMPLETE", ""]], [], 
					$strFormDataID_a, []);
			}
			else
			{
				update_import($objConn_a, 
					[["DOIMPORT", "N", ""], ["STATUS", "ERROR", ""]], [], 
					$strFormDataID_a, []);
			}
		}
	}
    
	return $arrJSONData;
}

function beforeDelete_import($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

function afterDelete_import($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_import($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
}
