<?php
function actionPublicPasswordReset($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strTableNameAccount = getTableNameEntity("account", false);

	$blnResult = false;
	$strDescription = "";
    $strResult = "";

    if (dependencies('security/resetPassword'))
    {
        // permission check
        if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}

        // parameters
        $strLogin = getJSONParameter($arrParameters_a, 'login');
        $strClientCode = getJSONParameter($arrParameters_a, 'clientcode');

        $strSQL = "select id returnvalue from ~TABLENAMEACCOUNT~ where ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_accountemailaddress = '~EMAILADDRESS~' and ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_clientcode = '~CLIENTCODE~'";
        $strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
        $strSQL = str_replace('~EMAILADDRESS~', ff($strLogin), $strSQL);
        $strSQL = str_replace('~CLIENTCODE~', ff($strClientCode), $strSQL);
        $strAccountID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

		if (strlen($strAccountID) > 0)
		{
			// initialisations
			$strClientID = $_SESSION['server_loggedin_clientid'];

			dbBeginTrans($objConn_a, __FUNCTION__);
			$strResult = resetPassword($objConn_a, $strAccountID);
			$blnResult = dbEndTrans($objConn_a, __FUNCTION__);

            $strDescription = dbErrorDescription(true);
		}
		else
		{
			$strDescription = "Invalid account code or email address.";
		}

        if ($blnResult)
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, 'You have initiated a password reset.<br><br>An email has been sent to you to reset your password.', array());
        }
        else
        {
            if (strlen($strDescription) == 0)
            {
                $strDescription = "Error resetting password.";
            }

            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strDescription, array());
        }
    }

    return $strResult;
}
