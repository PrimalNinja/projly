<?php

// check for print jobs
function serverEventPrintJobsCheck($objConn_a, $strClientID_a, $strUserID_a, $strDeviceID_a, $arrParameters_a)
{
	$strTableNamePrintJob = getTableNameEntity("printjob", false);
    $strTableNamePrintQueue = getTableNameEntity("printqueue", false);
	
	$arrResult = [];

    if (dependencies('setting/settingGet')) 
	{
        // get a list of printer queues that we are monitoring based on our computer
        $arrPrinterQueueList = settingGet($objConn_a, 'CORE', 'PRINTFROMQ', $strClientID_a, '', '', '', $strDeviceID_a, __FUNCTION__);
		if (!is_array($arrPrinterQueueList))
		{
			$arrPrinterQueueList = [];
		}

        // iterate throught the queue list and create a queueid list
        $strQueueList = '';
        for ($intI = 0; $intI < count($arrPrinterQueueList); $intI++) 
		{
            $strQueueID = $arrPrinterQueueList[$intI];
			if (strlen($strQueueID) > 0)
			{
				// verify the queueid
				$strSQL = "select id returnvalue from ~TABLENAMEPRINTQUEUE~ where client_id = ~CLIENTID~ and id = ~PRINTQUEUEID~";
				$strSQL = str_replace('~TABLENAMEPRINTQUEUE~', ff($strTableNamePrintQueue), $strSQL);
				$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
				//$strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
				$strSQL = str_replace('~PRINTQUEUEID~', ff($strQueueID), $strSQL);
				$strQueueID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

				// if the queueid is valid, add it to the list
				if (strlen($strQueueID) > 0) 
				{
					if (strlen($strQueueList) == 0) 
					{
						$strQueueList = $strQueueID;
					} 
					else 
					{
						$strQueueList .= "," . $strQueueID;
					}
				}
			}
        }

        if (strlen($strQueueList) > 0) 
		{
            $strSQL = "select pj.id, pj.document_id, pj.jsondata jsondata
						from ~TABLENAMEPRINTJOB~ pj, ~TABLENAMEPRINTQUEUE~ pq
						where 
						pj.client_id = ~CLIENTID~ and 
						pq.id = pj.printqueue_id and 
						pq.client_id = pj.client_id and 
						pq.id in (~PRINTQUEUELIST~) and 
						pj.gb398c9db_2272_4748_907c_d9685b924329_status = '~STATUS~'
						order by pj.id";
            $strSQL = str_replace('~TABLENAMEPRINTJOB~', ff($strTableNamePrintJob), $strSQL);
            $strSQL = str_replace('~TABLENAMEPRINTQUEUE~', ff($strTableNamePrintQueue), $strSQL);
            $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
            $strSQL = str_replace('~PRINTQUEUELIST~', $strQueueList, $strSQL);
            //$strSQL = str_replace('~STATUS~', STAT_PENDING, $strSQL);
            $strSQL = str_replace('~STATUS~', STAT_READY, $strSQL);

            $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
            if ($arrRow = dbReadRecord($objResult)) 
			{
                $arrResult[] = array(
                    "eventid" => secureValue("ESB", $arrRow['id']),
                    "eventqueue" => 'printer',
                    "eventname" => 'printjob',
					"eventdata" => ''
                );
            }
            dbCloseRecordset($objResult);
        }
    }

    return $arrResult;
}
