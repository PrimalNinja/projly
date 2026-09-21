<?php

// track device
function trackDeviceFailure($objConn_a, $strClientID_a, $strClientCode_a, $strLogin_a, $strIPAddress_a, $strUserAgent_a, $strDeviceCode_a, $strNotes_a)
{
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameDevice = getTableNameEntity("device", false);
	$strTableNameDeviceLog = getTableNameEntity("devicelog", false);
	
	$strClientID = $strClientID_a;
	
	if (strlen($strClientID) == 0)
	{
		// make it the owner id so they can see the login attempt
		$strClientID = getSystemOwnerClientID($objConn_a);
	}
	
	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, "devicelog");
		
	$strDeviceCode = $strDeviceCode_a;
	$strDeviceDescription = "";
	$strIsEnabled = "";
	
	$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "DEVICELOG");

	if (strlen($strDeviceCode_a) > 0)
	{
		$strSQL = "select info_devicecode code, description, is_enabled from ~TABLENAMEDEVICE~ where info_devicecode = '~DEVICECODE~'";
		$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
		$strSQL = str_replace('~DEVICECODE~', ff($strDeviceCode_a), $strSQL);
		$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		while ($arrRow = dbReadRecord($objResult)) 
		{
			$strDeviceCode = $arrRow['code'];
			$strDeviceDescription = $arrRow['description'];
			$strIsEnabled = $arrRow['is_enabled'];
		}
	}
	
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'ff26af4dd-710e-4510-8153-052ed948e34e', "CLIENTCODE", $strClientCode_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'ff26af4dd-710e-4510-8153-052ed948e34e', "USER", $strLogin_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'ff26af4dd-710e-4510-8153-052ed948e34e', "DEVICECODE", $strDeviceCode);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'ff26af4dd-710e-4510-8153-052ed948e34e', "DESCRIPTION", $strDeviceDescription);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'ff26af4dd-710e-4510-8153-052ed948e34e', "IPADDRESS", $strIPAddress_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'ff26af4dd-710e-4510-8153-052ed948e34e', "USERAGENT", $strUserAgent_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'ff26af4dd-710e-4510-8153-052ed948e34e', "ISENABLED", $strIsEnabled);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'ff26af4dd-710e-4510-8153-052ed948e34e', "NOTES", $strNotes_a);
	$strJSONData = json_encode($arrJSONData);
		
    dbBeginTrans($objConn_a, __FUNCTION__);

    $strSQL = "
insert into ~TABLENAMEDEVICELOG~ (device_id, client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, clientcode, login, ipaddress, useragent, notes)
select null, id, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', id, '~JSONDATA~', 'system', '~MODIFYDATETIME~', '~CLIENTCODE~', '~LOGIN~', '~IPADDRESS~', '~USERAGENT~', '~NOTES~'
from ~TABLENAMECLIENT~
where id = ~CLIENTID~
    ";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEDEVICELOG~', ff($strTableNameDeviceLog), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ffn($strClientID), $strSQL);
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
	$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strDeviceCode), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strDeviceDescription), $strSQL);
	$strSQL = str_replace('~ISENABLED~', ff($strIsEnabled), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	$strSQL = str_replace('~CLIENTCODE~', ff($strClientCode_a), $strSQL);
	$strSQL = str_replace('~LOGIN~', ff($strLogin_a), $strSQL);
	$strSQL = str_replace('~IPADDRESS~', ff($strIPAddress_a), $strSQL);
	$strSQL = str_replace('~USERAGENT~', ff($strUserAgent_a), $strSQL);
    $strSQL = str_replace('~NOTES~', ff($strNotes_a), $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

    return dbEndTrans($objConn_a, __FUNCTION__);
}
