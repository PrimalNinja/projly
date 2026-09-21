<?php
function actionEntityCreateDataAccessor($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{

    $strTableNameEntity = getTableNameEntity("entity", false);

    $strResult = "";

    if (dependencies('core/createDataAccessor'))
    {
        // permission check
        if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

        $strEntityID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);

        $strClientID = $_SESSION['server_loggedin_clientid'];
                
        $strSQL = "select code, description from ~TABLENAMEENTITY~ where id = ~ENTITYID~";
        $strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
        $strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        if ($arrRow = dbReadRecord($objResult))        
        {
            $strEntityCode = $arrRow['code'];

            logBuild("Creating data accessor for " . $strEntityCode);

            if (createDataAccessor($objConn_a, $strEntityID, $strEntityCode))
            {
                $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, "Data accessor created for " . $strEntityCode . ".", array());
            }
            else
            {
                $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'There is an error creating the data accessor.', array());
            }

            logBuild("Data accessor created for " . $strEntityCode);
        }
        else
        {
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Entity not found.', array());
        }
            
        dbCloseRecordset($objResult);
    }

    return $strResult;
}