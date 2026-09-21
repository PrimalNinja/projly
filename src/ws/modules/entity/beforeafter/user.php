<?php

// $strFormDataID_a = USER

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
function beforeDisplayAddUpdate_user($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;

	if (dependencies('setting/sequenceTypeGet,setting/userCodeAllocate'))
	{
		// enable sequence for standard override and manual, disable it for others
		// default sequence for all except for manual
		$strSequenceType = sequenceTypeGet($objConn_a, '', 'CORE', 'USERID', $strClientID_a);
		if (($strSequenceType == "manual") || ($strSequenceType == "standardoverride"))
		{
			// enable field
			$arrJSONData = makeFieldReadOnlyBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "CODE", "N");
		}
		else
		{
			// disable field
			$arrJSONData = makeFieldReadOnlyBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "CODE", "Y");
		}

		if ($blnUpdate_a == false)
		{
			if ($strSequenceType != "manual")
			{
				// allocate sequence
				$strCode = userCodeAllocate($objConn_a, $strClientID_a);
				$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "CODE", $strCode);
			}
		}
	}
	
	return $arrJSONData;
}

function beforeAddUpdate_user($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function afterAddUpdate_user($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$strTableNameUser = getTableNameEntity("user", false);

	$arrJSONData = $arrJSONData_a;
		
	if (dependencies('setting/userCodeAllocate'))
	{
		$strLogin = $_SESSION['server_loggedin_user'];
		
		// bring out fields
		$strCode = formValueGetBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "CODE");
		// if (strlen($strCode) == 0)
		// {
			// $strCode = userCodeAllocate($objConn_a, $strClientID_a);
			// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "CODE", $strCode);
		// }

		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "DESCRIPTION"); 
		$strDescription = $arrJSONField['p_value'];

		//$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "ISENABLED"); 
		//$strIsEnabled = $arrJSONField['p_value'];

		//$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "USERSTATUSID"); 
		//$strUserStatusID = ''; //$arrJSONField['p_value'];	// TODO

		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "EMAILADDRESS"); 
		$strEmailAddress = $arrJSONField['p_value'];	// need to expose login as it needs to be in it's own field along with the password

		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "LOGIN"); 
		$strLogin = $arrJSONField['p_value'];	// need to expose login as it needs to be in it's own field along with the password
		
		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "PASSWORD"); 
		$strPassword = $arrJSONField['p_value'];

		// we don't want to store the password in the jsondata
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "PASSWORD", "*****");	
		
		$strJSONData = json_encode($arrJSONData);
				
		// get the table		
		$strSQL = "update ~TABLENAMEUSER~ set code = '~CODE~', login = '~LOGIN~', email_address = '~EMAILADDRESS~', jsondata = '~JSONDATA~' where id = ~USERID~";
		$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
		$strSQL = str_replace('~EMAILADDRESS~', ff($strEmailAddress), $strSQL);
		$strSQL = str_replace('~CODE~', ff($strCode), $strSQL);
		$strSQL = str_replace('~LOGIN~', ff($strLogin), $strSQL);
		$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strFormDataID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
		if ($blnUpdate_a == false)
		{
			$strSQL = "update ~TABLENAMEUSER~ set is_defined = 'Y' where id = ~ID~";
			$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
			$strSQL = str_replace('~ID~', ff($strFormDataID_a), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		}

		if ($strPassword != '*****')
		{
			$strSQL = "update ~TABLENAMEUSER~ set password = '~PASSWORD~' where id = ~USERID~";
			$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
			$strSQL = str_replace('~USERID~', ff($strFormDataID_a), $strSQL);
	//debug($strClientID_a. ":" . $strFormDataID_a . ":" . $strPassword);
			$strSQL = str_replace('~PASSWORD~', ff(encryptPassword1Way($strClientID_a, $strFormDataID_a, strtolower($strPassword))), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		}
	}

	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_user($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;

    if (dependencies('setting/userSettingAdd,setting/userSettingDefault') &&
        dependencies('print/queueAdd') &&
        dependencies('security/userChecksumUpdate'))
	{
        userChecksumUpdate($objConn_a, $strClientID_a, $strFormDataID_a);

		if ($blnUpdate_a == false)
		{		
			// create a default and public printer queues for the user
			queueAdd($objConn_a, 'DEFAULT', 'Default', $strClientID_a, $strFormDataID_a, true, false);
			queueAdd($objConn_a, 'PUBLIC', 'Public', $strClientID_a, $strFormDataID_a, false, true);
			$strUserSettingID = userSettingAdd($objConn_a, $strClientID_a, $strFormDataID_a);
			userSettingDefault($objConn_a, $strClientID_a, $strFormDataID_a, $strUserSettingID);
		}
	}
	
	return $arrJSONData;
}

function beforeDelete_user($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNameDevice = getTableNameEntity("device", false);
	$strTableNameDeviceLog = getTableNameEntity("devicelog", false);
	$strTableNameUser = getTableNameEntity("user", false);
	$strTableNameUserProfile = getTableNameEntity("user_profile", false);
	$strTableNameUserSetting = getTableNameEntity("usersetting", false);
    $strTableNamePrintQueue = getTableNameEntity("printqueue", false);
    
	$strSQL = "select is_defined returnvalue from ~TABLENAMEUSER~ where client_id = ~CLIENTID~ and id = ~USERID~";
	$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~USERID~', ff($strFormDataID_a), $strSQL);
	$blnDefined = toBoolean(dbReadValue($objConn_a, $strSQL, __FUNCTION__));

	if ($blnDefined) 
	{
		// delete the user's computers
		$strSQL = "delete from ~TABLENAMEESBBROADCASTER~ where client_id = ~CLIENTID~ and computer_id in (select id from ~TABLENAMEDEVICE~ where user_id = ~USERID~)";
		$strSQL = str_replace("~TABLENAMEESBBROADCASTER~", CORE_ESBBROADCASTER, $strSQL);
		$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strFormDataID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		$strSQL = "delete from ~TABLENAMEESBLISTENER~ where client_id = ~CLIENTID~ and computer_id in (select id from ~TABLENAMEDEVICE~ where user_id = ~USERID~)";
		$strSQL = str_replace("~TABLENAMEESBLISTENER~", CORE_ESBLISTENER, $strSQL);
		$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strFormDataID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		$strSQL = "delete from ~TABLENAMEDEVICELOG~ where client_id = ~CLIENTID~ and device_id in (select id from ~TABLENAMEDEVICE~ where user_id = ~USERID~)";
		$strSQL = str_replace('~TABLENAMEDEVICELOG~', ff($strTableNameDeviceLog), $strSQL);
		$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strFormDataID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		$strSQL = "delete from ~TABLENAMEDEVICE~ where client_id = ~CLIENTID~ and user_id = ~USERID~";
		$strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strFormDataID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		$strSQL = "delete from ~TABLENAMEPRINTQUEUE~ where client_id = ~CLIENTID~ and user_id = ~USERID~";
		$strSQL = str_replace('~TABLENAMEPRINTQUEUE~', ff($strTableNamePrintQueue), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strFormDataID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		$strSQL = "delete from ~TABLENAMEACCOUNT~ where client_id = ~CLIENTID~ and user_id = ~USERID~";
		$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strFormDataID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		$strSQL = "delete from ~TABLENAMEUSERPROFILE~ where client_id = ~CLIENTID~ and user_id = ~USERID~";
		$strSQL = str_replace('~TABLENAMEUSERPROFILE~', ff($strTableNameUserProfile), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strFormDataID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

		$strSQL = "delete from ~TABLENAMEUSERSETTING~ where client_id = ~CLIENTID~ and user_id = ~USERID~";
		$strSQL = str_replace('~TABLENAMEUSERSETTING~', ff($strTableNameUserSetting), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strFormDataID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
		$strSQL = "delete from ~TABLENAMEUSER~ where client_id = ~CLIENTID~ and id = ~USERID~";
		$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strFormDataID_a), $strSQL);
		dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	}
}

function afterDelete_user($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_user($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
	// $strTableNameUser = getTableNameEntity("user", false);

	// $strSQL = "select id from ~TABLENAMEUSER~ where jsondata is null";
	// $strSQL = str_replace("~TABLENAMEUSER~", ff($strTableNameUser), $strSQL);
	// $objResultLoop = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	// while ($arrRowLoop = dbReadRecord($objResultLoop)) {
		// $strUserID = $arrRowLoop['id'];

		// $strCode = "";
		// $strDescription = "";
		// $strIsEnabled = "";
		// $strLogin = "";
		// $strPassword = "";
		// $strEmailAddress = "";

		// $strSQL = "select code, description, is_enabled, login, email_address from ~TABLENAMEUSER~ where id = ~USERID~";
		// $strSQL = str_replace("~TABLENAMEUSER~", ff($strTableNameUser), $strSQL);
		// $strSQL = str_replace("~USERID~", ff($strUserID), $strSQL);
		// $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		// if ($arrRow = dbReadRecord($objResult)) {
			// $strCode = $arrRow['code'];
			// $strDescription = $arrRow['description'];
			// $strIsEnabled = $arrRow['is_enabled'];
			// $strLogin = $arrRow['login'];
			// $strPassword = '*****';
			// $strEmailAddress = $arrRow['email_address'];
		// }
		// dbCloseRecordset($objResult);

		// $arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "USER");
		
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "CODE", $strCode);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "DESCRIPTION", $strDescription);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "ISENABLED", $strIsEnabled);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "LOGIN", $strLogin);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "PASSWORD", $strPassword);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "1d3fbeab-d440-4329-9b9c-aa93f6fe7c16", "EMAILADDRESS", $strEmailAddress);

		// $strJSONData = json_encode($arrJSONData);

		// dbBeginTrans($objConn_a, __FUNCTION__);

		// $strSQL = "update ~TABLENAMEUSER~ set jsondata = '~JSONDATA~' where id = ~USERID~";
		// $strSQL = str_replace("~TABLENAMEUSER~", ff($strTableNameUser), $strSQL);
		// $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		// $strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
		// dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
		// exposeEntityData($objConn_a, 'SYSTEMFORM', 'USER', $strUserID, $strJSONData);
		
		// dbEndTrans($objConn_a, __FUNCTION__);
	// }
	// dbCloseRecordset($objResultLoop);
	
	// $strTableNameUser = getTableNameEntity("user", false);
	// $strTableNameUserSetting = getTableNameEntity("usersetting", false);

	// $strLogin = $_SESSION['server_loggedin_user'];
	
	// $strSQL = "select id, client_id, jsondata from ~TABLENAMEUSER~";
	// $strSQL = str_replace("~TABLENAMEUSER~", ff($strTableNameUser), $strSQL);
	// $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	// while ($arrRow = dbReadRecord($objResult)) {
		// $strUserID = $arrRow['id'];	// usersettingid and userid are the same value
		// $strClientID = $arrRow['client_id'];
		
		// $arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "USERSETTING");
		
		// $strEntityID = getEntityID($objConn_a, "systemform");
		// $strDataEntityID = getEntityID($objConn_a, "usersetting");

		// $strJSONData = json_encode($arrRow['jsondata']);

		// $strSQL =
			// "
	// insert into ~TABLENAMEUSERSETTING~ (id, client_id, entity_id, dataentity_id, code, description, is_enabled, jsondata, data_client_id, modifyuser, modifydatetime, user_id)
	// values (~USERID~, ~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ENABLED~', '~JSONDATA~', ~DATACLIENTID~, '~MODIFYUSER~', '~MODIFYDATETIME~', ~USERID~)
	// ";
		// $strSQL = str_replace('~TABLENAMEUSERSETTING~', ff($strTableNameUserSetting), $strSQL);
		// $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
		// $strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
		// $strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
		// $strSQL = str_replace('~CODE~', 'USERSETTING', $strSQL);
		// $strSQL = str_replace('~DESCRIPTION~', 'USERSETTING', $strSQL);
		// $strSQL = str_replace('~ENABLED~', 'Y', $strSQL);
		// $strSQL = str_replace('~DATACLIENTID~', ff($strClientID), $strSQL);
		// $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		// $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
		// $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
		// $strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
		// dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		// $strUserID = dbLastInsertID($objConn_a);
		
		// exposeEntityData($objConn_a, 'SYSTEMFORM', 'USERSETTING', $strUserID, $strJSONData);
			
		// dbEndTrans($objConn_a, __FUNCTION__);
	// }
	// dbCloseRecordset($objResult);
}
