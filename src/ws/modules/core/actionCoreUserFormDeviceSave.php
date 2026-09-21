<?php

function actionCoreUserFormDeviceSave($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strTableNameDevice = getTableNameEntity("device", false);
    $strTableNameUserFormDevice = getTableNameEntity("userform_device", false);
    $strTableNameSystemForm = getTableNameEntity("systemform", false);

    $arrResult = [];
    $blnResult = false;

    // permission check
    if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}

    // parameters
    $strDeviceID = getJSONParameter($arrParameters_a, 'deviceid');
    $strFormEntityCode = getJSONParameter($arrParameters_a, 'formentity');

    // initialisations
    $strClientID = $_SESSION['server_loggedin_clientid'];
    $strUserID = $_SESSION['server_loggedin_userid'];
    $strLogin = $_SESSION['server_loggedin_user'];
    
    dbBeginTrans($objConn_a, __FUNCTION__);

    $strSQL = "select id returnvalue from ~TABLENAMESYSTEMFORM~ where code = '~ENTITYCODE~'";
    $strSQL = str_replace('~TABLENAMESYSTEMFORM~', ff($strTableNameSystemForm), $strSQL);
    $strSQL = str_replace('~ENTITYCODE~', ffeu($strFormEntityCode), $strSQL);
    $strSystemFormID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    // clear saved devices 
    $strSQL = "delete from ~TABLENAMEUSERFORMDEVICE~ where client_id = ~CLIENTID~ and user_id = ~USERID~ and systemform_id = ~SYSTEMFORMID~";
    $strSQL = str_replace('~TABLENAMEUSERFORMDEVICE~', ff($strTableNameUserFormDevice), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
    $strSQL = str_replace('~USERID~', $strUserID, $strSQL);
    $strSQL = str_replace('~SYSTEMFORMID~', $strSystemFormID, $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

    if ($strDeviceID !== 'NONE')
    {
        $strDeviceID = revertSecuredValue($strDeviceID, 'id', true);
        $strDeviceDescription = dbGetDescriptionFromID($objConn_a, $strTableNameDevice, $strDeviceID, __FUNCTION__);
                
        $strSQL = "select description returnvalue from ~TABLENAMESYSTEMFORM~ where code = '~ENTITYCODE~'";
        $strSQL = str_replace('~TABLENAMESYSTEMFORM~', ff($strTableNameSystemForm), $strSQL);
        $strSQL = str_replace('~ENTITYCODE~', ffeu($strFormEntityCode), $strSQL);
        $strSystemFormDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        $strCode = getGUID();
        $strDescription = $strSystemFormDescription;

        $arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "USERFORM_DEVICE");        
        $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "gb044f0b5-48c7-48d6-8d6c-b1f8af44a652", "CODE", $strCode);
        $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "gb044f0b5-48c7-48d6-8d6c-b1f8af44a652", "DESCRIPTION", $strDescription);
        $arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'gb044f0b5-48c7-48d6-8d6c-b1f8af44a652', "SYSTEMFORM", $strSystemFormID, $strSystemFormDescription);		
        $arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'gb044f0b5-48c7-48d6-8d6c-b1f8af44a652', "DEVICE", $strDeviceID, $strDeviceDescription);

        $strJSONData = json_encode($arrJSONData);
        
        $strSQL = "insert into ~TABLENAMEUSERFORMDEVICE~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, user_id) values(~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~', ~USERID~)";
        $strSQL = str_replace('~TABLENAMEUSERFORMDEVICE~', ff($strTableNameUserFormDevice), $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
        $strSQL = str_replace('~CODE~', $strCode, $strSQL);
        $strSQL = str_replace('~DESCRIPTION~', $strDescription, $strSQL);
        $strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
        $strSQL = str_replace('~ENTITYID~', ff(getEntityID($objConn_a, "systemform")), $strSQL);
        $strSQL = str_replace('~DATAENTITYID~', ff(getEntityID($objConn_a, "userform_device")), $strSQL);
        $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
        $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
        $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
        $strSQL = str_replace('~USERID~', $strUserID, $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        $strUserFormDeviceID = dbLastInsertID($objConn_a);
        
        exposeEntityData($objConn_a, 'SYSTEMFORM', 'USERFORM_DEVICE', $strUserFormDeviceID, $strJSONData);
    }

    $blnResult = dbEndTrans($objConn_a, __FUNCTION__);

    return createJSONResponse($strDataID_a, RESPONSE_OK, "", $arrResult);
}