<?php

// $strFormDataID_a = SYSTEMFORM

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
function beforeDisplayAddUpdate_systemform($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
    $arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeAddUpdate_systemform($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    $arrJSONData = $arrJSONData_a;    
	return $arrJSONData;
}

function afterAddUpdate_systemform($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{	
	$arrJSONData = $arrJSONData_a;
        
    if (dependencies('entity/entitySystemFormFileJSONCreate'))
    {
        entitySystemFormFileJSONCreate($strFormEntityCode_a, $arrJSONData);
    }

	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_systemform($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_systemform($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{          
    $strTableNameFormLayout = getTableNameEntity("formlayout", false);
	$strTableNameSystemform = getTableNameEntity("systemform", false);
    
    $strSQL = "select code returnvalue from ~TABLENAMESYSTEMFORM~ where id = ~SYSTEMFORMID~";
    $strSQL = str_replace('~TABLENAMESYSTEMFORM~', ff($strTableNameSystemform), $strSQL);
    $strSQL = str_replace('~SYSTEMFORMID~', ff($strFormDataID_a), $strSQL);
    $strSystemFormCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
    
    $strSQL = "select g38e1d8b1_b01e_44d6_bd90_102f0aa92f60_cannotunpublish returnvalue from ~TABLENAMEFORMLAYOUT~ where code = '~CODE~'";
    $strSQL = str_replace('~TABLENAMEFORMLAYOUT~', ff($strTableNameFormLayout), $strSQL);
    $strSQL = str_replace('~CODE~', ff($strSystemFormCode), $strSQL);
    $strCannotUnpublish = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    if ($strCannotUnpublish == 'Y') 
    {
        // note from jhun. I'm not sure how to properly raise an error.
		dbRaiseCustomError($objConn_a, "This form cannot be deleted or unpublished.");
    }        
}

function afterDelete_systemform($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{   
    if (dependencies('entity/entitySystemFormFileJSONDelete'))
    {
        entitySystemFormFileJSONDelete($arrRow_a['code']);
    } 
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_systemform($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{	
}
