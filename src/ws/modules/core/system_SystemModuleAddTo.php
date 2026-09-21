<?php
 
// $strFormEntityID_a = SYSTEMMODULE
// $strRelativeID_a = system
function system_SystemModuleAddTo($objConn_a, $strClientID_a, $strFormEntityIDList_a, $strRelativeID_a, $strRelationship_a) 
{     
    $strTableNameSystemModule = getTableNameEntity("systemmodule", false);
    $strTableNameSystemSystemModule = getTableNameEntity("system_systemmodule", false);
	
    $strResult = '';
	
    $strLogin = $_SESSION['server_loggedin_user'];
	
	// validations
	$strSQL = "select count(*) returnvalue from ~TABLENAMESYSTEMSYSTEMMODULE~ where client_id = ~CLIENTID~ and systemmodule_id in (~SYSTEMMODULEIDLIST~) and system_id = ~SYSTEMID~";
	$strSQL = str_replace("~TABLENAMESYSTEMSYSTEMMODULE~", ff($strTableNameSystemSystemModule), $strSQL);
	$strSQL = str_replace("~CLIENTID~", ff($strClientID_a), $strSQL);
	$strSQL = str_replace("~SYSTEMMODULEIDLIST~", $strFormEntityIDList_a, $strSQL);
	$strSQL = str_replace("~SYSTEMID~", ff($strRelativeID_a), $strSQL);
	$intCount = intval(dbReadValue($objConn_a, $strSQL, __FUNCTION__), 10);

	if ($intCount > 0)
	{
		dbRaiseCustomError($objConn_a, "A duplicate system module is found.");
	}
	else
	{
		dbBeginTrans($objConn_a, __FUNCTION__);
		
		$strSQL = "
	insert into ~TABLENAMESYSTEMSYSTEMMODULE~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, systemmodule_id, system_id)
	select ~CLIENTID~, entity_id, dataentity_id, code, description, is_enabled, ~CLIENTID~, null, '~MODIFYUSER~', '~MODIFYDATETIME~', id, ~RELATIVEID~ from ~TABLENAMESYSTEMMODULE~ where id in (~SYSTEMMODULEIDLIST~)
	";
		
		$strSQL = str_replace('~TABLENAMESYSTEMMODULE~', ff($strTableNameSystemModule), $strSQL);
		$strSQL = str_replace('~TABLENAMESYSTEMSYSTEMMODULE~', ff($strTableNameSystemSystemModule), $strSQL);
		$strSQL = str_replace('~SYSTEMMODULEIDLIST~', $strFormEntityIDList_a, $strSQL);
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
