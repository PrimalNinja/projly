<?php

// add a profile
function profileAdd($objConn_a, $strClientID_a, $strCode_a, $strDescription_a, $strEnabled_a, $strAdmin_a, $strDefault_a, $strSysAdmin_a, $strDefined_a)
{
	$strTableNameProfile = getTableNameEntity("profile", false);

    $strProfileID = "";

	dbBeginTrans($objConn_a, __FUNCTION__);

	$strLogin = $_SESSION['server_loggedin_user'];
	
	$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "PROFILE");
	
	// get the entity ids
	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, "profile");

	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f9a0f134f-54b8-4e11-81b3-02d4f51dadd0", "CODE", $strCode_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f9a0f134f-54b8-4e11-81b3-02d4f51dadd0", "DESCRIPTION", $strDescription_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f9a0f134f-54b8-4e11-81b3-02d4f51dadd0", "ISENABLED", $strEnabled_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f9a0f134f-54b8-4e11-81b3-02d4f51dadd0", "ISADMIN", $strAdmin_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f9a0f134f-54b8-4e11-81b3-02d4f51dadd0", "ISDEFAULT", $strDefault_a);
	$strJSONData = json_encode($arrJSONData);
	
	$strSQL =
		"
insert into ~TABLENAMEPROFILE~ (client_id, entity_id, dataentity_id, code, description, is_enabled, jsondata, data_client_id, modifyuser, modifydatetime, is_sysadmin, is_defined)
values (~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ENABLED~', '~JSONDATA~', ~DATACLIENTID~, '~MODIFYUSER~', '~MODIFYDATETIME~', '~SYSADMIN~', '~DEFINED~')
";
	$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
	$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strCode_a), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strDescription_a), $strSQL);
	$strSQL = str_replace('~ENABLED~', ff($strEnabled_a), $strSQL);
	$strSQL = str_replace('~DATACLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	$strSQL = str_replace('~SYSADMIN~', ff($strSysAdmin_a), $strSQL);
	$strSQL = str_replace('~DEFINED~', ff($strDefined_a), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	$strProfileID = dbLastInsertID($objConn_a);
	
	exposeEntityData($objConn_a, 'SYSTEMFORM', 'PROFILE', $strProfileID, $strJSONData);

	dbEndTrans($objConn_a, __FUNCTION__);

    return $strProfileID;
}
