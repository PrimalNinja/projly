<?php
function actionIntegrationOutboundCreateTask($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strTableNameAuthenticationType = getTableNameEntity("authenticationtype", false);
    $strTableNameIntegrationOutbound = getTableNameEntity("integrationoutbound", false);
	$strTableNameIntegrationOutboundPlugin = getTableNameEntity("integrationoutboundplugin", false);
	
	$strResult = "";
    $strResultError = "";
	$arrResult = [];
	
	// permission check
    if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

    $strIntegrationOutboundID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);
                        
    if (strlen($strIntegrationOutboundID) > 0)
    {
        $strSQL = "select authenticationtype_id, integrationoutboundtype_id from ~TABLENAMEINTEGRATIONOUTBOUND~ where id = '~INTEGRATIONOUTBOUNDID~'";
        $strSQL = str_replace('~TABLENAMEINTEGRATIONOUTBOUND~', ff($strTableNameIntegrationOutbound), $strSQL);
        $strSQL = str_replace('~INTEGRATIONOUTBOUNDID~', ff($strIntegrationOutboundID), $strSQL);

        $strAuthenticationTypeID = "";
        $strIntegrationOutboundTypeID = "";

        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        if ($arrRow = dbReadRecord($objResult))
        {
           $strIntegrationOutboundTypeID = $arrRow['integrationoutboundtype_id'];
           $strAuthenticationTypeID = $arrRow['authenticationtype_id'];
        }


        if (strlen($strAuthenticationTypeID) > 0) 
		{
            $strSQL = "select code returnvalue from ~TABLENAMEAUTHENTICATIONTYPE~ where id = ~AUTHENTICATIONTYPEID~";
            $strSQL = str_replace('~TABLENAMEAUTHENTICATIONTYPE~', ff($strTableNameAuthenticationType), $strSQL);
            $strSQL = str_replace('~AUTHENTICATIONTYPEID~', ff($strAuthenticationTypeID), $strSQL);

            $authenticationTypeCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
    
			$strPluginSuffix = preg_replace('/[^A-Za-z0-9\-\_]/', '', $authenticationTypeCode);
			$strPluginSuffix = strtoupper($strPluginSuffix);
			$strPluginName = 'pluginAuth_' . $strPluginSuffix;
			if (dependencies('plugins/authenticationplugins/' . $strPluginName)) 
			{
				$strResultError = call_user_func($strPluginName, $objConn_a);
			}
        }
        else {
            $strResultError = "Authentication type not found.";
        }

        if (strlen($strResultError) == 0)
        {
			$strIntegrationOutboundPlugin = "";
         
            $strTableNameIntegrationOutboundType = getTableNameEntity("integrationoutboundtype", false);
            $strTableNameIntegrationOutboundPlugin = getTableNameEntity("integrationoutboundplugin", false);
         
            $strSQL = "select id, gdaa1313b_b447_4e21_9dd6_7984dd3b554c_code code, gdaa1313b_b447_4e21_9dd6_7984dd3b554c_description description 
                       from ~TABLENAMEINTEGRATIONOUTBOUNDPLUGIN~ where id in  
                       ( select integrationoutboundplugin_id id from ~TABLEINTEGRATIONOUTBOUNDTYPE~ where id = ~INTEGRATIONOUTBOUNDTYPEID~ )";
         
            $strSQL = str_replace('~TABLENAMEINTEGRATIONOUTBOUNDPLUGIN~', ff($strTableNameIntegrationOutboundPlugin), $strSQL);
            $strSQL = str_replace('~TABLEINTEGRATIONOUTBOUNDTYPE~', ff($strTableNameIntegrationOutboundType), $strSQL);
            $strSQL = str_replace('~INTEGRATIONOUTBOUNDTYPEID~', ff($strIntegrationOutboundTypeID), $strSQL);
         
            
            $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
            if ($arrRow = dbReadRecord($objResult))
            {
               $strIntegrationOutboundPlugin = $arrRow['code'];
            }

            if (dependencies('integration/outbound/' . strtolower($strIntegrationOutboundPlugin)))
            {            
                $strMessage = initialiseTransmission($objConn_a, $strSecurityToken_a, $strDataID_a, $strIntegrationOutboundID);            
            } 
        }       
    }        
    
    
    if (strlen($strResultError) == 0) 
    {
	    $strResult  = createJSONResponse($strDataID_a, RESPONSE_OK, $strMessage, $arrResult);
    }
    else 
    {
	    $strResult  = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strResultError, $arrResult);
    }
	
	return $strResult;
}