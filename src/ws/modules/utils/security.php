<?php

// function summary:

// allocatePasswordRandom()
// arrayKeyExistsR($strNeedle_a, $haystack)
// authenticateUserBasedOnLDAP($objConn_a, $strClientID_a, $strLogin_a, $strPassword_a)
// clearSession()
// decryptID($str_a)
// decryptLicenceKey($strProductID_a, $strInstallationID_a, $str_a)
// decryptPassword2Way($strFutureUse_a, $strLogin_a, $str_a)
// encryptID($str_a)
// encryptPassword1Way($strClientID_a, $strUserID_a, $str_a)
// encryptPassword2Way($strFutureUse_a, $strLogin_a, $str_a)
// forceLogout($strPermissionCodes_a, $strLocation_a)
// getBatchClientID($objConn_a)
// getDefaultClientID($objConn_a)
// getDeveloperClientID($objConn_a)
// getGUID()
// getPublicClientID($objConn_a)
// getSessionDB($strSource_a)
// getSystemClientID($objConn_a)
// getSystemOwnerClientID($objConn_a)
// hasAdminPermission($objConn_a, $strPermissionCode_a, $strLocation_a, $blnForceLogout_a)
// hasNonAdminPermission($objConn_a, $strPermissionCode_a, $strLocation_a, $blnForceLogout_a)
// hasPermission($objConn_a, $strPermissionCode_a, $strLocation_a, $blnForceLogout_a)
// initUsersBasedOnLDAP($objConn_a, $strSecurityToken_a, $strClientID_a, $strLogin_a)
// initProfilesBasedOnLDAP($objConn_a, $strSecurityToken_a, $strClientID_a, $strLogin_a)
// isClientReserved($strClientID_a)
// licenceCheck($objConn_a, $strProductID_a, $strInstallationID_a)
// licenceValidate($objConn_a, $strProductID_a, $strInstallationID_a, $strLicenceKey_a)
// revertSecuredValue($strSecuredValue_a, $strComment_a, $blnMandatory_a)
// revertSecuredValueList($arrSecuredValueList_a, $strComment_a, $blnMandatory_a)
// safetyDie($strReason_a)
// secureEntityValue($strEntityCode_a, $strValue_a)
// secureValue($strType_a, $strValue_a)
// translateCustomEntityIDs($objConn_a, $strOriginalValue_a, $blnSecure_a)
// updateSessionDB($strClientDB_a, $strLoginAs_a, $strSource_a)

// return a random 6 digit number between 100000 and 999999
function allocatePasswordRandom()
{
    return rand(100000,999999);
}

// check if an item exists within an array
function arrayKeyExistsR($strNeedle_a, $arrHaystack_a)
{
    $result = array_key_exists($strNeedle_a, $arrHaystack_a);
    if ($result) 
	{
        return $result;
    }

    foreach ($arrHaystack_a as $var) 
	{
        if (is_array($var)) 
		{
            $result = arrayKeyExistsR($strNeedle_a, $var);
        }
        if ($result) 
		{
            return $result;
        }
    }
	
    return $result;
}

// return the userid
function authenticateUserBasedOnLDAP($objConn_a, $strClientID_a, $strLogin_a, $strPassword_a)
{
	$strTableNameUser = getTableNameEntity("user", false);

	$strResult = "";

	if (dependencies("utils/ldap"))
	{
		$objLDAP = ldapOpen();
		$blnAuthenticated = ldapAuthenticateUser($objLDAP, $strLogin_a, $strPassword_a);
		ldapClose($objLDAP);

		if ($blnAuthenticated == true)
		{
			$strSQL = "select id returnvalue from ~TABLENAMEUSER~ where client_id = ~CLIENTID~ and login = '~LOGIN~'";
			$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
			$strSQL = str_replace('~LOGIN~', ff($strLogin_a), $strSQL);
			$strResult = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		}
	}

	return $strResult;
}

function clearSession()
{
	//file_put_contents("d:\dev\session.log", " security.php clearSession:\n", FILE_APPEND);	// DEBUGSESSION
    $_SESSION['server_clientdb'] = '';
    $_SESSION['server_loginas'] = '';
    $_SESSION['server_deviceid'] = '';
    $_SESSION['server_loggedin'] = false;
    $_SESSION['server_loggedin_accountid'] = '';
    $_SESSION['server_loggedin_agent'] = '';
    $_SESSION['server_loggedin_batch'] = false;
    $_SESSION['server_loggedin_client'] = '';
    $_SESSION['server_loggedin_clientid'] = '';
    $_SESSION['server_loggedin_cookie'] = '';
    $_SESSION['server_loggedin_default'] = false;
	$_SESSION['server_loggedin_developer'] = false;
	$_SESSION['server_loggedin_emailaddress'] = '';
    $_SESSION['server_loggedin_ipaddress'] = '';
    $_SESSION['server_loggedin_isemployer'] = false;
	$_SESSION['server_loggedin_isindividual'] = false;
    $_SESSION['server_loggedin_public'] = false;
    $_SESSION['server_loggedin_sysadmin'] = false;
    $_SESSION['server_loggedin_system'] = false;
    $_SESSION['server_loggedin_token'] = '';
    $_SESSION['server_loggedin_user'] = '';
    $_SESSION['server_loggedin_userid'] = '';
	$_SESSION['server_loggedin_branchid'] = '';
	$_SESSION['server_loggedin_branchname'] = '';

	// $_SESSION['server_returnto_token'] = "";
	// $_SESSION['server_returnto_cookie'] = "";
	// $_SESSION['server_returnto_clientdb'] = "";
	// $_SESSION['server_returnto_loginas'] = "";
	// $_SESSION['server_returnto_client'] = "";
	// $_SESSION['server_returnto_user'] = "";
	// $_SESSION['server_returnto_agent'] = "";
	// $_SESSION['server_returnto_ipaddress'] = "";	
}

// no decryption use the passthrough decryptor
function decryptID($str_a)
{
	global $g_strGlobalSessionID;

    $strResult = '';
//echo('2:' . $g_strGlobalSessionID . "<BR>");
    if (strlen(DECRYPTION_TYPE_ID2WAY) == 0) 
	{
        $strResult = $str_a;
    } 
	else 
	{
        if (dependencies('plugins/cryptplugins/' . DECRYPTION_TYPE_ID2WAY)) 
		{
            $strSalt = strtolower('S-' . $g_strGlobalSessionID);
            $strResult = $str_a;
//echo("before decrypt:" . $strSalt . " - " . $strResult . "<BR>");
			$strResult = str_replace("PLUS", "+", $strResult);
            $strResult = call_user_func(DECRYPTION_TYPE_ID2WAY, $strSalt, $strResult);
//echo("after decrypt:" . $strSalt . " - " . $strResult . "<BR>");
            if (substr($strResult, 0, 3) == 'ID-') 
			{
                $strResult = substr($strResult, -(strlen($strResult) - 3));
            } 
			else 
			{
                $strResult = '';
            }
        }
    }

    return $strResult;
}

// no decryption use the passthrough decryptor
function decryptLicenceKey($strProductID_a, $strInstallationID_a, $str_a)
{
    $strResult = '';

    if (strlen(DECRYPTION_TYPE_LICENCE2WAY) == 0) 
	{
        $strResult = $str_a;
    } 
	else 
	{
        if (dependencies('plugins/cryptplugins/' . DECRYPTION_TYPE_LICENCE2WAY)) 
		{
            $strSalt = strtolower($strProductID_a . '-' . $strInstallationID_a);

            $strResult = $str_a;
            $strResult = call_user_func(DECRYPTION_TYPE_LICENCE2WAY, $strSalt, $strResult);
        }
    }

    return $strResult;
}

// no decryption use the passthrough decryptor
function decryptPassword2Way($strFutureUse_a, $strLogin_a, $str_a)
{
    $strResult = '';

    if (strlen(DECRYPTION_TYPE_PASSWORD2WAY) == 0) 
	{
        $strResult = $str_a;
    } 
	else 
	{
        if (dependencies('plugins/cryptplugins/' . DECRYPTION_TYPE_PASSWORD2WAY)) 
		{
            $strSalt = strtolower($strFutureUse_a . '-' . $strLogin_a);

            $strResult = $str_a;
            $strResult = call_user_func(DECRYPTION_TYPE_PASSWORD2WAY, $strSalt, $strResult);
        }
    }

    return $strResult;
}

// no encryption use the passthrough encrypter
function encryptID($str_a)
{
	global $g_strGlobalSessionID;

    $strResult = '';

    if (strlen(ENCRYPTION_TYPE_ID2WAY) == 0) 
	{
        $strResult = $str_a;
    } 
	else 
	{
        if (dependencies('plugins/cryptplugins/' . ENCRYPTION_TYPE_ID2WAY)) 
		{
            $strSalt = strtolower('S-' . $g_strGlobalSessionID);

            $strResult = 'ID-' . $str_a;
//echo("before encrypt:" . $strSalt . " - " . $strResult . "<BR>");
            $strResult = call_user_func(ENCRYPTION_TYPE_ID2WAY, $strSalt, $strResult);
			$strResult = str_replace("+", "PLUS", $strResult);
//echo("after encrypt:" . $strSalt . " - " . $strResult . "<BR>");
        }
    }

    return $strResult;
}

// no encryption use the passthrough encrypter (note: if the clientid or userid changes then the password needs regenerating)
function encryptPassword1Way($strClientID_a, $strUserID_a, $str_a)
{
    $strResult = '';

    if (strlen(ENCRYPTION_TYPE_PASSWORD1WAY) == 0) 
	{
        $strResult = $str_a;
    } 
	else 
	{
        if (dependencies('plugins/cryptplugins/' . ENCRYPTION_TYPE_PASSWORD1WAY)) 
		{
            $strSalt = strtolower($strClientID_a . '-' . $strUserID_a);

            $strResult = $str_a;
            $intIterations = intval(ENCRYPTION_INTERATIONBASE, 10) + (strlen($strSalt) * intval(ENCRYPTION_INTERATIONOFFSET, 10));
            for ($intIteration = 0; $intIteration < $intIterations; $intIteration++) 
			{
                $strResult = call_user_func(ENCRYPTION_TYPE_PASSWORD1WAY, $strSalt, $strResult);
            }
        }
    }
//debug($strClientID_a . ', ' . $strUserID_a . ', ' . $str_a . ', ' . $strResult);
    return $strResult;
}

// no encryption use the passthrough encrypter
function encryptPassword2Way($strFutureUse_a, $strLogin_a, $str_a)
{
    $strResult = '';

    if (strlen(ENCRYPTION_TYPE_PASSWORD2WAY) == 0) 
	{
        $strResult = $str_a;
    } 
	else 
	{
        if (dependencies('plugins/cryptplugins/' . ENCRYPTION_TYPE_PASSWORD2WAY)) 
		{
            $strSalt = strtolower($strFutureUse_a . '-' . $strLogin_a);

            $strResult = $str_a;
            $strResult = call_user_func(ENCRYPTION_TYPE_PASSWORD2WAY, $strSalt, $strResult);
        }
    }

    return $strResult;
}

// force a logout
function forceLogout($strPermissionCodes_a, $strLocation_a)
{
    logDebug('FORCED LOGOUT due to ' . $strPermissionCodes_a . ' in function ' . $strLocation_a, '');
	logSecurity('FORCED LOGOUT due to ' . $strPermissionCodes_a . ' in function ' . $strLocation_a, '');

	//file_put_contents("d:\dev\session.log", " security.php forceLogout:\n", FILE_APPEND);	// DEBUGSESSION
	clearSession();

    return createJSONResponse('', RESPONSE_FORCEDLOGOUT, MSG_FORCEDLOGOUT, array());
}

function getBatchClientID($objConn_a)
{
	$strTableNameClient = getTableNameEntity("client", false);

	$strSQL = "select id returnvalue from ~TABLENAMECLIENT~ where code = '~CLIENTCODE~'";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~CLIENTCODE~', BATCH_CLIENT, $strSQL);
	return dbReadValue($objConn_a, $strSQL, __FUNCTION__);
}

function getDefaultClientID($objConn_a)
{
	$strTableNameClient = getTableNameEntity("client", false);

	$strSQL = "select id returnvalue from ~TABLENAMECLIENT~ where code = '~CLIENTCODE~'";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~CLIENTCODE~', DEFAULT_CLIENT, $strSQL);
	return dbReadValue($objConn_a, $strSQL, __FUNCTION__);
}

function getDeveloperClientID($objConn_a)
{
	$strTableNameClient = getTableNameEntity("client", false);

	$strSQL = "select id returnvalue from ~TABLENAMECLIENT~ where code = '~CLIENTCODE~'";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~CLIENTCODE~', DEVELOPER_CLIENT, $strSQL);
	return dbReadValue($objConn_a, $strSQL, __FUNCTION__);
}

// create a guid
function getGUID($strPrefix_a = '')
{
    $strPrefix = $strPrefix_a;
    if ($strPrefix == '' || is_null($strPrefix)) 
    { 
        $strPrefix = ''; 
    }

    $strTemplate = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx';
    $strGuid = '';
    
    for ($intI = 0; $intI < strlen($strTemplate); $intI++) 
    {
        $strChar = $strTemplate[$intI];
        if ($strChar == 'x' || $strChar == 'y') 
        {
            $intR = mt_rand(0, 15);
            $intV = ($strChar == 'x') ? $intR : (($intR & 0x3) | 0x8);
            $strGuid .= dechex($intV);
        } 
        else 
        {
            $strGuid .= $strChar;
        }
    }
    
    return $strPrefix . $strGuid;
}

function getPublicClientID($objConn_a)
{
	$strTableNameClient = getTableNameEntity("client", false);

	$strSQL = "select id returnvalue from ~TABLENAMECLIENT~ where code = '~CLIENTCODE~'";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~CLIENTCODE~', PUBLIC_CLIENT, $strSQL);
	return dbReadValue($objConn_a, $strSQL, __FUNCTION__);
}

function getSessionDB($strSource_a)
{
	global $strGlobalClientDB;

	$strResult = "";

	if (isset($_SESSION['server_clientdb']))
	{
		if ($_SESSION['server_clientdb'] == 'client')
		{
			$strResult = 'client';
		}
		else
		{
			$strResult = 'system';
		}
	}
	else
	{
		$strResult = 'system';
	}

	$strGlobalClientDB = $strResult;
	$_SESSION['server_clientdb'] = $strResult;
	
	if (!in_array($strSource_a, ['dbExecuteSQL','dbOpenRecordset','dbReadValue','initDB','dbBeginTrans','dbEndTrans'], true))
	{
		logSecurity('SESSIONDB retrieved: ' . $strResult . ' in function ' . $strSource_a, '');
	}

	return $strResult;
}

function getSystemClientID($objConn_a)
{
	$strTableNameClient = getTableNameEntity("client", false);

	$strSQL = "select id returnvalue from ~TABLENAMECLIENT~ where code = '~CLIENTCODE~'";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~CLIENTCODE~', SYSTEM_CLIENT, $strSQL);
	return dbReadValue($objConn_a, $strSQL, __FUNCTION__);
}

function getSystemOwnerClientID($objConn_a)
{
	$strTableNameClient = getTableNameEntity("client", false);

	$strSQL = "select id returnvalue from ~TABLENAMECLIENT~ where code = '~CLIENTCODE~'";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~CLIENTCODE~', SYSTEMOWNER_CLIENT, $strSQL);
	return dbReadValue($objConn_a, $strSQL, __FUNCTION__);
}

// check the user has admin permissions
function hasAdminPermission($objConn_a, $strPermissionCode_a, $strLocation_a, $blnForceLogout_a)
{
	$strTableNamePermission = getTableNameEntity("permission", false);
	$strTableNameProfile = getTableNameEntity("profile", false);
	$strTableNameProfilePermission = getTableNameEntity("profile_permission", false);
	$strTableNameUser = getTableNameEntity("user", false);
	$strTableNameUserProfile = getTableNameEntity("user_profile", false);

    $blnResult = true;

    $strClientID = $_SESSION['server_loggedin_clientid'];
    $strUserID = $_SESSION['server_loggedin_userid'];

	if ((strlen($strClientID) == 0) || (strlen($strUserID) == 0))
	{
		logDebug("Automatic NO Permission in hasAdminPermission due to no client '" . $strClientID . "' or no user '" . $strUserID . "'.", '');
		logSecurity("Automatic NO Permission in hasAdminPermission due to no client '" . $strClientID . "' or no user '" . $strUserID . "'.", '');
		$blnResult = false;
	}
	else
	{
		$strSQL =
			"
	select distinct pr.description as returnvalue
	from ~TABLENAMEUSER~ u, ~TABLENAMEUSERPROFILE~ up, ~TABLENAMEPROFILE~ p, ~TABLENAMEPROFILEPERMISSION~ pp, ~TABLENAMEPERMISSION~ pr
	where
	u.client_id = ~CLIENTID~ and
	u.is_enabled = 'Y' and
	u.id = ~USERID~ and
	up.client_id = u.client_id and
	up.user_id = u.id and
	p.id = up.profile_id and
	p.client_id = up.client_id and
	p.is_enabled = 'Y' and
	pp.client_id = p.client_id and
	pp.profile_id = p.id and
	pr.id = pp.permission_id and
	pr.code = '~PERMISSIONCODE~' and
	pr.is_enabled = 'Y' and
	p.f9a0f134f_54b8_4e11_81b3_02d4f51dadd0_isadmin = 'Y' and
	(pr.0000eae3_e5b8_4ebb_a3a8_50220cee15d5_applications = '' or pr.0000eae3_e5b8_4ebb_a3a8_50220cee15d5_applications is null or pr.0000eae3_e5b8_4ebb_a3a8_50220cee15d5_applications like '%~APPCODE~%')
	";
		$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
		$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
		$strSQL = str_replace('~TABLENAMEPROFILEPERMISSION~', ff($strTableNameProfilePermission), $strSQL);
		$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
		$strSQL = str_replace('~TABLENAMEUSERPROFILE~', ff($strTableNameUserProfile), $strSQL);
		$strSQL = str_replace('~APPCODE~', ff(APP_CODE), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
		$strSQL = str_replace('~PERMISSIONCODE~', ff($strPermissionCode_a), $strSQL);
		$strResult = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

		if (strlen($strResult) == 0) 
		{
			if ($blnForceLogout_a) 
			{
				$blnResult = forceLogout($strPermissionCode_a, $strLocation_a);
			} 
			else 
			{
				$blnResult = false;
			}
		}
	}

    return $blnResult;
}

// check the user has admin permissions
function hasNonAdminPermission($objConn_a, $strPermissionCode_a, $strLocation_a, $blnForceLogout_a)
{
	$strTableNamePermission = getTableNameEntity("permission", false);
	$strTableNameProfile = getTableNameEntity("profile", false);
	$strTableNameProfilePermission = getTableNameEntity("profile_permission", false);
	$strTableNameUser = getTableNameEntity("user", false);
	$strTableNameUserProfile = getTableNameEntity("user_profile", false);

    $blnResult = true;

    $strClientID = $_SESSION['server_loggedin_clientid'];
    $strUserID = $_SESSION['server_loggedin_userid'];

	if ((strlen($strClientID) == 0) || (strlen($strUserID) == 0))
	{
		logDebug("Automatic NO Permission in hasNonAdminPermission due to no client '" . $strClientID . "' or no user '" . $strUserID . "'.", '');
		logSecurity("Automatic NO Permission in hasNonAdminPermission due to no client '" . $strClientID . "' or no user '" . $strUserID . "'.", '');
		$blnResult = false;
	}
	else
	{
		$strSQL =
			"
	select distinct pr.description as returnvalue
	from ~TABLENAMEUSER~ u, ~TABLENAMEUSERPROFILE~ up, ~TABLENAMEPROFILE~ p, ~TABLENAMEPROFILEPERMISSION~ pp, ~TABLENAMEPERMISSION~ pr
	where
	u.client_id = ~CLIENTID~ and
	u.is_enabled = 'Y' and
	u.id = ~USERID~ and
	up.client_id = u.client_id and
	up.user_id = u.id and
	p.id = up.profile_id and
	p.client_id = up.client_id and
	p.is_enabled = 'Y' and
	pp.client_id = p.client_id and
	pp.profile_id = p.id and
	pr.id = pp.permission_id and
	pr.code = '~PERMISSIONCODE~' and
	pr.is_enabled = 'Y' and
	p.f9a0f134f_54b8_4e11_81b3_02d4f51dadd0_isadmin = 'N' and
	(pr.0000eae3_e5b8_4ebb_a3a8_50220cee15d5_applications = '' or pr.0000eae3_e5b8_4ebb_a3a8_50220cee15d5_applications is null or pr.0000eae3_e5b8_4ebb_a3a8_50220cee15d5_applications like '%~APPCODE~%')
	";
		$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
		$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
		$strSQL = str_replace('~TABLENAMEPROFILEPERMISSION~', ff($strTableNameProfilePermission), $strSQL);
		$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
		$strSQL = str_replace('~TABLENAMEUSERPROFILE~', ff($strTableNameUserProfile), $strSQL);
		$strSQL = str_replace('~APPCODE~', ff(APP_CODE), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
		$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
		$strSQL = str_replace('~PERMISSIONCODE~', ff($strPermissionCode_a), $strSQL);
		$strResult = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		
		if (strlen($strResult) == 0) 
		{
			if ($blnForceLogout_a) 
			{
				$blnResult = forceLogout($strPermissionCode_a, $strLocation_a);
			} 
			else 
			{
				$blnResult = false;
			}
		}
	}

    return $blnResult;
}

// check the user has permissions
function hasPermission($objConn_a, $strPermissionCode_a, $strLocation_a, $blnForceLogout_a)
{
	$strTableNamePermission = getTableNameEntity("permission", false);
	$strTableNameProfile = getTableNameEntity("profile", false);
	$strTableNameProfilePermission = getTableNameEntity("profile_permission", false);
	$strTableNameUser = getTableNameEntity("user", false);
	$strTableNameUserProfile = getTableNameEntity("user_profile", false);

    $blnResult = true;

	if ($strPermissionCode_a != "NOCHECK")
	{
		$strClientID = $_SESSION['server_loggedin_clientid'];
		$strUserID = $_SESSION['server_loggedin_userid'];

		if ((strlen($strClientID) == 0) || (strlen($strUserID) == 0))
		{
			logDebug("Automatic NO Permission in hasPermission due to no client '" . $strClientID . "' or no user '" . $strUserID . "'.", '');
			logSecurity("Automatic NO Permission in hasPermission due to no client '" . $strClientID . "' or no user '" . $strUserID . "'.", '');
			$blnResult = false;
		}
		else
		{
			$strSQL =
				"
		select distinct pr.description as returnvalue
		from ~TABLENAMEUSER~ u, ~TABLENAMEUSERPROFILE~ up, ~TABLENAMEPROFILE~ p, ~TABLENAMEPROFILEPERMISSION~ pp, ~TABLENAMEPERMISSION~ pr
		where
		u.client_id = ~CLIENTID~ and
		u.is_enabled = 'Y' and
		u.id = ~USERID~ and
		up.client_id = u.client_id and
		up.user_id = u.id and
		p.id = up.profile_id and
		p.client_id = up.client_id and
		p.is_enabled = 'Y' and
		pp.client_id = p.client_id and
		pp.profile_id = p.id and
		pr.id = pp.permission_id and
		pr.code = '~PERMISSIONCODE~' and
		pr.is_enabled = 'Y' and
		(pr.0000eae3_e5b8_4ebb_a3a8_50220cee15d5_applications = '' or pr.0000eae3_e5b8_4ebb_a3a8_50220cee15d5_applications is null or pr.0000eae3_e5b8_4ebb_a3a8_50220cee15d5_applications like '%~APPCODE~%')
		";
			$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
			$strSQL = str_replace('~TABLENAMEPROFILE~', ff($strTableNameProfile), $strSQL);
			$strSQL = str_replace('~TABLENAMEPROFILEPERMISSION~', ff($strTableNameProfilePermission), $strSQL);
			$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
			$strSQL = str_replace('~TABLENAMEUSERPROFILE~', ff($strTableNameUserProfile), $strSQL);
			$strSQL = str_replace('~APPCODE~', ff(APP_CODE), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
			$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
			$strSQL = str_replace('~PERMISSIONCODE~', ff($strPermissionCode_a), $strSQL);
			$strResult = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			if (strlen($strResult) == 0) 
			{
				if ($strPermissionCode_a == "TODO")
				{
					logDebug('TODO Permission found in function ' . $strLocation_a, '');
					logSecurity('TODO Permission found in function ' . $strLocation_a, '');
				}
				else
				{
					if ($blnForceLogout_a) 
					{
						$blnResult = forceLogout($strPermissionCode_a, $strLocation_a);
						//$blnResult = true; // remove this line and uncomment previous line to re-enable forced logout
					} 
					else 
					{
						$blnResult = false;
					}
				}
			}
		}
	}

    return $blnResult;
}

function initUsersBasedOnLDAP($objConn_a, $strSecurityToken_a, $strClientID_a, $strLogin_a)
{
	if (dependencies("utils/ldap"))
	{
		$objLDAP = ldapOpen();

		// TODO initialise new users based on LDAP, maybe deactivate based on LDAP too
		logDebug('initUsersBasedOnLDAP', '');

		ldapClose($objLDAP);
	}
}

function initProfilesBasedOnLDAP($objConn_a, $strSecurityToken_a, $strClientID_a, $strLogin_a)
{
	$strTableNameUser = getTableNameEntity("user", false);

	if (dependencies("utils/ldap"))
	{
		// remove all profiles from the user
		$strSQL = "select id returnvalue from ~TABLENAMEUSER~ where client_id = ~CLIENTID~ and login = '~LOGIN~'";
		$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$strSQL = str_replace('~LOGIN~', ff($strLogin_a), $strSQL);
		$strUserID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

		$strOperationID = getGUID();

		// TODO
		// - add an ldap_group_name to the profile table header
		// - add a code field to the t_selected table
		// - for TL_ENTITY_PROFILES, populate t_selected.code with the ldap_group_name

        //dbBeginTrans($objConn_a, __FUNCTION__);
        //selectListsInitialise($objConn_a, $strSecurityToken_a, $strClientID_a, $strUserID, TL_ENTITY_PROFILES, $strUserID, $strOperationID, null, null);
        //selectListsAllRemove($objConn_a, $strSecurityToken_a, $strClientID_a, $strUserID, TL_ENTITY_PROFILES, $strOperationID, array());
        //$blnX = dbEndTrans($objConn_a, __FUNCTION__);

		// get all the ldap profiles for the user
		$objLDAP = ldapOpen();
		$arrGroups = ldapGetUserGroups($objLDAP, $strLogin_a);
		ldapClose($objLDAP);

		// add the ldap profile go the selection list
        //dbBeginTrans($objConn_a, __FUNCTION__);
		//foreach ($arrGroups as $objGroup)
		//{
			// TODO update t_selected.is_selected where t_selected.code = the group name
		//}
		//selectListsCommit($objConn_a, $strSecurityToken_a, $strClientID_a, $strUserID, TL_ENTITY_PROFILES, $strUserID, $strOperationID);
        //$blnX = dbEndTrans($objConn_a, __FUNCTION__);
	}
}

// this returns whether the client is a system client or a customer client
function isClientReserved($strClientID_a)
{
	$blnResult = false;
	
	if ($strClientID_a <= MAXRESERVEDCLIENTS)
	{
		$blnResult = true;
	}
	
	return $blnResult;
}

// check if the product is licensed
function licenceCheck($objConn_a, $strProductID_a, $strInstallationID_a)
{
    $strResult = '';
    $dteToday = getISODate();

    $strLicenceKey = systemSettingGetNoDebug($objConn_a, SETTING_KEY_LICENCEKEY);
    if (strlen($strLicenceKey) > 0) 
	{
        $strExpiryDate = decryptLicenceKey($strProductID_a, $strInstallationID_a, $strLicenceKey);
        if (strlen($strExpiryDate) > 0) 
		{
            $dteExpiryDate = date('Y-m-d', strtotime($strExpiryDate));
            if ($dteExpiryDate >= $dteToday) 
			{
                $strResult = $dteExpiryDate . '';
            }
        }
    }

    return $strResult;
}

// validate a licence key
function licenceValidate($objConn_a, $strProductID_a, $strInstallationID_a, $strLicenceKey_a)
{
    $blnResult = false;
    $dteToday = getISODate();

    try
    {
        $strExpiryDate = decryptLicenceKey($strProductID_a, $strInstallationID_a, $strLicenceKey_a);
        if (strlen($strExpiryDate) > 0) 
		{
            $dteExpiryDate = date('Y-m-d', strtotime($strExpiryDate));
            if ($dteExpiryDate >= $dteToday) 
			{
                $blnResult = true;
            }
        }
    } 
	catch (Exception $e) 
	{
        // do nothing
    }

    return $blnResult;
}

// given a secured value from the client, unsecure it again
function revertSecuredValue($strSecuredValue_a, $strComment_a, $blnMandatory_a)
{
    $strResult = "";
    if (toBoolean(SECURE_IDS)) 
	{
        if (toBoolean(SECURE_IDS_WITH_GUIDS)) 
		{
            $arrGUIDs = $_SESSION[SESSION_SECURITY];
            $strKey = array_search($strSecuredValue_a, $arrGUIDs);
        } 
		else 
		{
            // decrypt value
//debug('1:' . $strSecuredValue_a);
            $strKey = decryptID($strSecuredValue_a);
//debug('2:' . $strKey);
        }

        if (strlen($strKey) > 0) 
		{
            $arrElements = explode(',', $strKey);
            $strResult = $arrElements[1];
        } 
		else 
		{
            if ($blnMandatory_a) 
			{
				logDebug('could not revert secured value ' . $strComment_a . ': ' . $strSecuredValue_a . '(' . getSessionID() . ')', '');
				logSecurity('could not revert secured value ' . $strComment_a . ': ' . $strSecuredValue_a . '(' . getSessionID() . ')', '');
                safetyDie('mandatory value not provided');
            }
        }
    } 
	else 
	{
        $strResult = $strSecuredValue_a;
        if (($blnMandatory_a) && (strlen($strResult) == 0)) 
		{
            safetyDie('mandatory value not provided');
        }
    }

    return $strResult;
}

// given a secured value list from the client, unsecure it again
function revertSecuredValueList($arrSecuredValueList_a, $strComment_a, $blnMandatory_a)
{
    $arrResult = array();

	foreach ($arrSecuredValueList_a as $strSecuredValue)
	{
		$arrResult[] = revertSecuredValue($strSecuredValue, $strComment_a, $blnMandatory_a);
	}

    return $arrResult;
}

function safetyDie($strReason_a)
{
    logDebug($strReason_a, '');
    die();
}

function secureEntityValue($strEntityCode_a, $strValue_a)
{
	$strResult = $strValue_a;
	
	if (strlen($strResult) > 0)
	{
		$strResult = secureValue('ENTITY_' . ffel($strEntityCode_a), $strResult);
	}
	
	return $strResult;
}

// secure a value to return to the client
function secureValue($strType_a, $strValue_a)
{
    $strResult = '';
    if (toBoolean(SECURE_IDS)) 
	{
        $strKey = $strType_a . ',' . $strValue_a;

        if (toBoolean(SECURE_IDS_WITH_GUIDS)) 
		{
            $arrGUIDs = $_SESSION[SESSION_SECURITY];
            //if (array_key_exists($strKey, $arrGUIDs))
            if (arrayKeyExistsR($strKey, $arrGUIDs)) 
			{
                // found key
                $strResult = $arrGUIDs[$strKey];

                logDebug('secured value retrieved: ' . $strType_a . '.' . $strValue_a . ' as ' . $strResult . '(' . getSessionID() . ')', '');
            } 
			else 
			{
                // allocate key and add it
                $strResult = getGUID();
                $arrGUIDs[$strKey] = $strResult;
                $_SESSION[SESSION_SECURITY] = $arrGUIDs;

                logDebug('secured value from: ' . $strType_a . '.' . $strValue_a . ' to ' . $strResult . '(' . getSessionID() . ')', '');
            }
        } 
		else 
		{
            // encrypt value

            $strResult = encryptID($strKey);
            logDebug('secured value from: ' . $strType_a . '.' . $strValue_a . ' to ' . $strResult . '(' . getSessionID() . ')', '');
        }
    } 
	else 
	{
        $strResult = $strValue_a;
    }

    return $strResult;
}

// custom ID placeholders used in JS so we don't need to expose literal IDs (sometimes we dont know what they are at the JS side anyway)
// always returns an secured value
function translateCustomEntityIDs($objConn_a, $strOriginalValue_a, $blnSecure_a)
{
	$strTableNameEntity = getTableNameEntity("entity", false);
	
	$strResult = $strOriginalValue_a;

	if ($strResult == CUSTOM_FRAGMENTID)
	{
		$strSQL = "select id returnvalue from ~TABLENAMEENTITY~ where code = 'FRAGMENT'";
		$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
		$strResult = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
		// don't secure this, it doesn't work, because it is of type 'entity', not of type 'form'
		//if ($blnSecure_a)
		//{
			//$strResult = secureEntityValue('SYSTEMFORM', $strResult);
		//}
	}
	else if ($strResult == CUSTOM_SELFACCOUNTID)
	{
		$strResult = $_SESSION['server_loggedin_accountid'];
		if ($blnSecure_a)
		{
			$strResult = secureEntityValue('ACCOUNT', $strResult);
		}
	}
	else if ($strResult == CUSTOM_SELFCLIENTID)
	{
		$strResult = $_SESSION['server_loggedin_clientid'];
		if ($blnSecure_a)
		{
			$strResult = secureEntityValue('CLIENT', $strResult);
		}
	}
	else if ($strResult == CUSTOM_SELFID)
	{
		$strResult = $_SESSION['server_loggedin_userid'];
		if ($blnSecure_a)
		{
			$strResult = secureEntityValue('USER', $strResult);
		}
	}
	else if ($strResult == CUSTOM_MYDEVICE)
	{
		$strResult = $_SESSION['server_deviceid'];
		if ($blnSecure_a)
		{
			$strResult = secureEntityValue('DEVICE', $strResult);
		}
	}
	
	return $strResult;
}

function updateSessionDB($strClientDB_a, $strLoginAs_a, $strSource_a)
{
	global $strGlobalClientDB;

	$strGlobalClientDB = $strClientDB_a;
	
	$_SESSION['server_clientdb'] = $strClientDB_a;
	$_SESSION['server_loginas'] = $strLoginAs_a;

	logSecurity('SESSIONDB updated to ' . $strClientDB_a . '.' . $strLoginAs_a . ' in function ' . $strSource_a, '');
	
	return $strClientDB_a;
}
