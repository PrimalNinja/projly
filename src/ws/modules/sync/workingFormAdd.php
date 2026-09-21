<?php

// add a working form entry
function workingFormAdd($objConn_a, $strClientID_a, $strUserID_a, $strGUID_a, $strSections_a, $strImages_a, $strProcessed_a, $strDescription_a, $strCreateDateTime_a, $strModifyDateTime_a) {

	dbBeginTrans($objConn_a, __FUNCTION__);

	$strSQL =
		"
insert into sync_tblworkingform (client_id, user_id, guid, sections, images, processed, description, createdatetime, modifydatetime)
values (~CLIENTID~, ~USERID~, '~GUID~', '~SECTIONS~', '~IMAGES~', '~PROCESSED~', '~DESCRIPTION~', '~CREATEDATETIME~', '~MODIFYDATETIME~')
";

	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
	$strSQL = str_replace('~GUID~', ff($strGUID_a), $strSQL);
	$strSQL = str_replace('~SECTIONS~', ff($strSections_a), $strSQL);
	$strSQL = str_replace('~IMAGES~', ff($strImages_a), $strSQL);
	$strSQL = str_replace('~PROCESSED~', ff($strProcessed_a), $strSQL);
    $strSQL = str_replace('~DESCRIPTION~', ff($strDescription_a), $strSQL);
	$strSQL = str_replace('~CREATEDATETIME~', ff($strCreateDateTime_a), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', ff($strModifyDateTime_a), $strSQL);

	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	$strWorkingFormID = dbLastInsertID($objConn_a);

	dbEndTrans($objConn_a, __FUNCTION__);

    return $strWorkingFormID;
}
