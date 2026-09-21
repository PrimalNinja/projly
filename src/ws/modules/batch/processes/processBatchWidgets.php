<?php

function processBatchWidgets($objConn_a, $strBatchClientID_a, $strBatchDeviceID_a)
{
	$strTableNameClient = getTableNameEntity("client", false);

	echo ("started processing widgets...");
    
    if (dependencies('batch/batchJobAdd')) 
	{
        // initialisations
        $strUserID = $_SESSION['server_loggedin_userid'];
		
        $arrMetadata = array(
            'batchclientid' => $strBatchClientID_a,
            'batchdeviceid' => $strBatchDeviceID_a
        );
        
        $strMetaData = json_encode($arrMetadata);
        
        $strSQL = "select id from ~TABLENAMECLIENT~ order by id";
		$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        while ($arrRow = dbReadRecord($objResult)) 
		{ 
            $strClientID = $arrRow['id'];
            batchJobAdd($objConn_a, $strClientID, $strUserID, '3', 'WIDGETS', '', 'PROCESS_WIDGETS', $strMetaData, true, false);
        }
		
		dbCloseRecordset($objResult);
    }
        	
    echo ("end processing widgets...");
}
