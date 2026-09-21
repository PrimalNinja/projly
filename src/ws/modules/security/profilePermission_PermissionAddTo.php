<?php
 
// $strFormEntityID_a = PERMISSION
// $strRelativeID_a = PROFILE
function profilePermission_PermissionAddTo($objConn_a, $strClientID_a, $strFormEntityIDList_a, $strRelativeID_a, $strRelationship_a) 
{ 
	$strTableNamePermission = getTableNameEntity("permission", false);
	$strTableNameProfilePermission = getTableNameEntity("profile_permission", false);
	
    $strResult = '';
	
    $strLogin = $_SESSION['server_loggedin_user'];
	
	// validations
	$strSQL = "select count(*) returnvalue from ~TABLENAMEPROFILEPERMISSION~ where client_id = ~CLIENTID~ and permission_id in (~PERMISSIONIDLIST~) and profile_id = ~PROFILEID~";
	$strSQL = str_replace("~TABLENAMEPROFILEPERMISSION~", ff($strTableNameProfilePermission), $strSQL);
	$strSQL = str_replace("~CLIENTID~", ff($strClientID_a), $strSQL);
	$strSQL = str_replace("~PERMISSIONIDLIST~", $strFormEntityIDList_a, $strSQL);
	$strSQL = str_replace("~PROFILEID~", ff($strRelativeID_a), $strSQL);
	$intCount = intval(dbReadValue($objConn_a, $strSQL, __FUNCTION__), 10);

	if ($intCount > 0)
	{
		dbRaiseCustomError($objConn_a, "A duplicate permission is found.");
	}
	else
	{
	
		dbBeginTrans($objConn_a, __FUNCTION__);
		
		$strSQL = "
	insert into ~TABLENAMEPROFILEPERMISSION~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, permission_id, profile_id)
	select ~CLIENTID~, entity_id, dataentity_id, code, description, is_enabled, ~CLIENTID~, null, '~MODIFYUSER~', '~MODIFYDATETIME~', id, ~RELATIVEID~ from ~TABLENAMEPERMISSION~ where id in (~PERMISSIONIDLIST~)
	";
		
		$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
		$strSQL = str_replace('~TABLENAMEPROFILEPERMISSION~', ff($strTableNameProfilePermission), $strSQL);
		$strSQL = str_replace('~PERMISSIONIDLIST~', $strFormEntityIDList_a, $strSQL);
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
