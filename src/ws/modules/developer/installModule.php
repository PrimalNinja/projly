<?php

function installModule($objConn_a, $strSystemModuleID_a, $strSystemBuildID_a)
{   
    $strTableNameSystemModule = getTableNameEntity("systemmodule", false);

    if (dependencies('developer/installTasks,developer/systemBuildRunTasks'))
    {
        $blnResult = false;

        $strSQL = "select id, code, description, jsondata from ~TABLENAMESYSTEMMODULE~ where id = ~SYSTEMMODULEID~";
        $strSQL = str_replace('~TABLENAMESYSTEMMODULE~', ff($strTableNameSystemModule), $strSQL);
        $strSQL = str_replace('~SYSTEMMODULEID~', ff($strSystemModuleID_a), $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        
        if ($arrRow = dbReadRecord($objResult))
        {   
            // must run tasks first, so that folders and files must be created 
            installTasks($objConn_a, $arrRow['id'], $strSystemBuildID_a);
            //systemBuildRunTasks($objConn_a, $arrRow['id'], $strSystemBuildID_a);
            
        }

        dbCloseRecordset($objResult);
    }
    
    return $blnResult;
}