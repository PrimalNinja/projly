<?php

// check the ESB
function esbCheck($objConn_a, $strClientID_a, $strDeviceID_a, $strEventQueueList_a)
{
    $arrResult = array();

    if (strlen($strEventQueueList_a) > 0) 
	{
        // create the esb entries for enabled listeners
        $strSQL =
            "
	select e.id
	from ~TABLENAMEESB~ e, ~TABLENAMEESBLISTENER~ l, ~TABLENAMEESBSTATUS~ s
	where
	e.client_id = ~CLIENTID~ and
	l.id = e.listener_id and
	l.client_id = e.client_id and
	l.computer_id = ~DEVICEID~ and
	s.id = e.status_id and
	s.code = 'PEND' and
	l.eventqueue in (~EVENTQUEUELIST~) and
	l.is_enabled = 'Y'
	order by e.id
	";

		$strSQL = str_replace("~TABLENAMEESB~", CORE_ESB, $strSQL);
		$strSQL = str_replace("~TABLENAMEESBLISTENER~", CORE_ESBLISTENER, $strSQL);
		$strSQL = str_replace("~TABLENAMEESBSTATUS~", CORE_ESBSTATUS, $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
        $strSQL = str_replace('~DEVICEID~', ff($strDeviceID_a), $strSQL);
        $strSQL = str_replace('~EVENTQUEUELIST~', $strEventQueueList_a, $strSQL);

        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        if ($arrRow = dbReadRecord($objResult)) {
            $arrResult[] = array(
                "eventid" => secureValue("ESB", $arrRow['id']),
                "eventqueue" => 'esb',
                "eventname" => 'esb',
				"eventdata" => ''
            );
        }
        dbCloseRecordset($objResult);
    }

    return $arrResult;
}
