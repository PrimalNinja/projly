<?php

// $strFormDataID_a = CLIENTPRODUCT
// $strRelativeID_a = 

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
function beforeDisplayAddUpdate_clientproduct($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
    $arrJSONData = $arrJSONData_a;
    return $arrJSONData;
}

function beforeAddUpdate_clientproduct($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    $arrJSONData = $arrJSONData_a;
    return $arrJSONData;
}


function afterAddUpdate_clientproduct($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
    return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_clientproduct($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;

    if (dependencies('security/profilesInitialise'))
	{
		// uninstall product's profile
		profilesInitialise($objConn_a, $strClientID_a);
    }     

	return $arrJSONData;
}

function beforeDelete_clientproduct($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{
}

function afterDelete_clientproduct($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{
    if (dependencies('security/clientProductDeactivate,security/profilesInitialise'))
	{
		// deactivate the product
		clientProductDeactivate($objConn_a, $strFormDataID_a);
		
		// uninstall product's profile
		profilesInitialise($objConn_a, $strClientID_a);
    }     

}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_clientproduct($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
}
