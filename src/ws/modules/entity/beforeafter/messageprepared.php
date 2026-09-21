<?php

// $strFormDataID_a = MESSAGEPREPARED

// code in before display add events:
//		default fields within the JSON for display purposes before a user starts filling in a form
//
// code in after events:
//		exposing fields
//		populating manually created fields
//
// code in before events:
// 		modifying the json that is to be stored (it is stored automatically)
//		validation such as uniqueness (don't forget to put unique indexes on field combinations you need to be unique)
//
// event order:
//		before events, saving of json and common fields, after events, transfer of exposed field's values
//
function beforeDisplayAddUpdate_messageprepared($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;
	
	$strSQL = "select fromdomain, sender, sendername, recipients, subject, message, messagehtml, priority, retries, sent, modifydatetime from ~TABLENAMEMESSAGEPREPARED~ where id = ~ID~";
	$strSQL = str_replace('~TABLENAMEMESSAGEPREPARED~', CORE_MESSAGEPREPARED, $strSQL);
	$strSQL = str_replace("~ID~", ff($strFormDataID_a), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    if ($arrRow = dbReadRecord($objResult))
    {
		$strRecipients = $arrRow["recipients"];
		$strRecipients = str_replace('[{"recipient":"', "", $strRecipients);
		$strRecipients = str_replace('"}]', "", $strRecipients);
		
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g088d79ab-f1bf-4349-8abd-c8c1749d6f6a", "FROMDOMAIN", $arrRow["fromdomain"]);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g088d79ab-f1bf-4349-8abd-c8c1749d6f6a", "SENDER", $arrRow["sender"]);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g088d79ab-f1bf-4349-8abd-c8c1749d6f6a", "SENDERNAME", $arrRow["sendername"]);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g088d79ab-f1bf-4349-8abd-c8c1749d6f6a", "RECIPIENTS", $strRecipients);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g088d79ab-f1bf-4349-8abd-c8c1749d6f6a", "SUBJECT", $arrRow["subject"]);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g088d79ab-f1bf-4349-8abd-c8c1749d6f6a", "MESSAGE", $arrRow["message"]);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g088d79ab-f1bf-4349-8abd-c8c1749d6f6a", "MESSAGEHTML", $arrRow["messagehtml"]);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g088d79ab-f1bf-4349-8abd-c8c1749d6f6a", "PRIORITY", $arrRow["priority"]);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g088d79ab-f1bf-4349-8abd-c8c1749d6f6a", "RETRIES", $arrRow["retries"]);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g088d79ab-f1bf-4349-8abd-c8c1749d6f6a", "ISSENT", $arrRow["sent"]);
	}
	dbCloseRecordset($objResult);

	return $arrJSONData;
}

function beforeAddUpdate_messageprepared($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_messageprepared($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_messageprepared($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_messageprepared($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{
}

function afterDelete_messageprepared($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a)
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_messageprepared($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
}
