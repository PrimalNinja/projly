<?php
function actionEntityCreateDataAccessorsAll($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strTableNameEntity = getTableNameEntity("entity", false);
    $strTableNameSystemForm = getTableNameEntity("systemform", false);

    $strResult = "";
    $intCountBuildError = 0;
    $intCountBuild = 0;
    if (dependencies('core/createDataAccessor'))
    {
        // permission check
        if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

        $strClientID = $_SESSION['server_loggedin_clientid'];
        
        $strSQL = "select e.id entity_id, e.code entity_code, e.description, count(s.id) systemform_count from ~TABLENAMEENTITY~ e left join ~TABLENAMESYSTEMFORM~ s on e.id=s.dataentity_id group by e.code";
        $strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
        $strSQL = str_replace('~TABLENAMESYSTEMFORM~', ff($strTableNameSystemForm), $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        while ($arrRow = dbReadRecord($objResult))
        {
            $strEntityID = $arrRow['entity_id'];
            $strEntityCode = $arrRow['entity_code'];
            $strSystemFormCount = $arrRow['systemform_count'];

            logBuild("Creating data accessor for " . $strEntityCode);

            if (intval($strSystemFormCount) > 1)
            {
                // log build [ENTITYCODE] has $count systemform
                logBuild($strEntityCode . " has " . $strSystemFormCount . " systemforms (Published Forms)");
            }

            if (createDataAccessor($objConn_a, $strEntityID, $strEntityCode) === false)
            {
                $intCountBuildError++;
            }

            logBuild("Data accessor created for " . $strEntityCode);

            $intCountBuild++;
        }

        
        if ($intCountBuildError == 0)
        {
            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, $intCountBuild . " data accessors created.", array());
        }
        else
        {
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $intCountBuild . " data accessors created. " . $intCountBuildError . " created with errors.", array());
        }
    }

    return $strResult;
}