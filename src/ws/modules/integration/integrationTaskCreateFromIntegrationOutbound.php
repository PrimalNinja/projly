<?php 
function integrationTaskCreateFromIntegrationOutbound($objConn_a, $strIntegrationOutboundID_a)
{
    $strTableNameIntegrationOutbound = getTableNameEntity("integrationoutbound", false);
    $strTableNameIntegrationOutboundPlugin = getTableNameEntity("integrationoutboundplugin", false);
    $strTableNameIntegrationOutboundType = getTableNameEntity("integrationoutboundtype", false);
    $strTableNameServer = getTableNameEntity("server", false);

    // these are required fields
    $strLogin = $_SESSION['server_loggedin_user'];
    //$strClientID = $_SESSION['server_loggedin_clientid']; 
          
    if (dependencies('integration/createIntegrationTaskOutbound'))
    {
        dbBeginTrans($objConn_a, __FUNCTION__);

        $strSQL = "select id, client_id, g561d6970_cd14_4e7b_8ac4_460ae9f6a750_description description, jsondata from ~TABLENAMEINTEGRATIONOUTBOUND~ where id = ~INTEGRATIONOUTBOUNDID~";
        $strSQL = str_replace('~TABLENAMEINTEGRATIONOUTBOUND~', ff($strTableNameIntegrationOutbound), $strSQL);
        $strSQL = str_replace('~INTEGRATIONOUTBOUNDID~', ff($strIntegrationOutboundID_a), $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        if ($arrRow = dbReadRecord($objResult))
        {
            $strCode = 'TRANSMITSTEP2';
            $strDescription = 'Transmit Step 2';
            $strStatus = 'PENDING'; 
            $strIsProcessed = "N";

            $strIntegrationOutboundID = $arrRow['id'];
            $strClientID = $arrRow['client_id'];
            $strIntegrationOutboundDescription = $arrRow['description'];
            $arrIntegrationOutboundJSONData = json_decode($arrRow['jsondata'], true);

            $strOutboundGUID = formValueGetBySectionCodeFieldCode($arrIntegrationOutboundJSONData, 'g561d6970-cd14-4e7b-8ac4-460ae9f6a750', 'OUTBOUNDGUID');

            $arrField = formFieldGetBySectionCodeFieldCode($arrIntegrationOutboundJSONData, 'g561d6970-cd14-4e7b-8ac4-460ae9f6a750', 'PARAMETERS');
            $strIntegrationOutboundParameters = $arrField['p_value'];
            $strIntegrationOutboundParameters = $arrField['p_valuedescription'];

            $arrField = formFieldGetBySectionCodeFieldCode($arrIntegrationOutboundJSONData, 'g561d6970-cd14-4e7b-8ac4-460ae9f6a750', 'INTEGRATIONOUTBOUNDTYPE');
            $strIntegrationOutboundTypeID = $arrField['p_value'];
            $strIntegrationOutboundTypeDescription = $arrField['p_valuedescription'];

            $arrField = formFieldGetBySectionCodeFieldCode($arrIntegrationOutboundJSONData, 'g561d6970-cd14-4e7b-8ac4-460ae9f6a750', 'SERVER');
            $strServerID = $arrField['p_value'];
            $strServerDescription = $arrField['p_valuedescription'];

            createIntegrationTaskOutbound($objConn_a, $strClientID, $strCode, $strDescription, $strStatus, $strIsProcessed, $strOutboundGUID
            , $strIntegrationOutboundID, $strIntegrationOutboundDescription, $strIntegrationOutboundTypeID, $strIntegrationOutboundTypeDescription, $strServerID,
            $strServerDescription, $strIntegrationOutboundParameters);


            // update status of integrationoutbound
            $strSQL = "update ~TABLENAMEINTEGRATIONOUTBOUND~ set is_processed = 'Y' where id = ~INTEGRATIONOUTBOUNDID~";
            $strSQL = str_replace('~TABLENAMEINTEGRATIONOUTBOUND~', ff($strTableNameIntegrationOutbound), $strSQL);
            $strSQL = str_replace('~INTEGRATIONOUTBOUNDID~', ff($strIntegrationOutboundID), $strSQL);

            dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        }
        
        dbCloseRecordset($objResult);
    }
    return dbEndTrans($objConn_a, __FUNCTION__);
}