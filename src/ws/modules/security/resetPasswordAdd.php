<?php

function resetPasswordAdd($objConn_a, $strClientID_a, $strEmailAddress_a, $strToken_a)
{
    $strTableNameResetPassword = getTableNameEntity("resetpassword", false);

    $strResetPasswordID = "";
    $strIPAddress = $_SERVER["REMOTE_ADDR"];

    dbBeginTrans($objConn_a, __FUNCTION__);

    $strLogin = $_SESSION['server_loggedin_user'];

    $arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "RESETPASSWORD");

    $strEntityID = getEntityID($objConn_a, "systemform");
    $strDataEntityID = getEntityID($objConn_a, "resetpassword");

    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g14b59919-5bef-40e8-8eb5-a25b870230aa", "IPADDRESS", $strIPAddress);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g14b59919-5bef-40e8-8eb5-a25b870230aa", "ACCOUNTEMAILADDRESS", $strEmailAddress_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g14b59919-5bef-40e8-8eb5-a25b870230aa", "ISCONFIRMED", "N");

    $strJSONData = json_encode($arrJSONData);

    $strSQL =
        "
insert into ~TABLENAMERESETPASSWORD~ (client_id, entity_id, code, description, is_enabled, dataentity_id, data_client_id, jsondata, modifyuser, modifydatetime, token, is_confirmed)
values (~CLIENTID~, ~ENTITYID~, '', '', 'Y', ~DATAENTITYID~, ~DATACLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~', '~TOKEN~', 'N')
";
    $strSQL = str_replace('~TABLENAMERESETPASSWORD~', ff($strTableNameResetPassword), $strSQL);
    $strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
    $strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
    $strSQL = str_replace('~DATACLIENTID~', ff($strClientID_a), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
    $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
    $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
    $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
    $strSQL = str_replace('~TOKEN~', ff($strToken_a), $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
    $strResetPasswordID = dbLastInsertID($objConn_a);

	exposeEntityData($objConn_a, 'SYSTEMFORM', 'RESETPASSWORD', $strResetPasswordID, $strJSONData);

    dbEndTrans($objConn_a, __FUNCTION__);

    return $strResetPasswordID;
}
