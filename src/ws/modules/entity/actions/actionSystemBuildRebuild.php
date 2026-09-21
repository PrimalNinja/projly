<?php
function actionSystemBuildRebuild($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strTableNameSystemBuild = getTableNameEntity("systembuild", false);

    $strResult = "";

    if (dependencies('developer/rebuildSystem'))
    {
        // permission check
        if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

        $strID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);

        $strClientID = $_SESSION['server_loggedin_clientid'];

        $strSQL = "select g3addd7a5_6125_499d_b87a_c827161eae5f_description from ~TABLENAMESYSTEMBUILD~ where id = ~SYSTEMBUILDID~";
        $strSQL = str_replace('~TABLENAMESYSTEMBUILD~', ff($strTableNameSystemBuild), $strSQL);
        $strSQL = str_replace('~SYSTEMBUILDID~', ff($strID), $strSQL);
        $strSystemDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        if (rebuildSystem($objConn_a, $strClientID, $strID, getGUID(), $strSystemDescription))
        {
            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, 'System successfully rebuilt.', array());
        }
        else
        {
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'There is an error processing the rebuild.', array());
        }
        
    }

    return $strResult;
}