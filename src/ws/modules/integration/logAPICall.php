<?php

function logAPICall($objConn_a, $strClientID_a, $strAPICallTypeID_a, $strAPISubType_a, $strCallCount_a, $strNotes_a, $strURL_a, $strIsSuccessful_a, $strSuccessResponse_a, $strErrorResponse_a, $strRawRequest_a, $strRawResponse_a)
{
	$strTableNameAPICall = getTableNameEntity("apicall", false);
	$strTableNameAPICallType = getTableNameEntity("apicalltype", false);
	$strTableNameClient = getTableNameEntity("client", false);

	$strLogin = $_SESSION['server_loggedin_user'];
	
	$strDataEntityID = getEntityID($objConn_a, "apicall");
	$strEntityID = getEntityID($objConn_a, "systemform"); 
	$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "APICALL");
	$strCode = "APICALL";
	$strDescription = "APICALL";
	
	$strSQL = "select description returnvalue from ~TABLENAMECLIENT~ where id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strClientName = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	$strSQL = "select description returnvalue from ~TABLENAMEAPICALLTYPE~ where id = ~APICALLTYPEID~";
	$strSQL = str_replace('~TABLENAMEAPICALLTYPE~', ff($strTableNameAPICallType), $strSQL);
	$strSQL = str_replace('~APICALLTYPEID~', ff($strAPICallTypeID_a), $strSQL);
	$strAPICallTypeDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ga0c53bc1-9f46-489f-be5e-09c27f7ef3f6", "CLIENTNAME", $strClientName);
	$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "ga0c53bc1-9f46-489f-be5e-09c27f7ef3f6", "APICALLTYPE", $strAPICallTypeID_a, $strAPICallTypeDescription);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ga0c53bc1-9f46-489f-be5e-09c27f7ef3f6", "SUBTYPE", $strAPISubType_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ga0c53bc1-9f46-489f-be5e-09c27f7ef3f6", "CALLCOUNT", $strCallCount_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ga0c53bc1-9f46-489f-be5e-09c27f7ef3f6", "NOTES", $strNotes_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ga0c53bc1-9f46-489f-be5e-09c27f7ef3f6", "URL", $strURL_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ga0c53bc1-9f46-489f-be5e-09c27f7ef3f6", "ISSUCCESSFUL", $strIsSuccessful_a);
	//$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ga0c53bc1-9f46-489f-be5e-09c27f7ef3f6", "SUCCESSRESPONSE", $strSuccessResponse_a);
	//$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ga0c53bc1-9f46-489f-be5e-09c27f7ef3f6", "ERRORRESPONSE", $strErrorResponse_a);
	//$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ga0c53bc1-9f46-489f-be5e-09c27f7ef3f6", "RAWREQUEST", $strRawRequest_a);
	//$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ga0c53bc1-9f46-489f-be5e-09c27f7ef3f6", "RAWRESPONSE", $strRawResponse_a);

	logAPICallRequestResponse("");
	logAPICallRequestResponse("Client: " . $strClientName);
	logAPICallRequestResponse("Call Type: " . $strAPICallTypeDescription);
	logAPICallRequestResponse("Notes: " . $strNotes_a);
	logAPICallRequestResponse("URL: " . $strURL_a);
	logAPICallRequestResponse("Success: " . $strSuccessResponse_a);
	logAPICallRequestResponse("Error: " . $strErrorResponse_a);
	logAPICallRequestResponse("Raw Request: " . $strRawRequest_a);
	logAPICallRequestResponse("Raw Response: " . $strRawResponse_a);
	
	$strJSONData = json_encode($arrJSONData);
	
	dbBeginTrans($objConn_a, __FUNCTION__);
	
	$strSQL = "insert into ~TABLENAMEAPICALL~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime)
				values (~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~DATACLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~')";
	$strSQL = str_replace('~TABLENAMEAPICALL~', ff($strTableNameAPICall), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
	$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strCode), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strDescription), $strSQL);
	$strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
	$strSQL = str_replace('~DATACLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);    
	$strAPICallID = dbLastInsertID($objConn_a);
	$strResult = $strAPICallID;

	exposeEntityData($objConn_a, 'SYSTEMFORM', 'APICALL', $strAPICallID, $strJSONData);			
			
	dbEndTrans($objConn_a, __FUNCTION__);
	
	return $strResult;
}
