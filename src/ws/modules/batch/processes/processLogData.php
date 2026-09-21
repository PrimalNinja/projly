<?php

// process logdata (should be run nightly)
function processLogData($objConn_a)
{
	$strNow = getDateTime();
	echo('logging data...');

    dbBeginTrans($objConn_a, __FUNCTION__);

	// log client stats
	$strSQL = "insert into log_tblclientstats (client_id, column1, column2, valuetoken, modifyuser, modifydatetime, logdatetime) select client_id, column1, column2, valuetoken, modifyuser, modifydatetime, '~LOGDATETIME~' from dash_tblclientstats";
	$strSQL = str_replace('~LOGDATETIME~', ff($strNow), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
            
    dbEndTrans($objConn_a, __FUNCTION__);
	
	echo ('<br>data logged.');
}
