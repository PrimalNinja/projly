<?php

function buildSystem($objConn_a, $strClientID_a, $strSystemID_a, $strGUID_a, $strSystemDescription_a)
{    
    $strTableNameSystemBuild = getTableNameEntity("systembuild", false);
    $strTableNameSystemModule = getTableNameEntity("systemmodule", false);
    $strTableNameSystemSystemModule = getTableNameEntity("system_systemmodule", false);
    
    if (dependencies('developer/installModule'))
    {
        $blnResult = false;

        $strLogin = $_SESSION['server_loggedin_user'];
        $strClientID = $strClientID_a;

        $strGUID = $strGUID_a;

        $strCode = $strGUID;
        $strDescription = $strSystemDescription_a;
        $strDate = getDateTime();
        $strTime = date("H:m:s");
        $strStatus = "INPROGRESS";
        $strFolderName = cleanFilename($strGUID);

        $arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "SYSTEMBUILD");
        $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g3addd7a5-6125-499d-b87a-c827161eae5f', "CODE", $strCode);
        $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g3addd7a5-6125-499d-b87a-c827161eae5f', "DESCRIPTION", $strDescription);
        $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g3addd7a5-6125-499d-b87a-c827161eae5f', "DATE", $strDate);
        $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g3addd7a5-6125-499d-b87a-c827161eae5f', "TIME", $strTime);
        $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g3addd7a5-6125-499d-b87a-c827161eae5f', "STATUS", $strStatus);
        $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g3addd7a5-6125-499d-b87a-c827161eae5f', "FOLDER", $strFolderName);

        $strJSONData = json_encode($arrJSONData);
        $strEntityID = getEntityID($objConn_a, "systemform");
        $strDataEntityID = getEntityID($objConn_a, "systembuild");

        dbBeginTrans($objConn_a, __FUNCTION__);

        $strSQL = "insert into ~TABLNAMESYSTEMBUILD~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime) values(~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~')";
        $strSQL = str_replace('~TABLNAMESYSTEMBUILD~', ff($strTableNameSystemBuild), $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
        $strSQL = str_replace('~CODE~', $strCode, $strSQL);
        $strSQL = str_replace('~DESCRIPTION~', $strDescription, $strSQL);
        $strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
        $strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
        $strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
        $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
        $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
        $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        $strSystemBuildID = dbLastInsertID($objConn_a);
        
        exposeEntityData($objConn_a, 'SYSTEMFORM', 'SYSTEMBUILD', $strSystemBuildID, $strJSONData);
        
        // create the folder
        if (strlen($strSystemBuildID) > 0)
        {
            createFolder(BUILD_PATH . $strFolderName, false);

            $strSQL = "select systemmodule_id, sm.description from ~TABLENAMESYSTEMSYSTEMMODULE~ ssm, ~TABLENAMESYSTEMMODULE~ sm where ssm.systemmodule_id=sm.id and ssm.system_id = ~SYSTEMID~ order by cast(sm.gdb7a73ca_38b3_43bf_9d17_a599f23507e5_sortorder as unsigned) asc";
            $strSQL = str_replace('~TABLENAMESYSTEMSYSTEMMODULE~', ff($strTableNameSystemSystemModule), $strSQL);
            $strSQL = str_replace('~TABLENAMESYSTEMMODULE~', ff($strTableNameSystemModule), $strSQL);
            $strSQL = str_replace('~SYSTEMID~', ff($strSystemID_a), $strSQL);
            $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
            
            while ($arrRow = dbReadRecord($objResult))
            {
                installModule($objConn_a, $arrRow['systemmodule_id'], $strSystemBuildID);
            }

            dbCloseRecordset($objResult);
        }

        $blnResult = dbEndTrans($objConn_a, __FUNCTION__);
    }

    return $blnResult;
}