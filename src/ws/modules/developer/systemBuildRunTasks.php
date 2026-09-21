<?php

function systemBuildRunTasks($objConn_a, $strSystemModuleID_a, $strSystemBuildID_a)
{
    //global $g_arrDefaultDictionary;
    global $g_arrDictionary;

    $strTableNameSystemBuild = getTableNameEntity("systembuild", false);
    $strTableNameSystemBuildTask = getTableNameEntity("systembuildtask", false);

    if (dependencies('developer/runTask,developer/tasks'))
    {
        $blnResult = true;

        $strSQL = "select g3addd7a5_6125_499d_b87a_c827161eae5f_folder returnvalue from ~TABLENAMESYSTEMBUILD~ where id = ~SYSTEMBUILDID~";
        $strSQL = str_replace('~TABLENAMESYSTEMBUILD~', ff($strTableNameSystemBuild), $strSQL);
        $strSQL = str_replace("~SYSTEMBUILDID~", ff($strSystemBuildID_a), $strSQL);
        $strBuildFolder = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        $strDictionaryFile = BUILD_PATH . "dictionary.json";
        $strPathBuild = BUILD_PATH . $strBuildFolder;
        
        if (file_exists($strDictionaryFile))
        {
            $strDictionary = loadFile($strDictionaryFile);
            if (strlen($strDictionary) > 0)
            {
                $g_arrDictionary = json_decode($strDictionary, true);
            }
        }

        $strPathSystemConfig = APP_PATH . 'config'; // SYS_PATH_CONFIG
        
        addDictionary('%SYS_DBCLIENTMAIN_DATABASENAME%', DBCLIENTMAIN_DATABASENAME);
        addDictionary('%SYS_DBCLIENTMAIN_HOSTNAME%', DBCLIENTMAIN_HOSTNAME);
        addDictionary('%SYS_DBCLIENTMAIN_LOGIN%', DBCLIENTMAIN_LOGIN);
        addDictionary('%SYS_DBCLIENTMAIN_PASSWORD%', DBCLIENTMAIN_PASSWORD);

        addDictionary('%SYS_DBCLIENTTEMP_DATABASENAME%', DBCLIENTTEMP_DATABASENAME);
        addDictionary('%SYS_DBCLIENTTEMP_HOSTNAME%', DBCLIENTTEMP_HOSTNAME);
        addDictionary('%SYS_DBCLIENTTEMP_LOGIN%', DBCLIENTTEMP_LOGIN);
        addDictionary('%SYS_DBCLIENTTEMP_PASSWORD%', DBCLIENTTEMP_PASSWORD);

        addDictionary('%SYS_DBSYSTEMMAIN_DATABASENAME%', DBSYSTEMMAIN_DATABASENAME);
        addDictionary('%SYS_DBSYSTEMMAIN_HOSTNAME%', DBSYSTEMMAIN_HOSTNAME);
        addDictionary('%SYS_DBSYSTEMMAIN_LOGIN%', DBSYSTEMMAIN_LOGIN);
        addDictionary('%SYS_DBSYSTEMMAIN_PASSWORD%', DBSYSTEMMAIN_PASSWORD);

        addDictionary('%SYS_DBSYSTEMTEMP_DATABASENAME%', DBSYSTEMTEMP_DATABASENAME);
        addDictionary('%SYS_DBSYSTEMTEMP_HOSTNAME%', DBSYSTEMTEMP_HOSTNAME);
        addDictionary('%SYS_DBSYSTEMTEMP_LOGIN%', DBSYSTEMTEMP_LOGIN);
        addDictionary('%SYS_DBSYSTEMTEMP_PASSWORD%', DBSYSTEMTEMP_PASSWORD);

        addDictionary('%SYS_YYYYMMDDHHNNSS%', getFileDateTime());
        //addDefaultDictionary('%SYS_FILESELECTED%', $strUpdate, $g_arrDefaultDictionary);
        addDictionary('%SYS_TODAY%', getToday());
        addDictionary('%SYS_PATH_CONFIG%', $strPathSystemConfig);
                
        addDictionary('%PATH_APP%', APP_PATH);
        addDictionary('%PATH_BUILD%', $strPathBuild);
        
        $strSQL = "select id, jsondata from ~TABLENAMESYSTEMBUILDTASK~ where systembuild_id = ~SYSTEMBUILDID~ order by sortorder";
        $strSQL = str_replace('~TABLENAMESYSTEMBUILDTASK~', ff($strTableNameSystemBuildTask), $strSQL);
        $strSQL = str_replace("~SYSTEMBUILDID~", ff($strSystemBuildID_a), $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
                
        while ($arrRow = dbReadRecord($objResult))
        {   
            $strStartTime = time();

            $strSystemBuildTaskID = $arrRow['id'];
            $arrJSONData = json_decode($arrRow['jsondata'], true);

            $strTaskname = formValueGetBySectionCodeFieldCode($arrJSONData, 'gd8fac874-b432-4019-b5b0-abefded76d05', "COMMAND");
            $strParameters = formValueGetBySectionCodeFieldCode($arrJSONData, 'gd8fac874-b432-4019-b5b0-abefded76d05', "PARAMETERS");

            // replacing systemmodule id inside parameters
            // im not sure if this is the proper way
            // #by jhun
            $strParameters = str_replace('%SYSTEMMODULEID%', $strSystemModuleID_a, $strParameters);

            if (runTask($objConn_a, $strTaskname, $strParameters));
            {   
                $strEndTime = time();

                $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gd8fac874-b432-4019-b5b0-abefded76d05', "IS_PROCESSED", 'Y');
                $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gd8fac874-b432-4019-b5b0-abefded76d05', "STARTTIME", $strStartTime);
                $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gd8fac874-b432-4019-b5b0-abefded76d05', "ENDTIME", $strEndTime);
                $strJSONData = json_encode($arrJSONData);

                $strSQL = "update ~TABLENAMESYSTEMBUILDTASK~ set jsondata = '~JSONDATA~' where id = ~SYSTEMBUILDTASKID~";
                $strSQL = str_replace('~TABLENAMESYSTEMBUILDTASK~', ff($strTableNameSystemBuildTask), $strSQL);
                $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
                $strSQL = str_replace('~SYSTEMBUILDTASKID~', ff($strSystemBuildTaskID), $strSQL);
                dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
                
                exposeEntityData($objConn_a, 'SYSTEMFORM', 'SYSTEMBUILDTASK', $strSystemBuildTaskID, $strJSONData);
            }
        }

        dbCloseRecordset($objResult);
    }

    return $blnResult;
}