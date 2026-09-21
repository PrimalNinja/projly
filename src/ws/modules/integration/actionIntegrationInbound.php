<?php
function actionIntegrationInbound($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameIntegrationInbound = getTableNameEntity("integrationinbound", false);
	$strTableNameIntegrationInboundPlugin = getTableNameEntity("integrationinboundplugin", false);
	$strTableNameIntegrationInboundType = getTableNameEntity("integrationinboundtype", false);
	$strTableNameServer = getTableNameEntity("server", false);
	$strTableNameServerProtocol = getTableNameEntity("serverprotocol", false);
	
	$arrResult = [];
	$blnError = false;
	$strResult = "";	
	$strResponseMessage = "";
	
	// permission check
    if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}
    
	if (dependencies('utils/log'))
	{
        $strGUID = getJSONParameter($arrParameters_a, 'guid');        
        $strInboundType = getJSONParameter($arrParameters_a, 'inboundtype');
        $strIntegrationTaskData = getJSONParameter($arrParameters_a, 'integrationtaskdata');
        $strEntityDataID = getJSONParameter($arrParameters_a, 'entitydataid');
		$strInboundData = getJSONParameter($arrParameters_a, 'inbounddata');
		$strClientID = getJSONParameter($arrParameters_a, 'client_id');


		$strIntegrationInboundPlugin = "";
		
		$strSQL = "select ip.gcecf2cdd_fc49_47df_b9f9_02c951a4d41b_code returnvalue 
				   from ~TABLENAMEINTEGRATIONINBOUND~ i, ~TABLENAMEINTEGRATIONINBOUNDPLUGIN~ ip, 
				   ~TABLENAMEINTEGRATIONINBOUNDTYPE~ it 
				   where it.integrationinboundplugin_id=ip.id and i.integrationinboundtype_id=it.id 
				   and it.g6e6314de_2b08_4dac_90fc_9ecdab41fefc_code = '~INBOUNDTYPECODE~' and g8fbd6661_d8e6_4279_b9a5_5e1042970937_inboundguid = '~GUID~'";

		$strSQL = str_replace('~TABLENAMEINTEGRATIONINBOUND~', ff($strTableNameIntegrationInbound), $strSQL);
		$strSQL = str_replace('~TABLENAMEINTEGRATIONINBOUNDPLUGIN~', ff($strTableNameIntegrationInboundPlugin), $strSQL);
		$strSQL = str_replace('~TABLENAMEINTEGRATIONINBOUNDTYPE~', ff($strTableNameIntegrationInboundType), $strSQL);
		$strSQL = str_replace('~GUID~', ff($strGUID), $strSQL);
		$strSQL = str_replace('~INBOUNDTYPECODE~', ff($strInboundType), $strSQL);
		$strIntegrationInboundPlugin = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

		if (strlen($strIntegrationInboundPlugin) > 0)
		{
			if (dependencies('integration/inbound/' . strtolower($strIntegrationInboundPlugin), true))
			{			
                receiveData($objConn_a, $strIntegrationInboundPlugin, $arrParameters_a);                	                
			}
			else
			{
				$blnError = true;
				
				// log error in database		
				createAuditDataLog($objConn_a, "ERROR", "Could not find Integration Inbound Plugin '" . $strIntegrationInboundPlugin . "'", '', 'INTEGRATIONINBOUND', '', '');
			}
		}
		else
		{
			$blnError = true;
			
			// log error in database		
			createAuditDataLog($objConn_a, "ERROR", "Missing Inbound Plugin", 0, 'INTEGRATIONINBOUND', '', '');
		}
		
		if (!$blnError)
		{						
			$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, "", $arrResult);
		}
		else
		{
			$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, "Error processing your request.", $arrResult);
		}
	
	}
	
	return $strResult;
}