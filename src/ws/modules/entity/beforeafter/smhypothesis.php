<?php

// $strFormDataID_a = SMHYPOTHESIS
// $strRelativeID_a = SMQUESTION

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
function beforeDisplayAddUpdate_smhypothesis($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
    $arrJSONData = $arrJSONData_a;
    return $arrJSONData;
}

function beforeAddUpdate_smhypothesis($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    $arrJSONData = $arrJSONData_a;

    return $arrJSONData;
}


function afterAddUpdate_smhypothesis($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    $strTableNameSMHypothesis = getTableNameEntity("smhypothesis", false);

    $arrJSONData = $arrJSONData_a;

    if ($blnUpdate_a === false)
    {
        $strSQL = "update ~TABLENAMESMHYPOTHESIS~ set smquestion_id = ~RELATIVEID~ where id = ~ID~";
        $strSQL = str_replace('~TABLENAMESMHYPOTHESIS~', ff($strTableNameSMHypothesis), $strSQL);
        $strSQL = str_replace('~ID~', ff($strFormDataID_a), $strSQL);
        $strSQL = str_replace('~RELATIVEID~', ff($strRelativeID_a), $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
    }

    return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_smhypothesis($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    $arrJSONData = $arrJSONData_a;

    return $arrJSONData;
}

function beforeDelete_smhypothesis($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{
}

function afterDelete_smhypothesis($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_smhypothesis($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
}
