<?php

function permissionAdd($objConn_a, $strClientID_a, $strEntityID_a, $strCode_a, $strDescription_a, $strIsEnabled_a, $strIsSysAdmin_a, $strIsNonSysAdmin_a, $strIsLicensed_a, $strIsEntityCreated_a, $strCategoryCode_a)
{	
	$strTableNamePermission = getTableNameEntity("permission", false);
	$strTableNamePermissionCategory = getTableNameEntity("permissioncategory", false);

	$strResult = '';
	
	$strLogin = $_SESSION['server_loggedin_user'];
		
	$strCategoryID = "";
	$strCategoryDesc = "";

	$strSQL = "select id, description from ~TABLENAMEPERMISSIONCATEGORY~ where code = '~CATEGORYCODE~'";
	$strSQL = str_replace("~TABLENAMEPERMISSIONCATEGORY~", ff($strTableNamePermissionCategory), $strSQL);
	$strSQL = str_replace("~CATEGORYCODE~", ff($strCategoryCode_a), $strSQL);	
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	if ($arrRow = dbReadRecord($objResult)) {
		$strCategoryID = $arrRow['id'];
		$strCategoryDesc = $arrRow['description'];
	}
	dbCloseRecordset($objResult);
	
	if (strlen($strCategoryID) > 0)
	{
		dbBeginTrans($objConn_a, __FUNCTION__);

		$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "PERMISSION");
		
		//get the entity ids
		$strEntityID = getEntityID($objConn_a, "systemform");
		$strDataEntityID = getEntityID($objConn_a, "permission");
		
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "0000eae3-e5b8-4ebb-a3a8-50220cee15d5", "CODE", $strCode_a);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "0000eae3-e5b8-4ebb-a3a8-50220cee15d5", "DESCRIPTION", $strDescription_a);		
		$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "0000eae3-e5b8-4ebb-a3a8-50220cee15d5", "PERMISSIONCATEGORY", $strCategoryID, $strCategoryDesc);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "0000eae3-e5b8-4ebb-a3a8-50220cee15d5", "ISSYSADMIN", $strIsSysAdmin_a);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "0000eae3-e5b8-4ebb-a3a8-50220cee15d5", "ISNONSYSADMIN", $strIsNonSysAdmin_a);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "0000eae3-e5b8-4ebb-a3a8-50220cee15d5", "ISLICENSED", $strIsLicensed_a);

		$strJSONData = json_encode($arrJSONData);

		$strSQL = "
insert into ~TABLENAMEPERMISSION~ (
client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, is_entitycreated
) values (
~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~', '~ISENTITYCREATED~'
)";
		$strSQL = str_replace("~TABLENAMEPERMISSION~", ff($strTableNamePermission), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
		$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
		$strSQL = str_replace('~CODE~', ff($strCode_a), $strSQL);
		$strSQL = str_replace('~DESCRIPTION~', ff($strDescription_a), $strSQL);
		$strSQL = str_replace('~ISENABLED~', ff($strIsEnabled_a), $strSQL);
		$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
		$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
		$strSQL = str_replace('~ISENTITYCREATED~', ff($strIsEntityCreated_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		$strResult = dbLastInsertID($objConn_a);
		
		exposeEntityData($objConn_a, 'SYSTEMFORM', 'PERMISSION', $strResult, $strJSONData);

		$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
	}
	
	return $strResult;
}
