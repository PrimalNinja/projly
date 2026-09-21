<?php

// add a timesheet entry
function timesheetSyncAdd($objConn_a, $strClientID_a, $strUserID_a, $strGUID_a, $strCode_a, $strTitle_a, $strOnDuty_a, $strNotes_a, $strProcessed_a, $strCreateDateTime_a, $strModifyDateTime_a) 
{
    $strAddressID = "";

	dbBeginTrans($objConn_a, __FUNCTION__);

	$strSQL =
		"
insert into sync_tbltimesheet (client_id, user_id, guid, code, title, onduty, notes, processed, createdatetime, modifydatetime)
values (~CLIENTID~, ~USERID~, '~GUID~', '~CODE~', '~TITLE~', '~ONDUTY~', '~NOTES~', '~PROCESSED~', '~CREATEDATETIME~', '~MODIFYDATETIME~')
";

	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
	$strSQL = str_replace('~GUID~', ff($strGUID_a), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strCode_a), $strSQL);
	$strSQL = str_replace('~TITLE~', ff($strTitle_a), $strSQL);
	$strSQL = str_replace('~ONDUTY~', ff($strOnDuty_a), $strSQL);
	$strSQL = str_replace('~NOTES~', ff($strNotes_a), $strSQL);
	$strSQL = str_replace('~PROCESSED~', ff($strProcessed_a), $strSQL);
	$strSQL = str_replace('~CREATEDATETIME~', ff($strCreateDateTime_a), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', ff($strModifyDateTime_a), $strSQL);

	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	$strTimesheetID = dbLastInsertID($objConn_a);

	dbEndTrans($objConn_a, __FUNCTION__);

    return $strTimesheetID;
}
