<?php

// $strFormDataID_a = BRANCH

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
function beforeDisplayAddUpdate_branch($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeAddUpdate_branch($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_branch($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{	
	$strTableNameBranch = getTableNameEntity("branch", false);
	$strTableNameUserBranch = getTableNameEntity("user_branch", false);
	
	$arrJSONData = $arrJSONData_a;

    $strLogin = $_SESSION['server_loggedin_user'];
	$strUserID = $_SESSION['server_loggedin_userid'];
	
	if ($blnUpdate_a)
	{
		// do nothing
	}
	else
	{
		dbBeginTrans($objConn_a, __FUNCTION__);
		
		$strSQL = "
	insert into ~TABLENAMEUSERBRANCH~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, branch_id, user_id)
	select ~CLIENTID~, entity_id, dataentity_id, code, description, is_enabled, ~CLIENTID~, null, '~MODIFYUSER~', '~MODIFYDATETIME~', id, ~USERID~ from ~TABLENAMEBRANCH~ where id in (~BRANCHIDLIST~)
	";
		
		$strSQL = str_replace('~TABLENAMEBRANCH~', ff($strTableNameBranch), $strSQL);
		$strSQL = str_replace('~TABLENAMEUSERBRANCH~', ff($strTableNameUserBranch), $strSQL);
		$strSQL = str_replace('~BRANCHIDLIST~', $strFormDataID_a, $strSQL);
		$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
		$strSQL = str_replace("~CLIENTID~", ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
		$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
		dbEndTrans($objConn_a, __FUNCTION__);
	}
	
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_branch($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;	
	return $arrJSONData;
}

function beforeDelete_branch($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	
}

function afterDelete_branch($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_branch($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
	
}
