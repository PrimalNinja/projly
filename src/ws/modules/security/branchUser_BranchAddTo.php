<?php
 
// $strFormEntityID_a = USER
// $strRelativeID_a = BRANCH
function branchUser_BranchAddTo($objConn_a, $strClientID_a, $strFormEntityIDList_a, $strRelativeID_a, $strRelationship_a) 
{ 
	$strTableNameBranchUser = getTableNameEntity("branch_user", false);
	$strTableNameUser = getTableNameEntity("user", false);
	
    $strResult = '';
	
    $strLogin = $_SESSION['server_loggedin_user'];
	
	// validations
	$strSQL = "select count(*) returnvalue from ~TABLENAMEBRANCHUSER~ where client_id = ~CLIENTID~ and user_id in (~USERIDLIST~) and branch_id = ~BRANCHID~";
	$strSQL = str_replace("~TABLENAMEBRANCHUSER~", ff($strTableNameBranchUser), $strSQL);
	$strSQL = str_replace("~CLIENTID~", ff($strClientID_a), $strSQL);
	$strSQL = str_replace("~USERIDLIST~", $strFormEntityIDList_a, $strSQL);
	$strSQL = str_replace("~BRANCHID~", ff($strRelativeID_a), $strSQL);
	$intCount = intval(dbReadValue($objConn_a, $strSQL, __FUNCTION__), 10);

	if ($intCount > 0)
	{
		dbRaiseCustomError($objConn_a, "A duplicate user is found.");
	}
	else
	{
	
		dbBeginTrans($objConn_a, __FUNCTION__);
		
		$strSQL = "
	insert into ~TABLENAMEBRANCHUSER~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, user_id, branch_id)
	select ~CLIENTID~, entity_id, dataentity_id, code, description, is_enabled, ~CLIENTID~, null, '~MODIFYUSER~', '~MODIFYDATETIME~', id, ~RELATIVEID~ from ~TABLENAMEUSER~ where id in (~USERIDLIST~)
	";
		
		$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
		$strSQL = str_replace('~TABLENAMEBRANCHUSER~', ff($strTableNameBranchUser), $strSQL);
		$strSQL = str_replace('~USERIDLIST~', $strFormEntityIDList_a, $strSQL);
		$strSQL = str_replace('~RELATIVEID~', ff($strRelativeID_a), $strSQL);
		$strSQL = str_replace("~CLIENTID~", ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
		$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);

		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		$strResult = dbLastInsertID($objConn_a);  
		
		dbEndTrans($objConn_a, __FUNCTION__);
	}

    return $strResult;
    
}
