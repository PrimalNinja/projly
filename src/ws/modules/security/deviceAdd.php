<?php

// add a device
function deviceAdd($objConn_a, $strClientID_a, $strUserID_a, $strCode_a, $strDescription_a, $strIPAddress_a, $strUserAgent_a, $strCapabilities_a)
{
	$strTableNameDevice = getTableNameEntity("device", false);
	$strTableNameUser = getTableNameEntity("user", false);
	
    $strDeviceID = "";

	$strUserName = dbGetDescriptionFromID($objConn_a, $strTableNameUser, $strUserID_a, __FUNCTION__);

	$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "DEVICE");
	
	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, "device");

	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "fff26af3dd-710e-4510-8153-058ed948e34e", "CODE", $strCode_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "fff26af3dd-710e-4510-8153-058ed948e34e", "DESCRIPTION", $strDescription_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "fff26af3dd-710e-4510-8153-058ed948e34e", "ISENABLED", 'Y');
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "fff26af3dd-710e-4510-8153-058ed948e34e", "USER", $strUserName);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "fff26af3dd-710e-4510-8153-058ed948e34e", "ISAUTHENTICATED", 'Y');

	// default device settings
	// QUEUEING.PRINTFROMQ
	// QUEUEING.PQ4OR
	// PRINTING.P4OR
	
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "INFO", "DEVICECODE", $strCode_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "INFO", "IPADDRESS", $strIPAddress_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "INFO", "USERAGENT", $strUserAgent_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "INFO", "CAPABILITIES", $strCapabilities_a);
	
	$strJSONData = json_encode($arrJSONData);

	dbBeginTrans($objConn_a, __FUNCTION__);

	$strLogin = $_SESSION['server_loggedin_user'];

	$strSQL = "
insert into ~TABLENAMEDEVICE~ (
client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, user_id, is_authenticated
) values (
~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', 'Y', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~', ~USERID~, 'Y'
)
";
	$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
	$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strCode_a), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strDescription_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	$strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	$strDeviceID = dbLastInsertID($objConn_a);

	exposeEntityData($objConn_a, 'SYSTEMFORM', 'DEVICE', $strDeviceID, $strJSONData);
	
	$strAlert = "added device '" . $strDescription_a . "' from ipaddress '" . $strIPAddress_a . "'";
	//alertAdd($objConn_a, $strClientID_a, $strUserID_a, $strAlert);

	dbEndTrans($objConn_a, __FUNCTION__);

    return $strDeviceID;
}
