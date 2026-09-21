<?php

// create a persongroup based on the date and the product code / name
function personGroupCreate($objConn_a, $strClientID_a, $strEmailAddress_a, $strGroupCode_a, $strGroupName_a) 
{ 
	//$strTableNamePerson = getTableNameEntity("person", false);
	$strTableNamePersonGroup = getTableNameEntity("persongroup", false);
	//$strTableNamePersonGroupPerson = getTableNameEntity("persongroup_person", false);
	//$strTableNamePersonProduct = getTableNameEntity("personproduct", false);
	
	$strResult = '';
	$strLogin = $_SESSION['server_loggedin_user'];
	
	$strGroupCode = $strGroupCode_a;
	$strGroupName = $strGroupName_a;
	
	if ((strlen($strEmailAddress_a) > 0) && filter_var($strEmailAddress_a, FILTER_VALIDATE_EMAIL))
	{
		$strGroupCode .= '_EMAIL';
		$strGroupName .= ' EMAIL';
	}
	else
	{
		$strGroupCode .= '_POSTAL';
		$strGroupName .= ' POSTAL';
	}
	
	// find the group
	$strSQL = "select id returnvalue from ~TABLENAMEPERSONGROUP~ where client_id = ~CLIENTID~ and code = '~PERSONGROUPCODE~'";
	$strSQL = str_replace('~TABLENAMEPERSONGROUP~', ff($strTableNamePersonGroup), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~PERSONGROUPCODE~', ff($strGroupCode), $strSQL);
	$strResult = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	if (strlen($strResult) == 0)
	{
		// if group doesn't exist, create it
		$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "PERSONGROUP");

		// get the entity ids
		$strEntityID = getEntityID($objConn_a, "systemform");
		$strDataEntityID = getEntityID($objConn_a, "persongroup");

		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g9c5f214c-6f8e-4813-8647-b2845ec1cf20", "CODE", $strGroupCode);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g9c5f214c-6f8e-4813-8647-b2845ec1cf20", "DESCRIPTION", $strGroupName);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g9c5f214c-6f8e-4813-8647-b2845ec1cf20", "ISENABLED", "Y");
		$strJSONData = json_encode($arrJSONData);

		dbBeginTrans($objConn_a, __FUNCTION__);

		$strSQL =
			"
		insert into ~TABLENAMEPERSONGROUP~ (client_id, entity_id, dataentity_id, code, description, is_enabled, jsondata, data_client_id, modifyuser, modifydatetime)
		values (~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', 'Y', '~JSONDATA~', ~DATACLIENTID~,'~MODIFYUSER~', '~MODIFYDATETIME~')
		";

		$strSQL = str_replace('~TABLENAMEPERSONGROUP~', ff($strTableNamePersonGroup), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
		$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
		$strSQL = str_replace('~DATACLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~CODE~', ff($strGroupCode), $strSQL);
		$strSQL = str_replace('~DESCRIPTION~', ff($strGroupName), $strSQL);
		$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
		$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		$strResult = dbLastInsertID($objConn_a);

		exposeEntityData($objConn_a, 'SYSTEMFORM', 'PERSONGROUP', $strResult, $strJSONData);

		$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
	}
	
	return $strResult;
}
