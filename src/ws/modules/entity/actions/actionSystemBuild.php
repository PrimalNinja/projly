<?php
function actionSystemBuild($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{   
    $strTableNameSystem = getTableNameEntity("system", false);
    
    $strResult = "";

    if (dependencies('developer/buildSystem'))
    {
        // permission check
        if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

        $strID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);

        $strClientID = $_SESSION['server_loggedin_clientid'];

        $strSQL = "select gd624043e_decc_4b2c_aea0_9b3009b0c034_description from ~TABLENAMESYSTEM~ where id = ~SYSTEMID~";
        $strSQL = str_replace('~TABLENAMESYSTEM~', ff($strTableNameSystem), $strSQL);
        $strSQL = str_replace('~SYSTEMID~', ff($strID), $strSQL);
        $strSystemDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        if (buildSystem($objConn_a, $strClientID, $strID, getGUID(), $strSystemDescription))
        {
            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, 'New system successfully built.', array());
        }
        else
        {
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'There is an error building the system.', array());
        }        
        
    }

    return $strResult;
}