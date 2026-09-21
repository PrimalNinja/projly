<?php
function actionIntegrationTaskCreate($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strTableNameIntegrationOutbound = getTableNameEntity("integrationoutbound", false);
    $strTableNameIntegrationOutboundPlugin = getTableNameEntity("integrationoutboundplugin", false);

    $strResult = "";
	$arrResult = [];
	
	// permission check
    if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}

    $strOutboundCode = getJSONParameter($arrParameters_a, 'outbound');
    
    if (strlen($strOutboundCode) > 0)
    {
        $strSQL = "select id, integrationoutboundplugin_id from ~TABLENAMEINTEGRATIONOUTBOUND~ where code = '~INTEGRATIONOUTBOUNDCODE~'";
        $strSQL = str_replace('~TABLENAMEINTEGRATIONOUTBOUND~', ff($strTableNameIntegrationOutbound), $strSQL);
        $strSQL = str_replace('~INTEGRATIONOUTBOUNDCODE~', ff($strOutboundCode), $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        
        if ($arrRow = dbReadRecord($objResult))
        {   
            $strIntegrationOutboundID = $arrRow['id'];
            $strIntegrationOutboundPluginID = $arrRow['integrationoutboundplugin_id'];

            $strSQL = "select code returnvalue from ~TABLENAMEINTEGRATIONOUTBOUNDPLUGIN~ where id = ~INTEGRATIONOUTBOUNDPLUGINID~";
            $strSQL = str_replace('~TABLENAMEINTEGRATIONOUTBOUNDPLUGIN~', ff($strTableNameIntegrationOutboundPlugin), $strSQL);
            $strSQL = str_replace('~INTEGRATIONOUTBOUNDPLUGINID~', ff($strIntegrationOutboundPluginID), $strSQL);    
            $strIntegrationOutboundPlugin = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

            if (dependencies('integration/outbound/' . strtolower($strIntegrationOutboundPlugin)))
            {                   
                $strMessage = initialiseTransmission($objConn_a, $strSecurityToken_a, $strDataID_a, $strIntegrationOutboundID);                    
            }                            
        }
        
        dbCloseRecordset($objResult);
    }
        
    $strResult  = createJSONResponse($strDataID_a, RESPONSE_OK, $strMessage, $arrResult);
	
	return $strResult;
}