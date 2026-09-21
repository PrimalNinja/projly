<?php

// add a user
function userAdd($objConn_a, $strClientID_a, $strUserStatusID_a, $strCode_a, $strDescription_a, $strEmailAddress_a, $strLogin_a, $strPassword_a, $strEnabled_a, $strDefined_a, $blnCreateSettings_a)
{
	$strTableNameClientUser = getTableNameEntity("client_user", false);
	$strTableNameUser = getTableNameEntity("user", false);

    $strUserID = "";

    if (dependencies('security/userChecksumUpdate')) {
        dbBeginTrans($objConn_a, __FUNCTION__);

        $strLogin = $_SESSION['server_loggedin_user'];
		
		$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "USER");
		
		// get the entity ids
		$strEntityID = getEntityID($objConn_a, "systemform");
		$strDataEntityID = getEntityID($objConn_a, "user");

		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "LOGIN", $strLogin_a);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "ISENABLED", $strEnabled_a);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "DESCRIPTION", $strDescription_a);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "EMAILADDRESS", $strEmailAddress_a);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "CODE", $strCode_a);
		$strJSONData = json_encode($arrJSONData);
		
        $strSQL =
            "
	insert into ~TABLENAMEUSER~ (client_id, entity_id, dataentity_id, code, description, is_enabled, jsondata, data_client_id, modifyuser, modifydatetime, userstatus_id, email_address, login, password, is_defined, checksum)
	values (~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ENABLED~', '~JSONDATA~', ~DATACLIENTID~, '~MODIFYUSER~', '~MODIFYDATETIME~', ~USERSTATUSID~, '~EMAILADDRESS~', '~LOGIN~', '~PASSWORD~', '~DEFINED~', '')
	";
		$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
		$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
        $strSQL = str_replace('~CODE~', ff($strCode_a), $strSQL);
        $strSQL = str_replace('~DESCRIPTION~', ff($strDescription_a), $strSQL);
        $strSQL = str_replace('~ENABLED~', ff($strEnabled_a), $strSQL);
		$strSQL = str_replace('~DATACLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
        $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
        $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
        $strSQL = str_replace('~USERSTATUSID~', ffn($strUserStatusID_a), $strSQL);
        $strSQL = str_replace('~EMAILADDRESS~', ff($strEmailAddress_a), $strSQL);
        $strSQL = str_replace('~LOGIN~', ff($strLogin_a), $strSQL);
        $strSQL = str_replace('~PASSWORD~', ff(encryptPassword1Way($strClientID_a, $strLogin_a, strtolower($strPassword_a))), $strSQL);	// temporarily put the login here but really we want the userid
        $strSQL = str_replace('~DEFINED~', ff($strDefined_a), $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        $strUserID = dbLastInsertID($objConn_a);

		$strSQL = "update ~TABLENAMEUSER~ set password = '~PASSWORD~' where id = ~USERID~";
		$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
        $strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
        $strSQL = str_replace('~PASSWORD~', ff(encryptPassword1Way($strClientID_a, $strUserID, strtolower($strPassword_a))), $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
		exposeEntityData($objConn_a, 'SYSTEMFORM', 'USER', $strUserID, $strJSONData);

        userChecksumUpdate($objConn_a, $strClientID_a, $strUserID);

        dbEndTrans($objConn_a, __FUNCTION__);
    }

    return $strUserID;
}
