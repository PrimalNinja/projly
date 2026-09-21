<?php

// $strFormEntityID_a = PROFILE
// $strRelativeID_a = USER
function userProfile_ProfileAddTo($objConn_a, $strClientID_a, $strFormEntityID_a, $strRelativeID_a, $strRelationship_a) 
{ 
	$strTableNameProfile = getTableNameEntity("profile", false);
	$strTableNameUserProfile = getTableNameEntity("user_profile", false);
	
    $strResult = '';
	
    $strLogin = $_SESSION['server_loggedin_user'];
	
	// validations
	$strSQL = "select count(*) returnvalue from ~TABLENAMEUSERPROFILE~ where client_id = ~CLIENTID~ and profile_id = ~PROFILEID~ and user_id = ~USERID~";
	$strSQL = str_replace("~TABLENAMEUSERPROFILE~", ff($strTableNameUserProfile), $strSQL);
	$strSQL = str_replace("~CLIENTID~", ff($strClientID_a), $strSQL);
	$strSQL = str_replace("~PROFILEID~", ff($strFormEntityID_a), $strSQL);
	$strSQL = str_replace("~USERID~", ff($strRelativeID_a), $strSQL);
	$intCount = intval(dbReadValue($objConn_a, $strSQL, __FUNCTION__), 10);

	if ($intCount > 0)
	{
		dbRaiseCustomError($objConn_a, "A duplicate profile is found.");
	}
	else
	{
	
		dbBeginTrans($objConn_a, __FUNCTION__);
		
		$strSQL = "
	insert into ~TABLENAMEUSERPROFILE~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, profile_id, user_id)
	select client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, '~MODIFYUSER~', '~MODIFYDATETIME~', ~FORMENTITYID~, ~RELATIVEID~ from ~TABLENAMEPROFILE~ where id = ~FORMENTITYID~
	";
		
		$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
		$strSQL = str_replace('~TABLENAMEUSERPROFILE~', ff($strTableNameUserProfile), $strSQL);
		$strSQL = str_replace('~FORMENTITYID~', ff($strFormEntityID_a), $strSQL);
		$strSQL = str_replace('~RELATIVEID~', ff($strRelativeID_a), $strSQL);
		$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
		$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		$strResult = dbLastInsertID($objConn_a);  
		
		dbEndTrans($objConn_a, __FUNCTION__);
	}

    return $strResult;
    
}
