<?php

// $strFormDataID_a = SYSTEMMODULETASK
// $strRelativeID_a = SYSTEMMODULE

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
function beforeDisplayAddUpdate_systemmoduletask($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
    $arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeAddUpdate_systemmoduletask($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    $arrJSONData = $arrJSONData_a;    
	return $arrJSONData;
}

function afterAddUpdate_systemmoduletask($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameSystemModuleTask = getTableNameEntity("systemmoduletask", false);

    if ($blnUpdate_a === false) // add
    {
        $strSQL = "update ~TABLENAMESYSTEMMODULETASK~ set systemmodule_id = ~SYSTEMMODULEID~, sortorder = id where id = ~ID~";
        $strSQL = str_replace('~TABLENAMESYSTEMMODULETASK~', ff($strTableNameSystemModuleTask), $strSQL);
		$strSQL = str_replace("~SYSTEMMODULEID~", ff($strRelativeID_a), $strSQL);
        $strSQL = str_replace("~ID~", ff($strFormDataID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
    }

	$arrJSONData = $arrJSONData_a;
	
    $strLogin = $_SESSION['server_loggedin_user'];
		
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_systemmoduletask($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_systemmoduletask($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

function afterDelete_systemmoduletask($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_systemmoduletask($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{	
}
