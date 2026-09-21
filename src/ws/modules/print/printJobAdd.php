<?php

// add a print print job for a client
function printJobAdd($objConn_a, $strClientID_a, $strUserID_a, $strDocumentID_a, $strPrintJobCode_a, $strDescription_a, $strPrintQueueID_a, $strStatus_a, $strPrinter_a, $strMetaData_a)
{
    $strTableNamePrintQueue = getTableNameEntity("printqueue", false);
    $strTableNamePrintJob = getTableNameEntity("printjob", false);
	$strTableNameUser = getTableNameEntity("user", false);

    $strResult = '';

    dbBeginTrans($objConn_a, __FUNCTION__);

    $strDataEntityID = getEntityID($objConn_a, "printjob");
    $strEntityID = getEntityID($objConn_a, "systemform");     
	$strLogin = $_SESSION['server_loggedin_user'];
    
    //$strLogin = 'sysadmin';
	//$strCode = "PRINTJOB";
    //$strDescription = "Printing Issue " . $strPrintJobCode_a;
    //$strStatus = "";
	
    $arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "PRINTJOB");
    $strJSONData = json_encode($arrJSONData);

	$strSQL = "select description returnvalue from ~TABLENAMEPRINTQUEUE~ where client_id = ~CLIENTID~ and id = ~PRINTQUEUEID~";
	$strSQL = str_replace('~TABLENAMEPRINTQUEUE~', ff($strTableNamePrintQueue), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~PRINTQUEUEID~', ff($strPrintQueueID_a), $strSQL);
	$strPrintQueueDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	$strSQL = "select user_id returnvalue from ~TABLENAMEPRINTQUEUE~ where client_id = ~CLIENTID~ and id = ~PRINTQUEUEID~";
	$strSQL = str_replace('~TABLENAMEPRINTQUEUE~', ff($strTableNamePrintQueue), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~PRINTQUEUEID~', ff($strPrintQueueID_a), $strSQL);
	$strPrintQueueUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	$strPrintQueueOwner = "";
	if (strlen($strPrintQueueUserID) > 0)
	{
		$strSQL = "select description returnvalue from ~TABLENAMEUSER~ where client_id = ~CLIENTID~ and id = ~USERID~";
		$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strPrintQueueUserID), $strSQL);
		$strPrintQueueOwner = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	}

    $arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "PRINTJOB");
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb398c9db-2272-4748-907c-d9685b924329', "CODE", $strPrintJobCode_a);  
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb398c9db-2272-4748-907c-d9685b924329', "DESCRIPTION", $strDescription_a);  
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb398c9db-2272-4748-907c-d9685b924329', "QUEUEOWNER", $strPrintQueueOwner);
    $arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'gb398c9db-2272-4748-907c-d9685b924329', "PRINTQUEUE", $strPrintQueueID_a, $strPrintQueueDescription);
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb398c9db-2272-4748-907c-d9685b924329', "JOBDATETIME", getDateTime());  
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb398c9db-2272-4748-907c-d9685b924329', "PRINTER", $strPrinter_a);  
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb398c9db-2272-4748-907c-d9685b924329', "STATUS", $strStatus_a);  
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb398c9db-2272-4748-907c-d9685b924329', "METADATA", $strMetaData_a);  
    $strJSONData = json_encode($arrJSONData);

    // create the print job
    $strSQL ="insert into ~TABLENAMEPRINTJOB~ 
              (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, user_id, modifyuser, modifydatetime, printqueue_id, document_id)
              values 
              (~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', 'Y', ~DATACLIENTID~, '~JSONDATA~', ~USERID~, '~MODIFYUSER~', '~MODIFYDATETIME~', ~PRINTQUEUEID~, ~DOCUMENTID~)";

    $strSQL = str_replace('~TABLENAMEPRINTJOB~', ff($strTableNamePrintJob), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
    $strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
    $strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
    $strSQL = str_replace('~CODE~', ff($strPrintJobCode_a), $strSQL);
    $strSQL = str_replace('~DESCRIPTION~', ff($strDescription_a), $strSQL);
    $strSQL = str_replace('~DATACLIENTID~', ff($strClientID_a), $strSQL);
    $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
    $strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
    $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
    $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);    
    $strSQL = str_replace('~PRINTQUEUEID~', ff($strPrintQueueID_a), $strSQL);
    $strSQL = str_replace('~DOCUMENTID~', ff($strDocumentID_a), $strSQL);
    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

    $strPrintJobID = dbLastInsertID($objConn_a);
    exposeEntityData($objConn_a, 'SYSTEMFORM', 'PRINTJOB', $strPrintJobID, $strJSONData);

    if (dbEndTrans($objConn_a, __FUNCTION__))
    {
        $strResult = $strPrintJobID;
    }
	
    return $strResult;
}
