<?php

// $strFormDataID_a = PROJECTCAMPAIGNTASK
// $strRelativeID_a = PROJECTCAMPAIGN

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
function beforeDisplayAddUpdate_projectcampaigntask($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;

	if (dependencies('setting/sequenceTypeGet,setting/campaignTaskCodeAllocate'))
	{
		// enable sequence for standard override and manual, disable it for others
		// default sequence for all except for manual
		$strSequenceType = sequenceTypeGet($objConn_a, '', 'PROJLY', 'CAMPAIGNTASKID', $strClientID_a);
		if (($strSequenceType == "manual") || ($strSequenceType == "standardoverride"))
		{
			// enable field
			$arrJSONData = makeFieldReadOnlyBySectionCodeFieldCode($arrJSONData, "g4ec1b214-f01d-4c37-beda-aa6a8dbbf78c", "CODE", "N");
		}
		else
		{
			// disable field
			$arrJSONData = makeFieldReadOnlyBySectionCodeFieldCode($arrJSONData, "g4ec1b214-f01d-4c37-beda-aa6a8dbbf78c", "CODE", "Y");
		}

		if ($blnUpdate_a == false)
		{
			if ($strSequenceType != "manual")
			{
				// allocate sequence
				$strCode = campaignTaskCodeAllocate($objConn_a, $strClientID_a);
				$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g4ec1b214-f01d-4c37-beda-aa6a8dbbf78c", "CODE", $strCode);
			}
		}
	}

	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g4ec1b214-f01d-4c37-beda-aa6a8dbbf78c", "RAISEDDATE", getISODate());
	
	return $arrJSONData;
}

function beforeAddUpdate_projectcampaigntask($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_projectcampaigntask($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_projectcampaigntask($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_projectcampaigntask($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

function afterDelete_projectcampaigntask($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_projectcampaigntask($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
}
