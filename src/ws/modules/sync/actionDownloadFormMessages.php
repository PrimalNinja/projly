<?php

// fetch and download form messages
function actionDownloadFormMessages($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $arrResult = array();
	$strLastMessageID = '';
	
    // permission check
    if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

	// initialisations

	$strClientID = $_SESSION['server_loggedin_clientid'];
	$strUserID = $_SESSION['server_loggedin_userid'];

	// fetch
	$strSQL =
		"
select f.id id, f.client_id client_id, f.user_id user_id, f.msg_guid msg_guid, f.message message, f.is_downloaded is_downloaded, f.createdatetime createdatetime, f.modifydatetime modifydatetime
from sync_tblformmessage f
where f.client_id = ~CLIENTID~
and f.user_id = ~USERID~
and f.is_downloaded = 'N'
order by f.id
";

	$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
	$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);

	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	while ($arrRow = dbReadRecord($objResult)) 
	{
		$strLastMessageID = $arrRow['id'];
		
		$arrResult[] = array(
			"id" => secureValue(SYNC_FORM_MESSAGES, $arrRow['id']),
			"client_id" => secureEntityValue('CLIENT', $arrRow['client_id']),
			"user_id" => secureEntityValue('USER', $arrRow['user_id']),
			"msg_guid" => $arrRow['msg_guid'],
			"message" => $arrRow['message'],
			"is_downloaded" => $arrRow['is_downloaded'],
			"createdatetime" => $arrRow['createdatetime'],
			"modifydatetime" => $arrRow['modifydatetime']
		);
	}
	dbCloseRecordset($objResult);

	if (strlen($strLastMessageID) > 0)
	{
		dbBeginTrans($objConn_a, __FUNCTION__);

		$strSQL = "update sync_tblformmessage set is_downloaded = 'Y' where client_id = ~CLIENTID~ and user_id = ~USERID~ and is_downloaded = 'N' and id <= ~LASTMESSAGEID~";
		$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
		$strSQL = str_replace('~LASTMESSAGEID~', ff($strLastMessageID), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
		dbEndTrans($objConn_a, __FUNCTION__);
	}
	
    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
