<?php
 
// $strFormEntityID_a = BRANCH
// $strRelativeID_a = USER
function userBranch_UserAddTo($objConn_a, $strClientID_a, $strFormEntityIDList_a, $strRelativeID_a, $strRelationship_a) 
{ 
	$strTableNameBranch = getTableNameEntity("branch", false);
	$strTableNameUserBranch = getTableNameEntity("user_branch", false);
	
    $strResult = '';
	
    $strLogin = $_SESSION['server_loggedin_user'];
	
	// validations
	$strSQL = "select count(*) returnvalue from ~TABLENAMEUSERBRANCH~ where client_id = ~CLIENTID~ and branch_id in (~BRANCHIDLIST~) and user_id = ~USERID~";
	$strSQL = str_replace("~TABLENAMEUSERBRANCH~", ff($strTableNameUserBranch), $strSQL);
	$strSQL = str_replace("~CLIENTID~", ff($strClientID_a), $strSQL);
	$strSQL = str_replace("~BRANCHIDLIST~", $strFormEntityIDList_a, $strSQL);
	$strSQL = str_replace("~USERID~", ff($strRelativeID_a), $strSQL);
	$intCount = intval(dbReadValue($objConn_a, $strSQL, __FUNCTION__), 10);

	if ($intCount > 0)
	{
		dbRaiseCustomError($objConn_a, "A duplicate branch is found.");
	}
	else
	{
	
		dbBeginTrans($objConn_a, __FUNCTION__);
		
		$strSQL = "
	insert into ~TABLENAMEUSERBRANCH~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, branch_id, user_id)
	select ~CLIENTID~, entity_id, dataentity_id, code, description, is_enabled, ~CLIENTID~, null, '~MODIFYUSER~', '~MODIFYDATETIME~', id, ~RELATIVEID~ from ~TABLENAMEBRANCH~ where id in (~BRANCHIDLIST~)
	";
		
		$strSQL = str_replace('~TABLENAMEBRANCH~', ff($strTableNameBranch), $strSQL);
		$strSQL = str_replace('~TABLENAMEUSERBRANCH~', ff($strTableNameUserBranch), $strSQL);
		$strSQL = str_replace('~BRANCHIDLIST~', $strFormEntityIDList_a, $strSQL);
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
