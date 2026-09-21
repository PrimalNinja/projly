<?php
function clientSettingAdd($objConn_a, $strClientID_a)
{
    $strTableNameClientSetting = getTableNameEntity("clientsetting", false);

    $strClientSettingID = "";

    $strSQL = "select id returnvalue from ~TABLENAMECLIENTSETTING~ where client_id = ~CLIENTID~";
    $strSQL = str_replace('~TABLENAMECLIENTSETTING~', ff($strTableNameClientSetting), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
    $strClientSettingID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    if (strlen($strClientSettingID) === 0)
    {
        dbBeginTrans($objConn_a, __FUNCTION__);

        $strLogin = $_SESSION['server_loggedin_user'];
        
        $strCode = 'CLIENTSETTING';
        $strDescription = 'Client Setting';
        $strEnabled = 'Y';

        $arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "CLIENTSETTING");
        
        // get the entity ids
        $strEntityID = getEntityID($objConn_a, "systemform");
        $strDataEntityID = getEntityID($objConn_a, "clientsetting");

        // $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g77083296-9316-4fc8-ae5e-57ce6d21cd57", "CODE", $strCode);
        // $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g77083296-9316-4fc8-ae5e-57ce6d21cd57", "DESCRIPTION", $strDescription);
        // $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g77083296-9316-4fc8-ae5e-57ce6d21cd57", "ISENABLED", $strEnabled);
        $strJSONData = json_encode($arrJSONData);
        
        $strSQL =
            "
        insert into ~TABLENAMECLIENTSETTING~ (id, client_id, entity_id, dataentity_id, code, description, is_enabled, jsondata, data_client_id, modifyuser, modifydatetime)
        values (~CLIENTID~, ~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ENABLED~', '~JSONDATA~', ~DATACLIENTID~, '~MODIFYUSER~', '~MODIFYDATETIME~')
        ";
        $strSQL = str_replace('~TABLENAMECLIENTSETTING~', ff($strTableNameClientSetting), $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
        $strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
        $strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
        $strSQL = str_replace('~CODE~', ff($strCode), $strSQL);
        $strSQL = str_replace('~DESCRIPTION~', ff($strDescription), $strSQL);
        $strSQL = str_replace('~ENABLED~', ff($strEnabled), $strSQL);
        $strSQL = str_replace('~DATACLIENTID~', ff($strClientID_a), $strSQL);
        $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
        $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
        $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        $strClientSettingID = dbLastInsertID($objConn_a);
        
        exposeEntityData($objConn_a, 'SYSTEMFORM', 'CLIENTSETTING', $strClientSettingID, $strJSONData);

        dbEndTrans($objConn_a, __FUNCTION__);
    }

    return $strClientSettingID;
}
