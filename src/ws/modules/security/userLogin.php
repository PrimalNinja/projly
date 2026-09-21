<?php

function userLogin($objConn_a, $strSecurityToken_a, $strDeviceIDCookie_a, $strClientCode_a, $strLogin_a, $strPassword_a, $strStayLoggedIn_a, $strUserAgent_a, $strIPAddress_a, $strCapabilities_a, $blnCookieLogin_a, $strClientDB_a, $blnLoginAs_a, $strLoginAs_a)
{
	global $strGlobalDevicePushToken;
	
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNameBranch = getTableNameEntity("branch", false);
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameRegistrationType = getTableNameEntity("registrationtype", false);
	$strTableNameUser = getTableNameEntity("user", false);
		
    $blnResult = false;
	$blnUpdatePassword = false;
	$strClientID = '';
	$strDeviceID = '';
    $strNote = 'Incorrect Password';

    if (dependencies('esb/esbBroadcast') &&
		dependencies('security/deviceAuthenticate,security/deviceRegister,security/trackDevice,security/trackDeviceFailure') &&
		dependencies('security/userPasswordUpdate,security/userChecksumCalculateByLogin,setting/clientSettingAdd,setting/settingGet'))
	{
		
        // parameters
        $strClientCode = $strClientCode_a;
        $strLogin = $strLogin_a;
        $strPassword = $strPassword_a;
        $strUserAgent = $strUserAgent_a;
		$strIPAddress = $strIPAddress_a;
		$strCapabilities = $strCapabilities_a;
		$strUserID = "";

        // check if our client code is an email address
        if ((Instr($strClientCode, "@") >= 0) && ENABLE_REGISTER) 
		{
            // if it is, try find our client code by email addess
            $strSQL =
                "
select c.code returnvalue
from ~TABLENAMECLIENT~ c, ~TABLENAMEUSER~ u
where
c.email_address = '~EMAILADDRESS~' and
c.is_enabled = 'Y' and
u.client_id = c.id and
u.is_enabled = 'Y' and
u.login = '~LOGIN~'
";
			$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
			$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
            $strSQL = str_replace('~EMAILADDRESS~', ff($strClientCode), $strSQL);
            $strSQL = str_replace('~LOGIN~', ff($strLogin), $strSQL);
            $strClientCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
        }

        // clientid
        $strSQL =
            "
select c.id returnvalue
from ~TABLENAMECLIENT~ c, ~TABLENAMEUSER~ u
where
c.code = '~CLIENTCODE~' and
c.is_enabled = 'Y' and
u.client_id = c.id and
u.is_enabled = 'Y' and
u.login = '~LOGIN~'
";
		$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
		$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
        $strSQL = str_replace('~CLIENTCODE~', ff($strClientCode), $strSQL);
        $strSQL = str_replace('~LOGIN~', ff($strLogin), $strSQL);
        $strClientID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        if ($blnCookieLogin_a == true) 
		{
            // cookie version where the password isn't required as the user is already authenticated using the computer
            $strSQL =
                "
		select distinct u.id returnvalue
		from ~TABLENAMECLIENT~ c, ~TABLENAMEUSER~ u
		where
		c.code = '~CLIENTCODE~' and
		c.is_enabled = 'Y' and
		u.client_id = c.id and
		u.is_enabled = 'Y' and
		u.login = '~LOGIN~'
		";
			$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
			$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
            $strSQL = str_replace('~CLIENTCODE~', ff($strClientCode), $strSQL);
            $strSQL = str_replace('~LOGIN~', ff($strLogin), $strSQL);
            $strUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
        } 
		else 
		{
            // non-cookie version where the password must be provided (might be LDAP or NOT)
			if ((ff($strClientCode) == LDAP_AUTHENTICE_CLIENTCODE) && (toBoolean(LDAP_AUTHENTICE_USERS)))
			{
				if (toBoolean(LDAP_INITIALISE_USERS))
				{
					initUsersBasedOnLDAP($objConn_a, $strSecurityToken_a, $strClientID, $strLogin);
				}
				
				if (toBoolean(LDAP_INITIALISE_PROFILES))
				{
					initProfilesBasedOnLDAP($objConn_a, $strSecurityToken_a, $strClientID, $strLogin);
				}
				
				$strUserID = authenticateUserBasedOnLDAP($objConn_a, $strClientID, $strLogin, $strPassword);
			}
			else
			{
				if (strlen($strClientID) > 0) {
					$strSQL = "select id returnvalue from ~TABLENAMEUSER~ where client_id = ~CLIENTID~ and login = '~LOGIN~'";
					$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
					$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
					$strSQL = str_replace('~LOGIN~', ff($strLogin), $strSQL);
					$strUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
					
					$strChecksum = userChecksumCalculateByLogin($objConn_a, $strClientID, $strLogin);
//debug($strChecksum);
					// try get the userid with an encrypted password
					$strSQL =
						"
			select distinct u.id returnvalue
			from ~TABLENAMECLIENT~ c, ~TABLENAMEUSER~ u
			where
			c.code = '~CLIENTCODE~' and
			c.is_enabled = 'Y' and
			u.client_id = c.id and
			u.is_enabled = 'Y' and
			u.login = '~LOGIN~' and
			u.password = '~PASSWORD~' and
			u.checksum = '~CHECKSUM~'
			";
					$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
					$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
					$strSQL = str_replace('~CLIENTCODE~', ff($strClientCode), $strSQL);
					$strSQL = str_replace('~LOGIN~', ff($strLogin), $strSQL);
//debug($strClientID . ":" . $strUserID . ":" . $strPassword);
					$strSQL = str_replace('~PASSWORD~', ff(encryptPassword1Way($strClientID, $strUserID, strtolower($strPassword))), $strSQL);
					$strSQL = str_replace('~CHECKSUM~', ff($strChecksum), $strSQL);
					$strUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	//debug($strSQL);
					if ((toBoolean(ALLOWUNENCRYPTEDINDB)) && (strlen($strUserID) == 0)) {
						// try again with an unencrypted password (in the database)

						$strSQL =
							"
			select distinct u.id returnvalue
			from ~TABLENAMECLIENT~ c, ~TABLENAMEUSER~ u
			where
			c.code = '~CLIENTCODE~' and
			c.is_enabled = 'Y' and
			u.client_id = c.id and
			u.is_enabled = 'Y' and
			u.login = '~LOGIN~' and
			u.password = '~PASSWORD~'
			";
						$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
						$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
						$strSQL = str_replace('~CLIENTCODE~', ff($strClientCode), $strSQL);
						$strSQL = str_replace('~LOGIN~', ff($strLogin), $strSQL);
						$strSQL = str_replace('~PASSWORD~', ff($strPassword), $strSQL);
						$strUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
						$blnUpdatePassword = true;
					}
				}
			}
        }

        if ((strlen($strClientID) > 0) && (strlen($strUserID) > 0)) 
		{
			if ($blnUpdatePassword)
			{
				userPasswordUpdate($objConn_a, $strClientID, $strUserID, $strPassword);
			}
			
            $strSQL = "select email_address returnvalue from ~TABLENAMECLIENT~ where id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
            $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
            $strEmailAddress = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "select id returnvalue from ~TABLENAMEACCOUNT~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
            $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
			$strAccountID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

			$strSQL = "select registrationtype_id returnvalue from ~TABLENAMEACCOUNT~ where id = ~ACCOUNTID~";
			$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
            $strSQL = str_replace('~ACCOUNTID~', ff($strAccountID), $strSQL);
			$strRegistrationTypeID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

			$blnIsEmployer = false;
			$blnIsIndividual = false;

			if (strlen($strRegistrationTypeID) > 0)
			{
				$strSQL = "select ff4909db1e_8bee_4c2b_be10_6d0853789635_isemployer returnvalue from ~TABLENAMEREGISTRATIONTYPE~ where id = ~REGISTRATIONTYPEID~";
				$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);	
				$strSQL = str_replace('~REGISTRATIONTYPEID~', ff($strRegistrationTypeID), $strSQL);
				$blnIsEmployer = toBoolean(dbReadValue($objConn_a, $strSQL, __FUNCTION__));

				$strSQL = "select ff4909db1e_8bee_4c2b_be10_6d0853789635_isindividual returnvalue from ~TABLENAMEREGISTRATIONTYPE~ where id = ~REGISTRATIONTYPEID~";
				$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);	
				$strSQL = str_replace('~REGISTRATIONTYPEID~', ff($strRegistrationTypeID), $strSQL);
				$blnIsIndividual = toBoolean(dbReadValue($objConn_a, $strSQL, __FUNCTION__));
			}
			else
			{
				// add a default account for the client
				$strSQL = "select id returnvalue from ~TABLENAMEREGISTRATIONTYPE~ where code = '~REGISTRATIONTYPECODE~'";
				$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);	
				$strSQL = str_replace('~REGISTRATIONTYPECODE~', ff(DEFAULT_REGISTRATIONTYPE), $strSQL);
				$strRegistrationTypeID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			}
			
			$blnIsPublic = ((strtolower($strClientCode) == strtolower(PUBLIC_CLIENT)) && (strtolower($strLogin) == strtolower(PUBLIC_LOGIN))); // this one doesn't let the actual public user login;
			$blnLoginAs = $blnLoginAs_a;

			$strLoginAs = $strLoginAs_a;
			if ($blnIsPublic)
			{
				$strLoginAs = 'Public';
			}
			
			// read any default settings
			$strEnableBranches = settingGet($objConn_a, 'CORE', 'ENABLEBRANCHES', $strClientID, '', '', '', '', __FUNCTION__);
			$strBranchID = "";
			$strBranchName = "";
			
			if (toBoolean($strEnableBranches))
			{
				$strBranchID = settingGet($objConn_a, 'CORE', 'BRANCHID', $strClientID, $strUserID, '', '', '', __FUNCTION__);
				$strBranchName = dbGetDescriptionFromID($objConn_a, $strTableNameBranch, $strBranchID, __FUNCTION__);

				// invalidate the branch if it is not a valid branch
				if (strlen($strBranchName) == 0)
				{
					$strBranchID = '';
				}
			}
			
			// setup session
			$_SESSION['server_clientdb'] = $strClientDB_a;
			$_SESSION['server_loginas'] = $strLoginAs; 
			$_SESSION['server_deviceid'] = '';
            $_SESSION['server_loggedin'] = true;
            $_SESSION['server_loggedin_accountid'] = $strAccountID;
            $_SESSION['server_loggedin_agent'] = $strUserAgent_a;
            $_SESSION['server_loggedin_batch'] = (strtolower($strClientCode) == strtolower(BATCH_CLIENT));
            $_SESSION['server_loggedin_client'] = $strClientCode;
            $_SESSION['server_loggedin_clientid'] = $strClientID;
            $_SESSION['server_loggedin_cookie'] = $strDeviceIDCookie_a;
			$_SESSION['server_loggedin_devicepushtoken'] = $strGlobalDevicePushToken;
            $_SESSION['server_loggedin_default'] = (strtolower($strClientCode) == strtolower(DEFAULT_CLIENT));
			$_SESSION['server_loggedin_developer'] = (strtolower($strClientCode) == strtolower(DEVELOPER_CLIENT));
            $_SESSION['server_loggedin_emailaddress'] = $strEmailAddress;
            $_SESSION['server_loggedin_ipaddress'] = $strIPAddress_a;
			$_SESSION['server_loggedin_isemployer'] = $blnIsEmployer;
			$_SESSION['server_loggedin_isindividual'] = $blnIsIndividual;
            $_SESSION['server_loggedin_public'] = $blnIsPublic;
            $_SESSION['server_loggedin_sysadmin'] = ((strtolower($strClientCode) == strtolower(SYSTEM_CLIENT)) && (strtolower($strLogin) == strtolower(SYSTEM_LOGIN)));
            $_SESSION['server_loggedin_system'] = (strtolower($strClientCode) == strtolower(SYSTEM_CLIENT));
            $_SESSION['server_loggedin_token'] = $strSecurityToken_a;
            $_SESSION['server_loggedin_user'] = $strLogin;
            $_SESSION['server_loggedin_userid'] = $strUserID;
			$_SESSION['server_loggedin_enablebranches'] = $strEnableBranches;
			$_SESSION['server_loggedin_branchid'] = $strBranchID;
			$_SESSION['server_loggedin_branchname'] = $strBranchName;
			
			$_SESSION['server_returnto_token'] = "";
			$_SESSION['server_returnto_cookie'] = "";
			$_SESSION['server_returnto_clientdb'] = "";
			$_SESSION['server_returnto_loginas'] = "";
			$_SESSION['server_returnto_client'] = "";
			$_SESSION['server_returnto_user'] = "";
			$_SESSION['server_returnto_agent'] = "";
			$_SESSION['server_returnto_ipaddress'] = "";
			
			// if ($blnLoginAs)
			// {
			// }
			// else
			// {
			// }
			
			//file_put_contents("d:\dev\session.log", " userLogin.php userLogin " . $strSecurityToken_a . ":" . $_SESSION['server_loggedin_client'] . "\n", FILE_APPEND);	// DEBUGSESSION

			if ($blnIsPublic)
			{
				// do nothing for totally public users here as it wastes our log table space
			}
			else
			{
				$strDeviceID = deviceRegister($objConn_a, $strClientID, $strUserID, $strDeviceIDCookie_a, $strDeviceIDCookie_a, $strGlobalDevicePushToken, $strIPAddress, $strUserAgent, $strCapabilities);

				$_SESSION['server_deviceid'] = $strDeviceID;

				$strNotes = 'Login Success';

				trackDevice($objConn_a, $strClientID, $strClientCode_a, $strLogin_a, $strIPAddress_a, $strUserAgent_a, $strDeviceID, $strNotes);

				if (toBoolean($strStayLoggedIn_a)) {
					deviceAuthenticate($objConn_a, $strClientID, $strUserID, $strDeviceID, $strDeviceIDCookie_a, $strGlobalDevicePushToken, 'Y');
				}
				
                clientSettingAdd($objConn_a, $strClientID);

				// if (strlen($strBranchName) > 0)
				// {
					// esbBroadcast($objConn_a, $strClientID, $strDeviceID, '', 'branchchange', $strBranchName, '', true, true);
				// }
			}

            $blnResult = true;
        }
		else
		{
			// we do want to track failed login attemps from public
			$strNotes = 'Login Failure';
			trackDeviceFailure($objConn_a, $strClientID, $strClientCode_a, $strLogin_a, $strIPAddress_a, $strUserAgent_a, $strDeviceIDCookie_a, $strNotes);
		}
    }
	
    return $blnResult;
}
