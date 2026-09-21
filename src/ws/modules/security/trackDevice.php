<?php

// track device
function trackDevice($objConn_a, $strClientID_a, $strClientCode_a, $strLogin_a, $strIPAddress_a, $strUserAgent_a, $strDeviceID_a, $strNotes_a)
{
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameDevice = getTableNameEntity("device", false);
	$strTableNameDeviceLog = getTableNameEntity("devicelog", false);
	
	$blnResult = false;

	// make it the owner id so they can see the login attempt
	$strBatchClientID = getBatchClientID($objConn_a);
	$strPublicClientID = getPublicClientID($objConn_a);

	// don't log successful public logins as it fills up the logs with bots
	if (($strClientID_a != $strBatchClientID) && ($strClientID_a != $strPublicClientID))
	{
		$strEntityID = getEntityID($objConn_a, "systemform");
		$strDataEntityID = getEntityID($objConn_a, "devicelog");
		
		$strDeviceCode = "";
		$strDeviceDescription = "";
		$strIsEnabled = "";
		
        $arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "DEVICELOG");

		if (strlen($strDeviceID_a) > 0)
		{
			$strSQL = "select info_devicecode code, description, is_enabled from ~TABLENAMEDEVICE~ where id = ~DEVICEID~";
			$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
			$strSQL = str_replace('~DEVICEID~', ff($strDeviceID_a), $strSQL);
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
	select ~DEVICEID~, d.client_id, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', d.data_client_id, '~JSONDATA~', d.modifyuser, d.modifydatetime, '~CLIENTCODE~', '~LOGIN~', '~IPADDRESS~', '~USERAGENT~', '~NOTES~'
	from ~TABLENAMEDEVICE~ d, ~TABLENAMECLIENT~ cl
	where cl.id = ~CLIENTID~ and d.id = ~DEVICEID~ and cl.id = d.client_id
		";
		$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
		$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
		$strSQL = str_replace('~TABLENAMEDEVICELOG~', ff($strTableNameDeviceLog), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
		$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
		$strSQL = str_replace('~CODE~', ff($strDeviceCode), $strSQL);
		$strSQL = str_replace('~DESCRIPTION~', ff($strDeviceDescription), $strSQL);
		$strSQL = str_replace('~ISENABLED~', ff($strIsEnabled), $strSQL);
        $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		$strSQL = str_replace('~DEVICEID~', ff($strDeviceID_a), $strSQL);
		$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
		$strSQL = str_replace('~CLIENTCODE~', ff($strClientCode_a), $strSQL);
		$strSQL = str_replace('~LOGIN~', ff($strLogin_a), $strSQL);
		$strSQL = str_replace('~IPADDRESS~', ff($strIPAddress_a), $strSQL);
		$strSQL = str_replace('~USERAGENT~', ff($strUserAgent_a), $strSQL);
		$strSQL = str_replace('~NOTES~', ff($strNotes_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
	}

    return $blnResult;
}
