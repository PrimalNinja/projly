<?php
function actionSystemBuildRunTasks($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{

    $strResult = "";

    if (dependencies('developer/systemBuildRunTasks'))
    {
        // permission check
        if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

        $strSystemBuildID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);

        if (systemBuildRunTasks($objConn_a, $strSystemBuildID))
        {
            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, 'System build tasks run successfully.', array());
        }
        else
        {
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'There is an error building the system.', array());
        }
        
    }

    return $strResult;
}