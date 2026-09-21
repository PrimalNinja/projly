<?php

// ********* WARNING *********
//
//
// PSEUDOCODE:
//      A: work out what table to fetch from
//      B: fetch the last id from history as previous
//      C: fetch the jsondata by history id (current history id)
//      D: fetch the id from history as  previous
//      E: fetch the id from history as  next
//      F: get the type of table should be use based from the entitycode
//
function formDataVersionFetchSystemForm($objConn_a, $strEntityCode_a, $strClientID_a, $strEntityID_a, $strFormEntityCode_a, $strHistoryFormDataID_a)
{
	$strTableNameEntityHistory = getTableNameEntity($strFormEntityCode_a, true);

	$arrJSONData = array();
    $strModifyUser = "";
    $strModifyDateTime = "";
    $strPreviousID = "";
    $strNextID = "";

    //F:
	if (strlen($strHistoryFormDataID_a) == 0)
	{
		// B:
		$strSQL = "select id returnvalue from ~TABLENAMEENTITYHISTORY~ where client_id = ~CLIENTID~ and entitydata_id = ~ENTITYID~ ORDER BY id DESC LIMIT 1";
		$strSQL = str_replace('~TABLENAMEENTITYHISTORY~', ff($strTableNameEntityHistory), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID_a), $strSQL);
		$strPreviousID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		// B: End

	}
	else
	{
		// C:
		$strSQL = "select jsondata, modifyuser, modifydatetime from ~TABLENAMEENTITYHISTORY~ where client_id = ~CLIENTID~ and id = ~HISTORYFORMDATAID~ ";
		$strSQL = str_replace('~TABLENAMEENTITYHISTORY~', ff($strTableNameEntityHistory), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~HISTORYFORMDATAID~', ff($strHistoryFormDataID_a), $strSQL);
		$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		if ($arrRow = dbReadRecord($objResult)) 
		{
			$arrJSONData = json_decode($arrRow['jsondata'], true);
			$strModifyUser = $arrRow['modifyuser'];
			$strModifyDateTime = $arrRow['modifydatetime'];
		}
		dbCloseRecordset($objResult);
		// C: End

		// D:
		$strSQL = "select id returnvalue from ~TABLENAMEENTITYHISTORY~ where client_id = ~CLIENTID~ and entitydata_id = ~ENTITYID~ AND  id < ~HISTORYFORMDATAID~ ORDER BY id DESC LIMIT 1";
		$strSQL = str_replace('~TABLENAMEENTITYHISTORY~', ff($strTableNameEntityHistory), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID_a), $strSQL);
		$strSQL = str_replace('~HISTORYFORMDATAID~', ff($strHistoryFormDataID_a), $strSQL);
		$strPreviousID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		// D: End

		// E:
		$strSQL = "select id returnvalue from ~TABLENAMEENTITYHISTORY~ where client_id = ~CLIENTID~ and entitydata_id = ~ENTITYID~ AND  id > ~HISTORYFORMDATAID~ ORDER BY id ASC LIMIT 1";
		$strSQL = str_replace('~TABLENAMEENTITYHISTORY~', ff($strTableNameEntityHistory), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID_a), $strSQL);
		$strSQL = str_replace('~HISTORYFORMDATAID~', ff($strHistoryFormDataID_a), $strSQL);
		$strNextID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		// E: End
	}
    //F:end

	$arrResult = array(
	    "jsondata" => $arrJSONData,
        "modifyuser" => $strModifyUser,
        "modifydatetime" => $strModifyDateTime,
		"previd" => $strPreviousID,
        "nextid" => $strNextID
	);

    return $arrResult;
}

