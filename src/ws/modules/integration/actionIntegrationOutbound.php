<?php

function actionIntegrationOutbound($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameIntegrationOutbound = getTableNameEntity("integrationoutbound", false);
	$strTableNameIntegrationOutboundPlugin = getTableNameEntity("integrationoutboundplugin", false);
	$strTableNameIntegrationOutboundType = getTableNameEntity("integrationoutboundtype", false);
	$strTableNameServer = getTableNameEntity("server", false);
	$strTableNameServerProtocol = getTableNameEntity("serverprotocol", false);
	
	$strResult = "";
	$arrResult = [];
	
	// permission check
    if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}
	
	$strIntegrationOutboundID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);
	
	$strMessage = "";
	$strIntegrationOutboundCode = "";
	$strIntegrationOutboundTypeID = "";
	$strServerID = "";
	
	$strSQL = "select g561d6970_cd14_4e7b_8ac4_460ae9f6a750_outboundguid guid, g561d6970_cd14_4e7b_8ac4_460ae9f6a750_parameters parameters, integrationoutboundtype_id, server_id from ~TABLENAMEINTEGRATIONOUTBOUND~ where id = ~INTEGRATIONOUTBOUNDID~";
	$strSQL = str_replace('~TABLENAMEINTEGRATIONOUTBOUND~', ff($strTableNameIntegrationOutbound), $strSQL);
	$strSQL = str_replace('~INTEGRATIONOUTBOUNDID~', ff($strIntegrationOutboundID), $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	
	if ($arrRow = dbReadRecord($objResult))
	{			
		$strGUID = $arrRow['guid'];
		$strIntegrationOutboundTypeID = $arrRow['integrationoutboundtype_id'];
		$strIntegrationOutboundParameters = $arrRow['parameters'];
		$strServerID = $arrRow['server_id'];		
	}
	
	dbCloseRecordset($objResult);
			
	$strSQL = "select s.jsondata, p.ge9e3eb2e_0386_4df7_8d6d_5eb36073260e_code serverprotocol_code from ~TABLENAMESERVER~ s, ~TABLENAMESERVERPROTOCOL~ p where s.serverprotocol_id = p.id and s.id = ~SERVERID~";
	$strSQL = str_replace('~TABLENAMESERVER~', ff($strTableNameServer), $strSQL);
	$strSQL = str_replace('~TABLENAMESERVERPROTOCOL~', ff($strTableNameServerProtocol), $strSQL);
	$strSQL = str_replace('~SERVERID~', ff($strServerID), $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	
	if ($arrRow = dbReadRecord($objResult))
	{
		$arrJSONData = json_decode($arrRow['jsondata'], true);
		
		$strServerProtocol = $arrRow['serverprotocol_code'];
		$strServerPort = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'CONNECTIONPORT');
		$strServerURL = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'CONNECTIONURL');
		$strServerPath = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'CONNECTIONPATH');
		$strServerLogin = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'CONNECTIONLOGIN');
		$strServerPassword = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'CONNECTIONPASSWORD');
		
		
		// fetch proxy details and use it if enabled
		$strUseProxy = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'USEPROXY');
		$strProxyPort = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'PROXYPORT');
		$strProxyURL = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'PROXYURL');
		$strProxyLogin = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'PROXYLOGIN');
		$strProxyPassword = formValueGetBySectionCodeFieldCode($arrJSONData, 'gafb65c33-7df8-4b01-a919-4df125c08f0e', 'PROXYPASSWORD');
										
		if (dependencies('integration/outbound/' . strtolower($strIntegrationOutboundPlugin)))
		{
			$arrPostData = [
				[
					'name' => 'guid',
					'value' => $strGUID
				],
				[
					'name' => 'inboundtype',
					'value' => $strIntegrationOutboundType
				]
			];
			
			$arrResponse = $strIntegrationOutboundPlugin($objConn_a, $strServerPort, $strServerURL, $strServerLogin, $strServerPassword, $strServerPath, $strSecurityToken_a, $strDataID_a, $arrPostData);
			
			$strMessage = json_encode($arrResponse);
		}
		
		
	}
	
	dbCloseRecordset($objResult);
	
	$strResult  = createJSONResponse($strDataID_a, RESPONSE_OK, $strMessage, $arrResult);
	
	return $strResult;
}