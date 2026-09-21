<?php
function userSettingAdd($objConn_a, $strClientID_a, $strUserID_a)
{
    $strTableNameUserSetting = getTableNameEntity("usersetting", false);

    $strUserSettingID = "";

    $strSQL = "select id returnvalue from ~TABLENAMEUSERSETTING~ where client_id = ~CLIENTID~ and user_id = ~USERID~";
    $strSQL = str_replace('~TABLENAMEUSERSETTING~', ff($strTableNameUserSetting), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
    $strUserSettingID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    if (strlen($strUserSettingID) === 0)
    {
        dbBeginTrans($objConn_a, __FUNCTION__);

        $strLogin = $_SESSION['server_loggedin_user'];
        
        $strCode = 'USERSETTING';
        $strDescription = 'User Setting';
        $strEnabled = 'Y';

        $arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "USERSETTING");
        
        // get the entity ids
        $strEntityID = getEntityID($objConn_a, "systemform");
        $strDataEntityID = getEntityID($objConn_a, "usersetting");

        // $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g77083296-9316-4fc8-ae5e-57ce6d21cd57", "CODE", $strCode);
        // $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g77083296-9316-4fc8-ae5e-57ce6d21cd57", "DESCRIPTION", $strDescription);
        // $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g77083296-9316-4fc8-ae5e-57ce6d21cd57", "ISENABLED", $strEnabled);
        $strJSONData = json_encode($arrJSONData);
        
        $strSQL =
            "
        insert into ~TABLENAMEUSERSETTING~ (id, client_id, entity_id, dataentity_id, code, description, is_enabled, jsondata, data_client_id, modifyuser, modifydatetime, user_id)
        values (~USERID~, ~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ENABLED~', '~JSONDATA~', ~DATACLIENTID~, '~MODIFYUSER~', '~MODIFYDATETIME~', ~USERID~)
        ";
        $strSQL = str_replace('~TABLENAMEUSERSETTING~', ff($strTableNameUserSetting), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
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
        $strUserSettingID = dbLastInsertID($objConn_a);
        
        exposeEntityData($objConn_a, 'SYSTEMFORM', 'USERSETTING', $strUserSettingID, $strJSONData);

        dbEndTrans($objConn_a, __FUNCTION__);
    }

    return $strUserSettingID;
}
