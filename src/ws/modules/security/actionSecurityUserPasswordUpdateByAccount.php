<?php

// update password
function actionSecurityUserPasswordUpdateByAccount($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameAccount = getTableNameEntity("account", false);

    $strResult = "";

    if (dependencies('security/userPasswordUpdate')) {
        // permission check
        if (!hasPermission($objConn_a, 'CHPWD_USER', __FUNCTION__, true)) {return false;}

        // parameters
        //$arrUser = getJSONParameter($arrParameters_a, 'user');
        $blnSelfEdit = toBoolean(getJSONParameter($arrParameters_a, 'editself'));

        if ($blnSelfEdit) 
		{
            $strClientID = $_SESSION['server_loggedin_clientid'];
			$strAccountID = getAccountIDByClientIDViaAccount($objConn_a, $strClientID);
        } 
		else 
		{
            $strAccountID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);
            $strClientID = getClientIDByAccountIDViaAccount($objConn_a, $strAccountID);
        }

		$strSQL = "select user_id returnvalue from ~TABLENAMEACCOUNT~ where id = ~ACCOUNTID~";
		$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
		$strSQL = str_replace('~ACCOUNTID~', ff($strAccountID), $strSQL);
		$strUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        //$strPassword = strtolower($arrUser['password']);
		$strPassword = getJSONParameter($arrParameters_a, 'password');

        dbBeginTrans($objConn_a, __FUNCTION__);
        userPasswordUpdate($objConn_a, $strClientID, $strUserID, $strPassword);
        if (dbEndTrans($objConn_a, __FUNCTION__)) 
		{
            //$arrResult[] = array("id" => secureEntityValue('USER', $strUserID));

            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', array());
        } 
		else 
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error updating password.', array());
        }
    }

    return $strResult;
}
