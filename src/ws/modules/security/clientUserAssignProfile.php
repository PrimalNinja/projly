<?php

// assign a profile to the account's primary user
function clientUserAssignProfile($objConn_a, $strClientID_a, $strProfileID_a)
{
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNameProfile = getTableNameEntity("profile", false);
	$strTableNameUserProfile = getTableNameEntity("user_profile", false);

    $strUserProfileID = "";

	$strSQL = "select user_id returnvalue from ~TABLENAMEACCOUNT~ where client_id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	dbBeginTrans($objConn_a, __FUNCTION__);

	$strLogin = $_SESSION['server_loggedin_user'];


	$strSQL = "select id, code, description from ~TABLENAMEPROFILE~ where id = ~PROFILEID~";
	$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
	$strSQL = str_replace('~PROFILEID~', ff($strProfileID_a), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	if ($arrRow = dbReadRecord($objResult)) 
	{
		$strProfileID = $arrRow['id'];
		$strCode = $arrRow['code'];
		$strDescription = $arrRow['description'];

		// get the entity ids
		$strEntityID = getEntityID($objConn_a, "systemform");
		$strDataEntityID = getEntityID($objConn_a, "user_profile");
	
		$strSQL = "select id returnvalue from ~TABLENAMEUSERPROFILE~ where client_id = ~CLIENTID~ and user_id = ~USERID~ and profile_id = ~PROFILEID~";
		$strSQL = str_replace('~TABLENAMEUSERPROFILE~', ff($strTableNameUserProfile), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
		$strSQL = str_replace('~PROFILEID~', ff($strProfileID), $strSQL);
		$strUserProfileID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
		if (strlen($strUserProfileID) == 0)
		{
			$strSQL =
				"
		insert into ~TABLENAMEUSERPROFILE~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, modifyuser, modifydatetime, user_id, profile_id)
		values (~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', 'Y', ~DATACLIENTID~, '~MODIFYUSER~', '~MODIFYDATETIME~', ~USERID~, ~PROFILEID~)
		";
			$strSQL = str_replace('~TABLENAMEUSERPROFILE~', ff($strTableNameUserProfile), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
			$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
			$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
			$strSQL = str_replace('~CODE~', ff($strCode), $strSQL);
			$strSQL = str_replace('~DESCRIPTION~', ff($strDescription), $strSQL);
			$strSQL = str_replace('~DATACLIENTID~', ff($strClientID_a), $strSQL);
			$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
			$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
			$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
			$strSQL = str_replace('~PROFILEID~', ff($strProfileID), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			$strUserProfileID = dbLastInsertID($objConn_a);
		}
	}
	dbCloseRecordset($objResult);

	dbEndTrans($objConn_a, __FUNCTION__);

    return $strUserProfileID;
}
