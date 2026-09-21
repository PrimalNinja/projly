<?php

function processBatchBuild($objConn_a)
{
	global $g_arrDefaultDictionary;
	global $g_arrDictionary;

    $strTableNameSystemBuild = getTableNameEntity("systembuild", false);    
    $strTableNameSystemBuildTask = getTableNameEntity("systembuildtask", false);
    $strTableNameSystemModule = getTableNameEntity("systemmodule", false);

    $strLogin = $_SESSION['server_loggedin_user'];

	$blnResult = false;
    $blnContinue = true;

	$intTimeStart = microtime(true);
        
	$intTasksInRun = 0;
    $intMaxSeconds = 300; // 5 minutes
    //$intMaxRun = 10;

	$blnErrors = false;
	$blnMoreTime = true;
    $blnEndOfChanges = false;
    
    $strTaskStartTime = '';
    $strTaskEndTime = '';

    if (dependencies('developer/runTask,developer/tasks'))
	{
        	
		echo('<br />');
		echo('start processing batch build ...');
        echo('<br />');

        try
        {
            $strStartTime = time();

            // if we have no errors yet and still more time
            while ((!$blnErrors) && (!$blnEndOfChanges) && ($blnMoreTime))
            {
                dbBeginTrans($objConn_a, __FUNCTION__);

                $strSQL = "select id, client_id, jsondata, g3addd7a5_6125_499d_b87a_c827161eae5f_status status, g3addd7a5_6125_499d_b87a_c827161eae5f_folder folder from ~TABLENAMESYSTEMBUILD~ where g3addd7a5_6125_499d_b87a_c827161eae5f_status <> 'COMPLETED' limit 1";
                $strSQL = str_replace('~TABLENAMESYSTEMBUILD~', ff($strTableNameSystemBuild), $strSQL);
                $objResultSystemBuild = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
                
                if ($arrRowSytemBuild = dbReadRecord($objResultSystemBuild))
                { 
                    $strSystemBuildJSONData = $arrRowSytemBuild['jsondata'];
                    $strSystemBuildID = $arrRowSytemBuild['id'];
                    $strBuildFolder = $arrRowSytemBuild['folder'];

                    if (!file_exists(BUILD_PATH . $strBuildFolder))
                    {
                        echo '<br />';
                        echo 'Build folder does not exists. either it is not been created or deleted.';
                        echo '<br />';
                        $blnErrors = true;
                    }

                    // get the systemmodule in order, system modules has dependencies. needs to process them in order.
                    $strSQL = "select distinct systemmodule_id from ~TABLENAMESYSTEMBUILDTASK~ t, ~TABLENAMESYSTEMMODULE~ m
                                where t.systemmodule_id=m.id and t.systembuild_id = ~SYSTEMBUILDID~ order by cast(m.gdb7a73ca_38b3_43bf_9d17_a599f23507e5_sortorder as unsigned) asc";
                    $strSQL = str_replace('~TABLENAMESYSTEMBUILDTASK~', ff($strTableNameSystemBuildTask), $strSQL);
                    $strSQL = str_replace('~TABLENAMESYSTEMMODULE~', ff($strTableNameSystemModule), $strSQL);
                    $strSQL = str_replace('~SYSTEMBUILDID~', ff($strSystemBuildID), $strSQL);
                    $objResultSystemModule = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
                   
                    while ($arrRowSystemModule = dbReadRecord($objResultSystemModule))
                    { 
                        $strSystemModuleID = $arrRowSystemModule['systemmodule_id'];

                        $strSQL = "select id, jsondata from ~TABLENAMESYSTEMBUILDTASK~ where systembuild_id=~SYSTEMBUILDID~ and systemmodule_id=~SYSTEMMODULEID~ and gd8fac874_b432_4019_b5b0_abefded76d05_is_processed = 'N' order by sortorder";
                        $strSQL = str_replace('~TABLENAMESYSTEMBUILDTASK~', ff($strTableNameSystemBuildTask), $strSQL);
                        $strSQL = str_replace('~SYSTEMBUILDID~', ff($strSystemBuildID), $strSQL);
                        $strSQL = str_replace('~SYSTEMMODULEID~', ff($strSystemModuleID), $strSQL);
                        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
                              
                        while ($arrRow = dbReadRecord($objResult))
                        {   
                            $strTaskStartTime = time();

                            $strSystemBuildTaskID = $arrRow['id'];                        
                            $arrJSONData = json_decode($arrRow['jsondata'], true);
                            
                            $strPathBuild = BUILD_PATH . $strBuildFolder;
                            $strDictionaryFile = BUILD_PATH . "dictionary.json";
                            $strPathSystemConfig = APP_PATH . 'config'; // SYS_PATH_CONFIG

                            $strDictionary = "";
                            $g_arrDictionary = [];
                            if (file_exists($strDictionaryFile))
                            {
                                $strDictionary = loadFile($strDictionaryFile);
                                if (strlen($strDictionary) > 0)
                                {
                                    $g_arrDictionary = json_decode($strDictionary, true);
                                }
                            }
                            
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
                            addDictionary('%SYS_TODAY%', getToday());
                            addDictionary('%SYS_PATH_CONFIG%', $strPathSystemConfig);                            
                            addDictionary('%PATH_APP%', APP_PATH);
                            addDictionary('%PATH_BUILD%', $strPathBuild);

                            $strTaskname = formValueGetBySectionCodeFieldCode($arrJSONData, 'gd8fac874-b432-4019-b5b0-abefded76d05', "COMMAND");
                            $strParameters = formValueGetBySectionCodeFieldCode($arrJSONData, 'gd8fac874-b432-4019-b5b0-abefded76d05', "PARAMETERS");

                            // replacing systemmodule id inside parameters
                            // im not sure if this is the proper way
                            // #by jhun
                            $strParameters = str_replace('%SYSTEMMODULEID%', $strSystemModuleID, $strParameters);                    

                            if (runTask($objConn_a, $strTaskname, $strParameters))
                            {
                                $strTaskEndTime = time();

                                $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gd8fac874-b432-4019-b5b0-abefded76d05', "IS_PROCESSED", 'Y');
                                $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gd8fac874-b432-4019-b5b0-abefded76d05', "STARTTIME", $strTaskStartTime);
                                $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gd8fac874-b432-4019-b5b0-abefded76d05', "ENDTIME", $strTaskEndTime);
                                $strJSONData = json_encode($arrJSONData);

                                $strSQL = "update ~TABLENAMESYSTEMBUILDTASK~ set jsondata = '~JSONDATA~' where id = ~SYSTEMBUILDTASKID~";
                                $strSQL = str_replace('~TABLENAMESYSTEMBUILDTASK~', ff($strTableNameSystemBuildTask), $strSQL);
                                $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
                                $strSQL = str_replace('~SYSTEMBUILDTASKID~', ff($strSystemBuildTaskID), $strSQL);
                                dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
                        
                                exposeEntityData($objConn_a, 'SYSTEMFORM', 'SYSTEMBUILDTASK', $strSystemBuildTaskID, $strJSONData);
                            }   
                            
                            if (!$blnErrors && !$blnEndOfChanges)
                            {
                                $intTasksInRun++;
                            }
                                    
                            $intTimeNow = microtime(true);
                            $intTime = $intTimeNow - $intTimeStart;
                            
                            if ($intTime > $intMaxSeconds)
                            {
                                $blnMoreTime = false;
                            }

                            echo '<br />';
                            echo 'Tasks processed : ' . $intTasksInRun;
                            echo '<br />';
                        }

                        dbCloseRecordset($objResult);
                    }
                    // while systemmodule
                    dbCloseRecordset($objResultSystemModule);

                    $strSQL = "select count(*) from ~TABLENAMESYSTEMBUILDTASK~ where systembuild_id=~SYSTEMBUILDID~ and gd8fac874_b432_4019_b5b0_abefded76d05_is_processed = 'N'";
                    $strSQL = str_replace('~TABLENAMESYSTEMBUILDTASK~', ff($strTableNameSystemBuildTask), $strSQL);
                    $strSQL = str_replace('~SYSTEMBUILDID~', ff($strSystemBuildID), $strSQL);
                    $strTasksCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

                    if (intval($strTasksCount) == 0)
                    {
                        $arrJSONData = json_decode($strSystemBuildJSONData, true);
                        $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g3addd7a5-6125-499d-b87a-c827161eae5f', "STATUS", 'COMPLETED');
                        $strJSONData = json_encode($arrJSONData);

                        $strSQL = "update ~TABLENAMESYSTEMBUILD~ set jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where id = ~SYSTEMBUILDID~";
                        $strSQL = str_replace('~TABLENAMESYSTEMBUILD~', ff($strTableNameSystemBuild), $strSQL);
                        $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);			
                        $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
                        $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
                        $strSQL = str_replace('~SYSTEMBUILDID~', ff($strSystemBuildID), $strSQL);
                        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

                        exposeEntityData($objConn_a, 'SYSTEMFORM', 'SYSTEMBUILD', $strSystemBuildID, $strJSONData);

                        echo '<br />';
                        echo 'Build completed';
                        echo '<br />';
                    }

                } // if sysmtembuild
                else
                {
                    $blnEndOfChanges = true;
                }
                
                dbCloseRecordset($objResultSystemBuild);
                
                if (!dbEndTrans($objConn_a, __FUNCTION__))
                {
                    $blnErrors = true;
                    echo "<br />Database error <br />";
                }
            }
            // end while ((!$blnErrors) && (!$blnEndOfChanges) && ($blnMoreTime))

            $strEndTime = time();
            
            echo ("<br />");
            echo ('end processing batch build.');
            echo ("<br />");
        }
        catch(Exception $e)
        {
            print_r($e);
        }

	} // dependencies

    $blnResult = !$blnErrors;
    
    return $blnResult;
}