<?php

// add a client
// note: client table is the only table likley that needs no client_id or data_client_id (they are ok to be null)
function clientAdd($objConn_a, $strCode_a, $strDescription_a, $strLogin_a, $strEmailAddress_a, $strPhoneNumber_a, $strEnabled_a)
{
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameRegistrationType = getTableNameEntity("registrationtype", false);
	
    $strClientID = "";
	
	dbBeginTrans($objConn_a, __FUNCTION__);

	$strLogin = $_SESSION['server_loggedin_user'];

	$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "CLIENT");

	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, "client");

	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f8d9bfb3a-3eb0-4620-ad86-af7b4e222325", "CODE", $strCode_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f8d9bfb3a-3eb0-4620-ad86-af7b4e222325", "DESCRIPTION", $strDescription_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f8d9bfb3a-3eb0-4620-ad86-af7b4e222325", "LOGIN", $strLogin_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f8d9bfb3a-3eb0-4620-ad86-af7b4e222325", "EMAILADDRESS", $strEmailAddress_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f8d9bfb3a-3eb0-4620-ad86-af7b4e222325", "PHONENUMBER", $strPhoneNumber_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "f8d9bfb3a-3eb0-4620-ad86-af7b4e222325", "ISENABLED", $strEnabled_a);
	
	$strJSONData = json_encode($arrJSONData);

	$strSQL =
		"
insert into ~TABLENAMECLIENT~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, insertguid, is_defined, modifyuser, modifydatetime)
values (null, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ENABLED~', null, '~JSONDATA~', '', 'Y', '~MODIFYUSER~', '~MODIFYDATETIME~')
";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
	$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strCode_a), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strDescription_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~ENABLED~', ff($strEnabled_a), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	$strClientID = dbLastInsertID($objConn_a);

	exposeEntityData($objConn_a, 'SYSTEMFORM', 'CLIENT', $strClientID, $strJSONData);

	dbEndTrans($objConn_a, __FUNCTION__);

    return $strClientID;
}
