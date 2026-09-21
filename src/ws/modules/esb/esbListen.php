<?php

// listen to the ESB
function esbListen($objConn_a, $strClientID_a, $strDeviceID_a, $strEventQueueList_a)
{
    $arrResult = array();

    if (strlen($strEventQueueList_a) > 0) 
	{
        // create the esb entries for enabled listeners
        $strSQL =
            "
	select min(e.id) id, max(e.id) maxid, e.listener_id, e.eventqueue, e.event, e.eventdata
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
	group by e.listener_id, e.eventqueue, e.event
	";

		$strSQL = str_replace("~TABLENAMEESB~", CORE_ESB, $strSQL);
		$strSQL = str_replace("~TABLENAMEESBLISTENER~", CORE_ESBLISTENER, $strSQL);
		$strSQL = str_replace("~TABLENAMEESBSTATUS~", CORE_ESBSTATUS, $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
        $strSQL = str_replace('~DEVICEID~', ff($strDeviceID_a), $strSQL);
        $strSQL = str_replace('~EVENTQUEUELIST~', $strEventQueueList_a, $strSQL);

        $intMin = 0;
        $intMax = 0;
        $strListenerID = 0;

        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        while ($arrRow = dbReadRecord($objResult)) 
		{
            if (($intMin == 0) || ($arrRow['id'] < $intMin)) 
			{
                $intMin = $arrRow['id'];
            }

            if ($arrRow['maxid'] > $intMax) 
			{
                $intMax = $arrRow['maxid'];
            }

            $strListenerID = $arrRow['listener_id'];

            $arrResult[] = array(
                "eventid" => secureValue("ESB", $arrRow['id']),
                "eventqueue" => $arrRow['eventqueue'],
                "eventname" => $arrRow['event'],
                "eventdata" => $arrRow['eventdata']
            );
        }
        dbCloseRecordset($objResult);

        if (($intMin > 0) && ($intMax >= $intMin) && (strlen($strListenerID) > 0)) 
		{
            $strSQL = "select id returnvalue from ~TABLENAMEESBSTATUS~ where code = 'PEND'";
			$strSQL = str_replace("~TABLENAMEESBSTATUS~", CORE_ESBSTATUS, $strSQL);
            $strStatusIDPending = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

            $strSQL = "select id returnvalue from ~TABLENAMEESBSTATUS~ where code = 'DEL'";
			$strSQL = str_replace("~TABLENAMEESBSTATUS~", CORE_ESBSTATUS, $strSQL);
            $strStatusIDDelivered = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

            $strSQL =
                "
	update ~TABLENAMEESB~
	set status_id = ~NEWSTATUSID~, is_dataread = 'Y'
	where
	client_id = ~CLIENTID~ and
	listener_id = ~LISTENERID~ and
	status_id = ~OLDSTATUSID~ and
	id >= ~MIN~ and
	id <= ~MAX~
		";
			$strSQL = str_replace("~TABLENAMEESB~", CORE_ESB, $strSQL);
            $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
            $strSQL = str_replace('~LISTENERID~', ff($strListenerID), $strSQL);
            $strSQL = str_replace('~OLDSTATUSID~', ff($strStatusIDPending), $strSQL);
            $strSQL = str_replace('~NEWSTATUSID~', ff($strStatusIDDelivered), $strSQL);
            $strSQL = str_replace('~MIN~', ff($intMin), $strSQL);
            $strSQL = str_replace('~MAX~', ff($intMax), $strSQL);

            dbBeginTrans($objConn_a, __FUNCTION__);
            dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
            dbEndTrans($objConn_a, __FUNCTION__);
        }
    }

    return $arrResult;
}
