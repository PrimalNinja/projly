<?php

/**
 * clientInitialise creates client from registrationForm (accountAddFromRegistrationForm)
 * install default profile then call profileinitialise to install related client profiles
 * see profileInitialise for more info
 */
function clientInitialise($objConn_a, $blnCreateClient_a, $strClientID_a, $strClientCode_a, $strClientDescription_a, $strClientEmailAddress_a, $strClientPhoneNumber_a, $strClientEnabled_a, $strUserDescription_a, $strUserEmailAddress_a, $strUserLogin_a, $strUserPassword_a, $strRegistrationTypeID_a, $strRegistrationFormJSON_a)
{	
	$strTableNamePermission = getTableNameEntity("permission", false);
	$strTableNameProfile = getTableNameEntity("profile", false);
	$strTableNameProfilePermission = getTableNameEntity("profile_permission", false);
	$strTableNameRegistrationType = getTableNameEntity("registrationtype", false);
	$strTableNameUserStatus = getTableNameEntity("userstatus", false);

	$arrRegistrationFormJSON = array();
	if (strlen($strRegistrationFormJSON_a) > 0)
	{
		$arrRegistrationFormJSON = json_decode($strRegistrationFormJSON_a, true);
	}

	$strAccountID = "";
    $strClientID = $strClientID_a;
	$strUserID = "";
	$strRegistrationTypeID = $strRegistrationTypeID_a;

    if (dependencies('security/accountAddFromRegistrationForm,security/clientAdd,security/clientCodeUniquify,security/fixClientAndBatchAfterInsert,security/profilesInitialise,security/userAdd,setting/userSettingAdd,setting/userSettingDefault,security/salesPersonCodeLog,setting/clientSettingAdd')) {
        dbBeginTrans($objConn_a, __FUNCTION__);

		// start of logic
        $strSystemClientID = getSystemClientID($objConn_a);

        $strLogin = $_SESSION['server_loggedin_user'];
		
		if (strlen($strRegistrationTypeID) == 0)
		{
			$strSQL = "select id returnvalue from ~TABLENAMEREGISTRATIONTYPE~ where code = '~DEFAULT_REGISTRATIONTYPE~'";
			$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);	
			$strSQL = str_replace('~DEFAULT_REGISTRATIONTYPE~', ff(DEFAULT_REGISTRATIONTYPE), $strSQL);	
			$strRegistrationTypeID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		}
		
		if ($blnCreateClient_a)
		{
			// prepare details for new client
			$strClientCode = clientCodeUniquify($objConn_a, $strClientCode_a);
		}
		else
		{
			$strClientCode = $strClientCode_a;
		}

		// there are 2 ways to create a client, one is via the entity forms, that has an entity record created already before calling this initialise function
		// the other is with the registration confirmation, that needs an actual client record created as part of this
		$strAccountID = accountAddFromRegistrationForm($objConn_a, $strClientCode, $strClientDescription_a, "Y", $strClientEmailAddress_a, $strClientPhoneNumber_a, $strRegistrationTypeID, $arrRegistrationFormJSON);

		if ($blnCreateClient_a)
		{
			// add a new client
			$strClientID = clientAdd($objConn_a, $strClientCode, $strClientDescription_a, $strUserLogin_a, $strClientEmailAddress_a, $strClientPhoneNumber_a, $strClientEnabled_a);
			salesPersonCodeLog($objConn_a, $strClientID, $arrRegistrationFormJSON);

			//$strSystemOwnerClientID = getSystemOwnerClientID($objConn_a);
		}

		$strSQL = "select id returnvalue from ~TABLENAMEUSERSTATUS~ where code = '" . DEFAULT_REGISTRATIONTYPE . "'";
		$strSQL = str_replace('~TABLENAMEUSERSTATUS~', ff($strTableNameUserStatus), $strSQL);
		$strDefaultUserStatusID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        $strCode = '';
        $strUserID = userAdd($objConn_a, $strClientID, $strDefaultUserStatusID, $strCode, $strUserDescription_a, $strUserEmailAddress_a, $strUserLogin_a, $strUserPassword_a, 'Y', 'N', false);

		// add the client account
		fixClientAndBatchAfterInsert($objConn_a, $strAccountID, $strClientID, $strUserID);

		profilesInitialise($objConn_a, $strClientID);
		clientSettingAdd($objConn_a, $strClientID);

		$strUserSettingID = userSettingAdd($objConn_a, $strClientID, $strUserID);
		
        userSettingDefault($objConn_a, $strClientID, $strUserID, $strUserSettingID);
		
        dbEndTrans($objConn_a, __FUNCTION__);
    }

    return $strClientID;
}
