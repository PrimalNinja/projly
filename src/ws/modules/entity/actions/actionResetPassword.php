<?php
 
function actionResetPassword($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strResult = "";

    if (dependencies('security/resetPassword'))
    {
        // permission check
        if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

        $strAccountID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);

        // initialisations
 
        dbBeginTrans($objConn_a, __FUNCTION__);
        $strResult = resetPassword($objConn_a, $strAccountID);

        if (dbEndTrans($objConn_a, __FUNCTION__)) {

            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, 'You have initiated a password reset.<br><br>Please check your email for instructions to complete the password reset.', array());
        }
        else
        {
            $strDescription = dbErrorDescription(true);
            if (strlen($strDescription) == 0)
            {
                $strDescription = "Error resetting password.";
            }
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strDescription, array());
        }
    }

    return $strResult;
}
