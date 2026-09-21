<?php

function personGroupAddPerson($objConn_a, $strClientID_a, $strPersonGroupID_a, $strPersonID_a, $strPersonName_a, $strEmailAddress_a)
{
    $strTableNamePersonGroupPerson = getTableNameEntity('persongroup_person', false);

    // initialisations
    $strLogin = $_SESSION['server_loggedin_user'];

	// add them to the group
	$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "PERSONGROUP_PERSON");

	dbBeginTrans($objConn_a, __FUNCTION__);
	
	// get the entity ids
	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, "persongroup_person");
	
	$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "g9c5f214c-6f8e-4813-8647-b2845ec1cf20", "PERSON", $strPersonID_a, $strPersonName_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g9c5f214c-6f8e-4813-8647-b2845ec1cf20", "EMAILADDRESS", $strEmailAddress_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g9c5f214c-6f8e-4813-8647-b2845ec1cf20", "PROCESSED", "N");
	$strJSONData = json_encode($arrJSONData);

	$strSQL = "insert into ~TABLENAMEPERSONGROUPPERSON~ (client_id, entity_id, dataentity_id, code, description, is_enabled, jsondata, data_client_id, persongroup_id, modifyuser, modifydatetime)
			   values (~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, 'PERSONGROUP_PERSON', 'Person Group Person', 'Y', '~JSONDATA~', ~DATACLIENTID~, ~PERSONGROUPID~, '~MODIFYUSER~', '~MODIFYDATETIME~')";
	$strSQL = str_replace('~TABLENAMEPERSONGROUPPERSON~', ff($strTableNamePersonGroupPerson), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
	$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
	$strSQL = str_replace('~DATACLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~PERSONGROUPID~', ff($strPersonGroupID_a), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	$strResult = dbLastInsertID($objConn_a, __FUNCTION__);

	exposeEntityData($objConn_a, 'SYSTEMFORM', 'PERSONGROUP_PERSON', $strResult, $strJSONData);

    $blnX = dbEndTrans($objConn_a, __FUNCTION__);
	
	return $strResult;
}