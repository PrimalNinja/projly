<?php

// install a profile
// note: if installing into the sysadmin area they come from the default area, otherwise they come from the sysadmin area
function actionSecurityProfileInstall($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strResult = "";

    if (dependencies('security/profileInstall')) {
        // permission check
        if (!hasPermission($objConn_a, 'INS_PROFILE', __FUNCTION__, true)) {return false;}

        // parameters
        $strProfileIDFrom = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);

        // initialisations
        $blnSystemArea = $_SESSION['server_loggedin_system'];
        $strClientIDTo = $_SESSION['server_loggedin_clientid'];
        $strClientIDFrom = "";
		
        if ($blnSystemArea) 
		{
            // install from the default area
            $strClientIDFrom = getDefaultClientID($objConn_a);
        } 
		else 
		{
            // install from the system area
            $strClientIDFrom = getSystemClientID($objConn_a);
        }

        dbBeginTrans($objConn_a, __FUNCTION__);
        $strProfileIDTo = profileInstall($objConn_a, $strClientIDFrom, $strClientIDTo, $strProfileIDFrom);
        if (dbEndTrans($objConn_a, __FUNCTION__)) 
		{
            $arrResult[] = array("id" => secureEntityValue('PROFILE', $strProfileIDTo));

            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
        } 
		else 
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error installing profile.', array());
        }
    }

    return $strResult;
}
