<?php

// add a printer queue for a client
function queueAdd($objConn_a, $strQueueCode_a, $strDescription_a, $strClientID_a, $strUserID_a, $blnEnabled_a, $blnPublic_a)
{   
    $strTableNamePrintQueue = getTableNameEntity("printqueue", false);

    $strLogin = $_SESSION['server_loggedin_user'];
    $strQueueID = "";
	
	$strIsEnabled = "N";
	if ($blnEnabled_a)
	{
		$strIsEnabled = "Y";
	}
	
	$strIsPublic = "N";
	if ($blnPublic_a)
	{
		$strIsPublic = "Y";
	}

	dbBeginTrans($objConn_a, __FUNCTION__);

	$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "PRINTQUEUE");
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gff9b32c5-96c5-4850-8834-3ed24e70d727', "CODE", $strQueueCode_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gff9b32c5-96c5-4850-8834-3ed24e70d727', "DESCRIPTION", $strDescription_a);

	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, "printqueue");
	$strJSONData = json_encode($arrJSONData);

	// create the print job
	$strSQL =
		"
	insert into ~TABLENAMEPRINTQUEUE~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, user_id, modifyuser, modifydatetime, is_public)
	values (~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', '~USERID~', '~MODIFYUSER~', '~MODIFYDATETIME~', '~ISPUBLIC~')
	";
	$strSQL = str_replace('~TABLENAMEPRINTQUEUE~', ff($strTableNamePrintQueue), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strQueueCode_a), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strDescription_a), $strSQL);
	$strSQL = str_replace('~ISENABLED~', ff($strIsEnabled), $strSQL);
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
	$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
	$strSQL = str_replace('~ISPUBLIC~', ff($strIsPublic), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);

	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	$strQueueID = dbLastInsertID($objConn_a);

	exposeEntityData($objConn_a, 'SYSTEMFORM', 'PRINTQUEUE', $strQueueID, $strJSONData);

	dbEndTrans($objConn_a, __FUNCTION__);

    return $strQueueID;
}
