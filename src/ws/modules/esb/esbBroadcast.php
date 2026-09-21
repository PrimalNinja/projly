<?php

// broadcast a message via the ESB
function esbBroadcast($objConn_a, $strClientID_a, $strFromDeviceID_a, $strToDeviceID_a, $strEventQueue_a, $strEvent_a, $strEventData_a, $blnBroadcastToSelf_a, $blnCrossClients_a)
{
	$blnResult = false;
	$strListenerID = '';

    $strSQL = "select id returnvalue from ~TABLENAMEESBBROADCASTER~ where client_id = ~CLIENTID~ and computer_id = ~DEVICEID~ and eventqueue = '~EVENTQUEUE~' and is_enabled = 'Y'";
	$strSQL = str_replace("~TABLENAMEESBBROADCASTER~", CORE_ESBBROADCASTER, $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
    $strSQL = str_replace('~DEVICEID~', ffn($strFromDeviceID_a), $strSQL);
    $strSQL = str_replace('~EVENTQUEUE~', ff($strEventQueue_a), $strSQL);
    $strBroadcasterID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	if (strlen($strToDeviceID_a) > 0)
	{
		$strSQL = "select id returnvalue from ~TABLENAMEESBLISTENER~ where client_id = ~CLIENTID~ and computer_id = ~DEVICEID~ and eventqueue = '~EVENTQUEUE~' and is_enabled = 'Y'";
		$strSQL = str_replace("~TABLENAMEESBLISTENER~", CORE_ESBLISTENER, $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~DEVICEID~', ffn($strToDeviceID_a), $strSQL);
		$strSQL = str_replace('~EVENTQUEUE~', ff($strEventQueue_a), $strSQL);
		$strListenerID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	}

    // only enabled registered broadcasters can broadcast
    if (strlen($strBroadcasterID) > 0) 
	{
        $dte = getDateTime();
        $dteTimeout = getDateTimePlusSeconds($dte, intval(ESB_TIMEOUT, 10));

        dbBeginTrans($objConn_a, __FUNCTION__);

        // create the esb entries for enabled listeners
		if ($blnCrossClients_a)
		{
			$strSQL =
				"
		insert into ~TABLENAMEESB~ (client_id, broadcaster_id, listener_id, status_id, eventqueue, event, eventdata, is_dataread, createdatetime, timeoutdatetime)
		select l.client_id, ~BROADCASTID~, l.id, s.id, l.eventqueue, '~EVENT~', '~EVENTDATA~', 'N', '~CREATEDATETIME~', '~TIMEOUTDATETIME~'
		from ~TABLENAMEESBLISTENER~ l, ~TABLENAMEESBSTATUS~ s
		where
		s.code = 'PEND' and
		l.eventqueue = '~EVENTQUEUE~' and
		l.is_enabled = 'Y'
		";
		}
		else
		{
			$strSQL =
				"
		insert into ~TABLENAMEESB~ (client_id, broadcaster_id, listener_id, status_id, eventqueue, event, eventdata, is_dataread, createdatetime, timeoutdatetime)
		select l.client_id, ~BROADCASTID~, l.id, s.id, l.eventqueue, '~EVENT~', '~EVENTDATA~', 'N', '~CREATEDATETIME~', '~TIMEOUTDATETIME~'
		from ~TABLENAMEESBLISTENER~ l, ~TABLENAMEESBSTATUS~ s
		where
		s.code = 'PEND' and
		l.client_id = ~CLIENTID~ and
		l.eventqueue = '~EVENTQUEUE~' and
		l.is_enabled = 'Y'
		";
		}

		if (!$blnBroadcastToSelf_a)
		{
			$strSQL .= " and l.id <> ~BROADCASTID~";
		}
		
		if (strlen($strListenerID) > 0)
		{
			$strSQL .= " and l.id = ~LISTENERID~";
		}

		$strSQL = str_replace("~TABLENAMEESB~", CORE_ESB, $strSQL);
		$strSQL = str_replace("~TABLENAMEESBLISTENER~", CORE_ESBLISTENER, $strSQL);
		$strSQL = str_replace("~TABLENAMEESBSTATUS~", CORE_ESBSTATUS, $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
        $strSQL = str_replace('~BROADCASTID~', ff($strBroadcasterID), $strSQL);
		$strSQL = str_replace('~LISTENERID~', ff($strListenerID), $strSQL);
        $strSQL = str_replace('~EVENTQUEUE~', ff($strEventQueue_a), $strSQL);
        $strSQL = str_replace('~EVENT~', ff($strEvent_a), $strSQL);
        $strSQL = str_replace('~EVENTDATA~', ff($strEventData_a), $strSQL);
        $strSQL = str_replace('~CREATEDATETIME~', $dte, $strSQL);
        $strSQL = str_replace('~TIMEOUTDATETIME~', $dteTimeout, $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

        // cleanup expired esb entries
        $strSQL = "select id returnvalue from ~TABLENAMEESBSTATUS~ where code = 'PEND'";
		$strSQL = str_replace("~TABLENAMEESBSTATUS~", CORE_ESBSTATUS, $strSQL);
        $strStatusIDPending = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        $strSQL = "select id returnvalue from ~TABLENAMEESBSTATUS~ where code = 'TO'";
		$strSQL = str_replace("~TABLENAMEESBSTATUS~", CORE_ESBSTATUS, $strSQL);
        $strStatusIDTimedout = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        // timeout
        $strSQL =
            "
update ~TABLENAMEESB~
set status_id = ~NEWSTATUSID~
where
client_id = ~CLIENTID~ and
status_id = ~OLDSTATUSID~ and
timeoutdatetime < '~TIMEOUTDATETIME~'
	";
		$strSQL = str_replace("~TABLENAMEESB~", CORE_ESB, $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
        $strSQL = str_replace('~OLDSTATUSID~', ff($strStatusIDPending), $strSQL);
        $strSQL = str_replace('~NEWSTATUSID~', ff($strStatusIDTimedout), $strSQL);
        $strSQL = str_replace('~TIMEOUTDATETIME~', $dte, $strSQL);

        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

        // delete
        $strSQL =
            "
delete from ~TABLENAMEESB~
where
client_id = ~CLIENTID~ and
status_id <> ~OLDSTATUSID~
	";
		$strSQL = str_replace("~TABLENAMEESB~", CORE_ESB, $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
        $strSQL = str_replace('~OLDSTATUSID~', ff($strStatusIDPending), $strSQL);

        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        $blnResult = dbEndTrans($objConn_a, __FUNCTION__);
    }

	return $blnResult;
}
