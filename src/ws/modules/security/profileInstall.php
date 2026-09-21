<?php

// install a profile
// note: installing a sysadmin will only copy the non-sysadmin permissions
function profileInstall($objConn_a, $strClientIDFrom_a, $strClientIDTo_a, $strProfileIDFrom_a)
{
	$strTableNamePermission = getTableNameEntity("permission", false);
	$strTableNameProfile = getTableNameEntity("profile", false);
	$strTableNameProfilePermission = getTableNameEntity("profile_permission", false);

    $strProfileIDTo = "";

	dbBeginTrans($objConn_a, __FUNCTION__);

	$strLogin = $_SESSION['server_loggedin_user'];
	
	// create the profile
	$strSQL =
		"
insert into ~TABLENAMEPROFILE~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, is_defined, is_sysadmin)
select ~CLIENTIDTO~, entity_id, dataentity_id, code, description, is_enabled, ~CLIENTIDTO~, jsondata, '~MODIFYUSER~', '~MODIFYDATETIME~', 'Y', 'N'
from ~TABLENAMEPROFILE~ where client_id = ~CLIENTIDFROM~ and id = ~PROFILEIDFROM~
";
	$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
	$strSQL = str_replace('~CLIENTIDFROM~', ff($strClientIDFrom_a), $strSQL);
	$strSQL = str_replace('~CLIENTIDTO~', ff($strClientIDTo_a), $strSQL);
	$strSQL = str_replace('~PROFILEIDFROM~', ff($strProfileIDFrom_a), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	$strProfileIDTo = dbLastInsertID($objConn_a);
	
	$strSQL = "select jsondata returnvalue from ~TABLENAMEPROFILE~ where id = ~PROFILEID~";
	$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
	$strSQL = str_replace('~PROFILEID~', ff($strProfileIDTo), $strSQL);
	$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	exposeEntityData($objConn_a, 'SYSTEMFORM', 'PROFILE', $strProfileIDTo, $strJSONData);
	
	// clone profile permissions
	$strSQL =
		"
insert into ~TABLENAMEPROFILEPERMISSION~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, profile_id, permission_id)
select ~CLIENTIDTO~, entity_id, dataentity_id, code, description, 'Y', ~CLIENTIDTO~, null, '~MODIFYUSER~', '~MODIFYDATETIME~', ~PROFILEIDTO~, permission_id
from ~TABLENAMEPROFILEPERMISSION~ where client_id = ~CLIENTIDFROM~ and profile_id = ~PROFILEIDFROM~ and permission_id in (select id from ~TABLENAMEPERMISSION~ where 0000eae3_e5b8_4ebb_a3a8_50220cee15d5_isnonsysadmin = 'Y')
";
	$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
	$strSQL = str_replace('~TABLENAMEPROFILEPERMISSION~', ff($strTableNameProfilePermission), $strSQL);
	$strSQL = str_replace('~CLIENTIDFROM~', ff($strClientIDFrom_a), $strSQL);
	$strSQL = str_replace('~CLIENTIDTO~', ff($strClientIDTo_a), $strSQL);
	$strSQL = str_replace('~PROFILEIDFROM~', ff($strProfileIDFrom_a), $strSQL);
	$strSQL = str_replace('~PROFILEIDTO~', ff($strProfileIDTo), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	dbEndTrans($objConn_a, __FUNCTION__);

    return $strProfileIDTo;
}
