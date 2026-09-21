<?php

function entityOperationAdd($objConn_a, $strClientID_a, $strEntityID_a, $strCode_a, $strDescription_a, $strPermissionDesc_a, $strCommand_a, $strParameters_a, $strFlags_a, $strPrompt_a, $strIsInternal_a, $strRequiresSelection_a, $strAllowMultiple_a, $strIsCustom_a, $strDisplayOrder_a, $strIsEnabled_a, $strNotes_a)
{
	$strTableNameEntityOperation = getTableNameEntity("entityoperation", false);

	$strLogin = $_SESSION['server_loggedin_user'];
	
	dbBeginTrans($objConn_a, __FUNCTION__);

	$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "ENTITYOPERATION");
	
	//get the entity ids
	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, "entityoperation");

	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "CODE", $strCode_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "DESCRIPTION", $strDescription_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "PERMISSIONDESC", $strPermissionDesc_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "COMMAND", $strCommand_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "PARAMETERS", $strParameters_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "FLAGS", $strFlags_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "PROMPT", $strPrompt_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "ISINTERNAL", $strIsInternal_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "REQUIRESSELECTION", $strRequiresSelection_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "ALLOWMULTIPLE", $strAllowMultiple_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "ISCUSTOM", $strIsCustom_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "DISPLAYORDER", $strDisplayOrder_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "ISENABLED", $strIsEnabled_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff19a43370-beae-4bbf-aab7-f1c1ce50c50a", "NOTES", $strNotes_a);

	$strJSONData = json_encode($arrJSONData);

	$strSQL = "
insert into ~TABLENAMEENTITYOPERATION~ (
client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, relatedentity_id
) values (
~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~', ~RELATEDENTITYID~
)";
	$strSQL = str_replace("~TABLENAMEENTITYOPERATION~", ff($strTableNameEntityOperation), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
	$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strCode_a), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strDescription_a), $strSQL);
	$strSQL = str_replace('~ISENABLED~', ff($strIsEnabled_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	$strSQL = str_replace('~RELATEDENTITYID~', ff($strEntityID_a), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	$strResult = dbLastInsertID($objConn_a);
	
	exposeEntityData($objConn_a, 'SYSTEMFORM', 'ENTITYOPERATION', $strResult, $strJSONData);

	return dbEndTrans($objConn_a, __FUNCTION__);
}
