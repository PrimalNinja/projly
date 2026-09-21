<?php

// update password
function actionSecurityUserPasswordUpdate($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strResult = "";

    if (dependencies('security/userPasswordUpdate')) {
        // permission check
        if (!hasPermission($objConn_a, 'CHPWD_USER', __FUNCTION__, true)) {return false;}

        // parameters
        //$arrUser = getJSONParameter($arrParameters_a, 'user');
        $blnSelfEdit = toBoolean(getJSONParameter($arrParameters_a, 'editself'));

        if ($blnSelfEdit) 
		{
            $strUserID = $_SESSION['server_loggedin_userid'];
        } 
		else 
		{
            $strUserID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);
        }

        //$strPassword = strtolower($arrUser['password']);
		$strPassword = getJSONParameter($arrParameters_a, 'password');

        // initialisations
        $strClientID = $_SESSION['server_loggedin_clientid'];

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
