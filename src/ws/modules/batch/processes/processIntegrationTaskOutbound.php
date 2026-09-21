<?php

error_reporting(E_ALL);
error_reporting(1);
ini_set('error_reporting', E_ALL);

function processIntegrationTaskOutbound($objConn_a)
{
    $strTableNameIntegrationTaskOutbound = getTableNameEntity("integrationtaskoutbound", false);
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
        echo("<br />PROCESS INTEGRATION TASKS OUTBOUND");
        echo('<br />processing Integration Tasks...');

        $intTimeStart = microtime(true);

        // if we have no errors yet and still more time
        while ((!$blnErrors) && (!$blnEndOfChanges) && ($blnMoreTime))
        {
            dbBeginTrans($objConn_a, __FUNCTION__);

            $strSQL = "select id, code, integrationoutboundplugin_id, status, jsondata from  ~TABLENAMEINTEGRATIONTASKOUTBOUND~ where is_processed = 'N' limit 1";
            $strSQL = str_replace("~TABLENAMEINTEGRATIONTASKOUTBOUND~", ff($strTableNameIntegrationTaskOutbound), $strSQL);        
            $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
           
            if ($arrRow = dbReadRecord($objResult))
            {
                $strIntegrationTaskID = $arrRow['id'];
                $strIntegrationOutboundPluginID = $arrRow['integrationoutboundplugin_id'];
                $strCode = $arrRow['code'];
                $strStatus = $arrRow['status'];
                
                if ($strCode === 'TRANSMITSTEP2' && $strStatus === 'PENDING')
                {   
                    $strIntegrationOutboundPlugin = integrationOutboundPluginCodeFetch($objConn_a, $strIntegrationOutboundPluginID);
                    if (dependencies('integration/outbound/' . strtolower($strIntegrationOutboundPlugin)))
                    { 
                        updateIntegrationTaskOutboundStatus($objConn_a, $strIntegrationTaskID, 'PROGRESS');

                        // this will create TRANSMITENTITYDATA integrationtask 
                        if (transmitStep2($objConn_a, $strIntegrationTaskID))
                        {
                            updateIntegrationTaskOutboundStatus($objConn_a, $strIntegrationTaskID, 'COMPLETED');
                            updateIntegrationTaskOutboundProcessStatus($objConn_a, $strIntegrationTaskID, 'Y');
                        }
                        else
                        {
                            // status back to pending
                            updateIntegrationTaskOutboundStatus($objConn_a, $strIntegrationTaskID, 'PENDING');
                            $blnErrors = true;
                        }

                        echo ('<br />Processed Integration Task Step 2... <br />');
                    }
                                            
                } 
                else if ($strCode === 'TRANSMITSTEP3' && $strStatus === 'PENDING')
                {
                    $strIntegrationOutboundPlugin = integrationOutboundPluginCodeFetch($objConn_a, $strIntegrationOutboundPluginID);
                    if (dependencies('integration/outbound/' . strtolower($strIntegrationOutboundPlugin)))
                    { 
                        updateIntegrationTaskOutboundStatus($objConn_a, $strIntegrationTaskID, 'PROGRESS');

                        if (transmitStep3($objConn_a, $strIntegrationTaskID))
                        {      
                            updateIntegrationTaskOutboundStatus($objConn_a, $strIntegrationTaskID, 'COMPLETED');
                            updateIntegrationTaskOutboundProcessStatus($objConn_a, $strIntegrationTaskID, 'Y');
                        }
                        else
                        {  
                            // status back to pending
                            updateIntegrationTaskOutboundStatus($objConn_a, $strIntegrationTaskID, 'PENDING');
                            $blnErrors = true;
                        }
                        
                        echo ('<br />Processed Integration Task Step 3... <br />');
                    }
                }                   
                else if ($strCode === 'TRANSMITSTEP4')
                {
                    $strIntegrationOutboundPlugin = integrationOutboundPluginCodeFetch($objConn_a, $strIntegrationOutboundPluginID);
                    if (dependencies('integration/outbound/' . strtolower($strIntegrationOutboundPlugin)))
                    {            
                        $strSQL = "select count(*) returnvalue from ~TABLENAMEOUTBOUNDDATA~ where is_sent = 'N' and integrationtaskoutbound_id = ~INTEGRATIONTASKID~";
                        $strSQL = str_replace('~TABLENAMEOUTBOUNDDATA~', ff($strTableNameOutboundData), $strSQL);
                        $strSQL = str_replace('~INTEGRATIONTASKID~', ff($strIntegrationTaskID), $strSQL);    
                        $intOutboundDataCountUnsent = (int) dbReadValue($objConn_a, $strSQL, __FUNCTION__);
                        
                        if ($intOutboundDataCountUnsent > 0)
                        {
                            if (!transmitStep4($objConn_a, $strIntegrationTaskID))
                            {
                                updateIntegrationTaskOutboundStatus($objConn_a, $strIntegrationTaskID, 'PROGRESS');
                                $blnErrors = true;
                            }

                            echo ('<br />Processed Integration Task Step 4 Sending Outbound data... <br />');
                        }
                        else
                        {
                            updateIntegrationTaskOutboundStatus($objConn_a, $strIntegrationTaskID, 'COMPLETED');
                            updateIntegrationTaskOutboundProcessStatus($objConn_a, $strIntegrationTaskID, 'Y');

                            echo ('<br />Processed Integration Task Step 4 All Outbound data sent... <br />');
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


function updateIntegrationTaskOutboundStatus($objConn_a, $strIntegrationTaskID_a, $strStatus_a)
{
    $strTableNameIntegrationTaskOutbound = getTableNameEntity("integrationtaskoutbound", false);

    dbBeginTrans($objConn_a, __FUNCTION__);
	$strSQL = "update ~TABLENAMEINTEGRATIONTASKOUTBOUND~ set status = '~STATUS~' where id = ~ID~";
    $strSQL = str_replace("~TABLENAMEINTEGRATIONTASKOUTBOUND~", ff($strTableNameIntegrationTaskOutbound), $strSQL);
    $strSQL = str_replace("~ID~", ff($strIntegrationTaskID_a), $strSQL);
    $strSQL = str_replace("~STATUS~", ff($strStatus_a), $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
    return dbEndTrans($objConn_a, __FUNCTION__);
}

function updateIntegrationTaskOutboundProcessStatus($objConn_a, $strIntegrationTaskID_a, $strProcessStatus_a)
{
    $strTableNameIntegrationTaskOutbound = getTableNameEntity("integrationtaskoutbound", false);

    dbBeginTrans($objConn_a, __FUNCTION__);
	$strSQL = "update ~TABLENAMEINTEGRATIONTASKOUTBOUND~ set is_processed = '~PROCESSSTATUS~' where id = ~ID~";
    $strSQL = str_replace("~TABLENAMEINTEGRATIONTASKOUTBOUND~", ff($strTableNameIntegrationTaskOutbound), $strSQL);
    $strSQL = str_replace("~ID~", ff($strIntegrationTaskID_a), $strSQL);
    $strSQL = str_replace("~PROCESSSTATUS~", ff($strProcessStatus_a), $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
    return dbEndTrans($objConn_a, __FUNCTION__);
}

function integrationOutboundPluginCodeFetch($objConn_a, $strIntegrationOutboundPluginID_a)
{
    $strTableNameIntegrationOutboundPlugin = getTableNameEntity("integrationoutboundplugin", false);

    $strSQL = "select code returnvalue from ~TABLENAMEINTEGRATIONOUTBOUNDPLUGIN~ where id = ~INTEGRATIONOUTBOUNDPLUGINID~";
    $strSQL = str_replace('~TABLENAMEINTEGRATIONOUTBOUNDPLUGIN~', ff($strTableNameIntegrationOutboundPlugin), $strSQL);
    $strSQL = str_replace('~INTEGRATIONOUTBOUNDPLUGINID~', ff($strIntegrationOutboundPluginID_a), $strSQL);    
    return dbReadValue($objConn_a, $strSQL, __FUNCTION__);
}

function integrationInboundPluginCodeFetch($objConn_a, $strIntegrationInboundPluginID_a)
{
    $strTableNameIntegrationInboundPlugin = getTableNameEntity("integrationinboundplugin", false);

    $strSQL = "select code returnvalue from ~TABLENAMEINTEGRATIONINBOUNDPLUGIN~ where id = ~INTEGRATIONINBOUNDPLUGINID~";
    $strSQL = str_replace('~TABLENAMEINTEGRATIONINBOUNDPLUGIN~', ff($strTableNameIntegrationInboundPlugin), $strSQL);
    $strSQL = str_replace('~INTEGRATIONINBOUNDPLUGINID~', ff($strIntegrationInboundPluginID_a), $strSQL);    
    return dbReadValue($objConn_a, $strSQL, __FUNCTION__);
}
