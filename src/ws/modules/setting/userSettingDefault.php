<?php

// id and user_id are the same as the user entity table's id
function userSettingDefault($objConn_a, $strClientID_a, $strUserID_a, $strUserSettingID_a)
{
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNameRegistrationType = getTableNameEntity("registrationtype", false);
	$strTableNameUser = getTableNameEntity("user", false);
	$strTableNameUserSetting = getTableNameEntity("usersetting", false);
	
	$blnIsEmployer = false;
	$blnIsIndividual = false;

	// find out what type of profile the customer registered as
	$strSQL = "select rt.code returnvalue from ~TABLENAMEREGISTRATIONTYPE~ rt, ~TABLENAMEACCOUNT~ a where rt.id = a.registrationtype_id and a.client_id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strRegistrationAccountSubType = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	if (strlen($strRegistrationAccountSubType) > 0)
	{
		if (!$blnIsEmployer && !$blnIsIndividual)
		{
			$strSQL = "select ff4909db1e_8bee_4c2b_be10_6d0853789635_isemployer returnvalue from ~TABLENAMEREGISTRATIONTYPE~ where code = '~REGISTRATIONTYPECODE~'";
			$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);
			$strSQL = str_replace('~REGISTRATIONTYPECODE~', ff($strRegistrationAccountSubType), $strSQL);
			$blnIsEmployer = toBoolean(dbReadValue($objConn_a, $strSQL, __FUNCTION__));
		}
		
		if (!$blnIsEmployer)
		{
			$strSQL = "select ff4909db1e_8bee_4c2b_be10_6d0853789635_isindividual returnvalue from ~TABLENAMEREGISTRATIONTYPE~ where code = '~REGISTRATIONTYPECODE~'";
			$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);
			$strSQL = str_replace('~REGISTRATIONTYPECODE~', ff($strRegistrationAccountSubType), $strSQL);
			$blnIsIndividual = toBoolean(dbReadValue($objConn_a, $strSQL, __FUNCTION__));
		}
	}

	dbBeginTrans($objConn_a, __FUNCTION__);

	$strSQL = "select jsondata returnvalue from ~TABLENAMEUSERSETTING~ where id = ~USERSETTINGID~";
	$strSQL = str_replace('~TABLENAMEUSERSETTING~', ff($strTableNameUserSetting), $strSQL);
	$strSQL = str_replace('~USERSETTINGID~', ff($strUserSettingID_a), $strSQL);
	$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	$arrJSONData = json_decode($strJSONData, true);
	
	// If the user is being registered or created as an individual, default them to SDI mode or else set their setting to MDI as default
	if ($blnIsIndividual == true)
	{
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "GENERALSETTINGS", "MDI", "N");
	}
	else
	{	
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "GENERALSETTINGS", "MDI", "Y");
	}
	
	$strJSONData = json_encode($arrJSONData);

	$strSQL = "update ~TABLENAMEUSERSETTING~ set jsondata = '~JSONDATA~' where client_id = ~CLIENTID~ and user_id = ~USERID~";
	$strSQL = str_replace('~TABLENAMEUSERSETTING~', ff($strTableNameUserSetting), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	
	exposeEntityData($objConn_a, 'SYSTEMFORM', 'USERSETTING', $strUserSettingID_a, $strJSONData);
		
	dbEndTrans($objConn_a, __FUNCTION__);
}
