<?php

error_reporting(E_ALL);
error_reporting(1);
ini_set('error_reporting', E_ALL);

function processIntegrationTaskInbound($objConn_a)
{
    $strTableNameIntegrationTaskInbound = getTableNameEntity("integrationtaskinbound", false);
    $strTableNameIntegrationOutboundPlugin = getTableNameEntity("integrationoutboundplugin", false);
    $strTableNameOutboundData = getTableNameEntity("outbounddata", false);

	$blnResult = false;
	$intTimeStart = microtime(true);

    $strLogin = $_SESSION['server_loggedin_user'];
    $strNow = getDateTime();
    
	$intIntegrationTasksInRun = 0;
    $intMaxSeconds = 300; // 5 minutes
    $intMaxRun = 10;

	$blnErrors = false;
	$blnMoreTime = true;
    $blnEndOfChanges = false;

    try
    {
        echo("<br />PROCESS INTEGRATION TASKS");
        echo('<br />processing Integration Tasks...');

        $intTimeStart = microtime(true);

        // if we have no errors yet and still more time
        while ((!$blnErrors) && (!$blnEndOfChanges) && ($blnMoreTime))
        {
            dbBeginTrans($objConn_a, __FUNCTION__);

            $strSQL = "select id, code, integrationinboundplugin_id, status, jsondata from  ~TABLENAMEINTEGRATIONTASKINBOUND~ where is_processed = 'N' limit 1";
            $strSQL = str_replace("~TABLENAMEINTEGRATIONTASKINBOUND~", ff($strTableNameIntegrationTaskInbound), $strSQL);        
            $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
            
            if ($arrRow = dbReadRecord($objResult))
            {
                $strIntegrationTaskID = $arrRow['id'];
                $strIntegrationInboundPluginID = $arrRow['integrationinboundplugin_id'];
                $strCode = $arrRow['code'];
                $strStatus = $arrRow['status'];
                                
                if ($strCode === 'RECEIVESTEP2')
                {
                    echo("<br />RECEIVESTEP2");
                    $strIntegrationInboundPlugin = integrationInboundPluginCodeFetch($objConn_a, $strIntegrationInboundPluginID);
                    if (dependencies('integration/inbound/' . strtolower($strIntegrationInboundPlugin)))
                    {
                        echo("<br />PROGRESS");

                        updateIntegrationTaskInboundStatus($objConn_a, $strIntegrationTaskID, 'PROGRESS');
                        
                        if (receiveStep2($objConn_a, $strIntegrationTaskID))
                        {
                            updateIntegrationTaskInboundStatus($objConn_a, $strIntegrationTaskID, 'COMPLETED');
                            updateIntegrationTaskInboundProcessStatus($objConn_a, $strIntegrationTaskID, 'Y');  
                        }
                    }                      
                }
            }
            else
            {
                $blnEndOfChanges = true;
            }

            dbCloseRecordset($objResult);

            if (!dbEndTrans($objConn_a, __FUNCTION__))
            {
                $blnErrors = true;

                echo "<br />Database error <br />";
            }

            if ($intIntegrationTasksInRun > $intMaxRun)
            {
                $blnEndOfChanges = true;
            }

            if (!$blnErrors && !$blnEndOfChanges)
            {
                $intIntegrationTasksInRun++;
            }
                       
            $intTimeNow = microtime(true);
            $intTime = $intTimeNow - $intTimeStart;
            
            if ($intTime > $intMaxSeconds)
            {
                $blnMoreTime = false;
            }
                        
        }
        // while loop ends here
        
        if ($intIntegrationTasksInRun == 0)
        {
            echo ('no more Integration Tasks...');
        }

    }
    catch(\Exception $ex)
    {
        print_r($ex);
        //echo($ex);
        $blnErrors = true;
    }


    echo '<br />';
	echo ('processed ' . $intIntegrationTasksInRun . '. ');
	$intTimeEnd = microtime(true);
    $intTime = $intTimeEnd - $intTimeStart;
    echo '<br />';
    echo "process time: " . $intTime . '. ';
	
	return $intIntegrationTasksInRun;
}

function updateIntegrationTaskInboundStatus($objConn_a, $strIntegrationTaskID_a, $strStatus_a)
{
    $strTableNameIntegrationTaskInbound = getTableNameEntity("integrationtaskinbound", false);

    dbBeginTrans($objConn_a, __FUNCTION__);
	$strSQL = "update ~TABLENAMEINTEGRATIONTASKINBOUND~ set status = '~STATUS~' where id = ~ID~";
    $strSQL = str_replace("~TABLENAMEINTEGRATIONTASKINBOUND~", ff($strTableNameIntegrationTaskInbound), $strSQL);
    $strSQL = str_replace("~ID~", ff($strIntegrationTaskID_a), $strSQL);
    $strSQL = str_replace("~STATUS~", ff($strStatus_a), $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
    return dbEndTrans($objConn_a, __FUNCTION__);
}

function updateIntegrationTaskInboundProcessStatus($objConn_a, $strIntegrationTaskID_a, $strProcessStatus_a)
{
    $strTableNameIntegrationTaskInbound = getTableNameEntity("integrationtaskinbound", false);

    dbBeginTrans($objConn_a, __FUNCTION__);
	$strSQL = "update ~TABLENAMEINTEGRATIONTASKINBOUND~ set is_processed = '~PROCESSSTATUS~' where id = ~ID~";
    $strSQL = str_replace("~TABLENAMEINTEGRATIONTASKINBOUND~", ff($strTableNameIntegrationTaskInbound), $strSQL);
    $strSQL = str_replace("~ID~", ff($strIntegrationTaskID_a), $strSQL);
    $strSQL = str_replace("~PROCESSSTATUS~", ff($strProcessStatus_a), $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
    return dbEndTrans($objConn_a, __FUNCTION__);
}


function integrationInboundPluginCodeFetch($objConn_a, $strIntegrationInboundPluginID_a)
{
    $strTableNameIntegrationInboundPlugin = getTableNameEntity("integrationinboundplugin", false);

    $strSQL = "select code returnvalue from ~TABLENAMEINTEGRATIONINBOUNDPLUGIN~ where id = ~INTEGRATIONINBOUNDPLUGINID~";
    $strSQL = str_replace('~TABLENAMEINTEGRATIONINBOUNDPLUGIN~', ff($strTableNameIntegrationInboundPlugin), $strSQL);
    $strSQL = str_replace('~INTEGRATIONINBOUNDPLUGINID~', ff($strIntegrationInboundPluginID_a), $strSQL);    
    return dbReadValue($objConn_a, $strSQL, __FUNCTION__);
}
