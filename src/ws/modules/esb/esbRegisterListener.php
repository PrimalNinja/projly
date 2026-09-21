<?php

// register as an ESB listener
function esbRegisterListener($objConn_a, $strClientID_a, $strDeviceID_a, $strEventQueue_a)
{
    $strLogin = $_SESSION['server_loggedin_user'];

    $strSQL = "select id returnvalue from ~TABLENAMEESBLISTENER~ where client_id = ~CLIENTID~ and computer_id = ~DEVICEID~ and eventqueue = '~EVENTQUEUE~'";
	$strSQL = str_replace("~TABLENAMEESBLISTENER~", CORE_ESBLISTENER, $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
    $strSQL = str_replace('~DEVICEID~', ff($strDeviceID_a), $strSQL);
    $strSQL = str_replace('~EVENTQUEUE~', ff($strEventQueue_a), $strSQL);
    $strListenerID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    // create the esb listener if not already registered
    if (strlen($strListenerID) == 0) 
	{
        dbBeginTrans($objConn_a, __FUNCTION__);

        $strSQL =
            "
	insert into ~TABLENAMEESBLISTENER~ (client_id, computer_id, eventqueue, is_enabled, modifyuser, modifydatetime)
	values (~CLIENTID~, ~DEVICEID~, '~EVENTQUEUE~', 'Y', '~MODIFYUSER~', '~MODIFYDATETIME~')
	";
		
		$strSQL = str_replace("~TABLENAMEESBLISTENER~", CORE_ESBLISTENER, $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
        $strSQL = str_replace('~DEVICEID~', ff($strDeviceID_a), $strSQL);
        $strSQL = str_replace('~EVENTQUEUE~', ff($strEventQueue_a), $strSQL);
        $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
        $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

        dbEndTrans($objConn_a, __FUNCTION__);
    }
}
