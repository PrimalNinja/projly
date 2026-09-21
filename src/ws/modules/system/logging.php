<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

$g_intIndent = 0;
$g_strCurrentFunction = '';

function logAPICallRequestResponse($strDebug_a)
{
	logDebugSpecified(LOG_APICALL_FILE, $strDebug_a, '');
}

function logBuild($strLog_a)
{
    logDebugSpecified(LOG_BUILD_FILE, $strLog_a, "");
}

// log something into the debug log
function logDebug($strDebug_a, $strIndent_a)
{
    logDebugSpecified(LOG_DEBUG_FILE, $strDebug_a, $strIndent_a);
}

function logDebugPayment($strMethod_a, $strFullName_a, $strEmail_a, $strAddress_a, $strInvoiceDescription_a, $strInvoiceRef_a, $strAmount_a)
{
    if (toBoolean(DEBUG_LOG))
    {
        logDebugSpecified(LOG_PAYMENT_FILE, "method : " .  $strMethod_a, '');
        logDebugSpecified(LOG_PAYMENT_FILE, "fullname : " . $strFullName_a, '');
        logDebugSpecified(LOG_PAYMENT_FILE, "email : " . $strEmail_a, '');
        logDebugSpecified(LOG_PAYMENT_FILE, "address : " . $strAddress_a, '');
        logDebugSpecified(LOG_PAYMENT_FILE, "invoice description : " . $strInvoiceDescription_a, '');
        logDebugSpecified(LOG_PAYMENT_FILE, "invoice ref : " .  $strInvoiceRef_a, '');
        logDebugSpecified(LOG_PAYMENT_FILE, "amount : " .  $strAmount_a, '');        
        logDebugSpecified(LOG_PAYMENT_FILE, "",  '');        
    }
}

function logDebugSession()
{
	if (toBoolean(DEBUG_LOG_SESSIONINFO))
	{
		logDebug("Session:", '');
		
		$strClientDB = ""; if (isset($_SESSION['server_clientdb'])) { $strClientDB = $_SESSION['server_clientdb']; }
		$strLoginAs = ""; if (isset($_SESSION['server_loginas'])) { $strLoginAs = $_SESSION['server_loginas']; }
		$strDeviceID = ""; if (isset($_SESSION['server_deviceid'])) { $strDeviceID = $_SESSION['server_deviceid']; }
		$strLoggedIn = ""; if (isset($_SESSION['server_loggedin'])) { $strLoggedIn = $_SESSION['server_loggedin']; }
		$strAgent = ""; if (isset($_SESSION['server_loggedin_agent'])) { $strAgent = $_SESSION['server_loggedin_agent']; }
		$strBatch = ""; if (isset($_SESSION['server_loggedin_batch'])) { $strBatch = $_SESSION['server_loggedin_batch']; }
		$strClient = ""; if (isset($_SESSION['server_loggedin_client'])) { $strClient = $_SESSION['server_loggedin_client']; }
		$strClientID = ""; if (isset($_SESSION['server_loggedin_clientid'])) { $strClientID = $_SESSION['server_loggedin_clientid']; }
		$strCookie = ""; if (isset($_SESSION['server_loggedin_cookie'])) { $strCookie = $_SESSION['server_loggedin_cookie']; }
		$strDefault = ""; if (isset($_SESSION['server_loggedin_default'])) { $strDefault = $_SESSION['server_loggedin_default']; }
		$strDeveloper = ""; if (isset($_SESSION['server_loggedin_developer'])) { $strDeveloper = $_SESSION['server_loggedin_developer']; }
		$strEmailAddress = ""; if (isset($_SESSION['server_loggedin_emailaddress'])) { $strEmailAddress = $_SESSION['server_loggedin_emailaddress']; }
		$strIPAddress = ""; if (isset($_SESSION['server_loggedin_ipaddress'])) { $strIPAddress = $_SESSION['server_loggedin_ipaddress']; }
		$strIsEmployer = ""; if (isset($_SESSION['server_loggedin_isemployer'])) { $strIsEmployer = $_SESSION['server_loggedin_isemployer']; }
		$strIsIndividual = ""; if (isset($_SESSION['server_loggedin_isindividual'])) { $strIsIndividual = $_SESSION['server_loggedin_isindividual']; }
		$strPublic = ""; if (isset($_SESSION['server_loggedin_public'])) { $strPublic = $_SESSION['server_loggedin_public']; }
		$strSysAdmin = ""; if (isset($_SESSION['server_loggedin_sysadmin'])) { $strSysAdmin = $_SESSION['server_loggedin_sysadmin']; }
		$strSystem = ""; if (isset($_SESSION['server_loggedin_system'])) { $strSystem = $_SESSION['server_loggedin_system']; }
		$strToken = ""; if (isset($_SESSION['server_loggedin_token'])) { $strToken = $_SESSION['server_loggedin_token']; }
		$strUser = ""; if (isset($_SESSION['server_loggedin_user'])) { $strUser = $_SESSION['server_loggedin_user']; }
		$strUserID = ""; if (isset($_SESSION['server_loggedin_userid'])) { $strUserID = $_SESSION['server_loggedin_userid']; }

		$strReturnToToken = ""; if (isset($_SESSION['server_returnto_token'])) { $strReturnToToken = $_SESSION['server_returnto_token']; }
		$strReturnToCookie = ""; if (isset($_SESSION['server_returnto_cookie'])) { $strReturnToCookie = $_SESSION['server_returnto_cookie']; }
		$strReturnToClientDB = ""; if (isset($_SESSION['server_returnto_clientdb'])) { $strReturnToClientDB = $_SESSION['server_returnto_clientdb']; }
		$strReturnToLoginAs = ""; if (isset($_SESSION['server_returnto_loginas'])) { $strReturnToLoginAs = $_SESSION['server_returnto_loginas']; }
		$strReturnToClient = ""; if (isset($_SESSION['server_returnto_client'])) { $strReturnToClient = $_SESSION['server_returnto_client']; }
		$strReturnToUser = ""; if (isset($_SESSION['server_returnto_user'])) { $strReturnToUser = $_SESSION['server_returnto_user']; }
		$strReturnToAgent = ""; if (isset($_SESSION['server_returnto_agent'])) { $strReturnToAgent = $_SESSION['server_returnto_agent']; }
		$strReturnToIPAddress = ""; if (isset($_SESSION['server_returnto_ipaddress'])) { $strReturnToIPAddress = $_SESSION['server_returnto_ipaddress']; }
		
		logDebug("server_clientdb:" . $strClientDB, '');
		logDebug("server_loginas:" . $strLoginAs, '');
		logDebug("server_deviceid:" . $strDeviceID, '');
		logDebug("server_loggedin:" . $strLoggedIn, '');
		logDebug("server_loggedin_agent:" . $strAgent, '');
		logDebug("server_loggedin_batch:" . $strBatch, '');
		logDebug("server_loggedin_client:" . $strClient, '');
		logDebug("server_loggedin_clientid:" . $strClientID, '');
		logDebug("server_loggedin_cookie:" . $strCookie, '');
		logDebug("server_loggedin_default:" . $strDefault, '');
		logDebug("server_loggedin_developer:" . $strDeveloper, '');
		logDebug("server_loggedin_emailaddress:" . $strEmailAddress, '');
		logDebug("server_loggedin_ipaddress:" . $strIPAddress, '');
		logDebug("server_loggedin_isemployer:" . $strIsEmployer, '');
		logDebug("server_loggedin_isindividual:" . $strIsIndividual, '');
		logDebug("server_loggedin_public:" . $strPublic, '');
		logDebug("server_loggedin_sysadmin:" . $strSysAdmin, '');
		logDebug("server_loggedin_system:" . $strSystem, '');
		logDebug("server_loggedin_token:" . $strToken, '');
		logDebug("server_loggedin_user:" . $strUser, '');
		logDebug("server_loggedin_userid:" . $strUserID, '');

		logDebug("server_returnto_token:" . $strReturnToToken, '');
		logDebug("server_returnto_cookie:" . $strReturnToCookie, '');
		logDebug("server_returnto_clientdb:" . $strReturnToClientDB, '');
		logDebug("server_returnto_loginas:" . $strReturnToLoginAs, '');
		logDebug("server_returnto_client:" . $strReturnToClient, '');
		logDebug("server_returnto_user:" . $strReturnToUser, '');
		logDebug("server_returnto_agent:" . $strReturnToAgent, '');
		logDebug("server_returnto_ipaddress:" . $strReturnToIPAddress, '');

		logDebug("", '');
	}
}

// log something into the security debug log
function logDebugSecurity($strLogFile_a, $strDebug_a, $strIndent_a)
{
    global $g_intIndent;
    global $g_strCurrentFunction;

    $strFunction = ',' . strtolower($g_strCurrentFunction) . ',';
    $strHaystack = ',' . strtolower(DEBUG_LOG_IGNORECALLS) . ',';

    if (InStr($strHaystack, $strFunction) == -1) {
        if ($strIndent_a == 'E') {
            $g_intIndent = $g_intIndent - 2;
            if ($g_intIndent < 0) {
                $g_intIndent = 0;
            }
        }

        if (strlen($strLogFile_a) > 0) {
            error_log(str_repeat(' ', $g_intIndent) . 'D: ' . getDateTime() . ' - ' . $strDebug_a . "\r\n", 3, $strLogFile_a);
        }

		//echo (str_repeat(' ', $g_intIndent) . getDateTime() . ' - ' . $strDebug_a . "<br>");

        if ($strIndent_a == 'S') {
            $g_intIndent = $g_intIndent + 2;
        }
    }
}

// log something into a specified debug log
function logDebugSpecified($strLogFile_a, $strDebug_a, $strIndent_a)
{
    global $g_intIndent;
    global $g_strCurrentFunction;

    $strFunction = ',' . strtolower($g_strCurrentFunction) . ',';
    $strHaystack = ',' . strtolower(DEBUG_LOG_IGNORECALLS) . ',';

    //debug($strHaystack . ":" . $strFunction . ":" . $strDebug_a);

    if (InStr($strHaystack, $strFunction) == -1) 
	{
        if ($strIndent_a == 'E') 
		{
            $g_intIndent = $g_intIndent - 2;
            if ($g_intIndent < 0) 
			{
                $g_intIndent = 0;
            }
        }

        if ((toBoolean(DEBUG_LOG)) && (strlen($strLogFile_a) > 0)) 
		{
            //error_log(str_repeat(' ', $g_intIndent) . 'D: ' . getDateTime() . ' - ' . $strDebug_a . "\r\n", 3, $strLogFile_a);
            error_log(str_repeat(' ', $g_intIndent) . $strDebug_a . "\r\n", 3, $strLogFile_a);
        }

        if (toBoolean(DEBUG_OUTPUT)) 
		{
            echo (str_repeat(' ', $g_intIndent) . getDateTime() . ' - ' . $strDebug_a . "<br>");
			//echo (str_repeat(' ', $g_intIndent) . $strDebug_a . "<br>");
        }

        if ($strIndent_a == 'S') 
		{
            $g_intIndent = $g_intIndent + 2;
        }
    }
}

// log an error
function logError($blnFatal_a, $strError_a)
{
    if (strlen(LOG_ERROR_FILE) > 0) 
	{
        error_log('E: ' . getDateTime() . ' - ' . $strError_a . "\r\n", 3, LOG_ERROR_FILE);
    }

    if (toBoolean(DEBUG_PHPCONSOLE)) 
	{
        debug($strError_a);

        //PC::debug($strError_a);
    }

    if ($blnFatal_a) 
	{
        safetyDie('fatal error');
    }
}

// log missing files
function logMissing($blnFatal_a, $strError_a)
{
	if (strlen(LOG_MISSING_FILES) > 0)
	{
		error_log('E: ' . getDateTime() . ' - ' . $strError_a . "\r\n", 3, LOG_MISSING_FILES);
	}
	
	//if (toBoolean(DEBUG_PHPCONSOLE))
	//{
		//debug($strError_a);

		//PC::debug($strError_a);
	//}
	
	if ($blnFatal_a)
	{
		safetyDie('fatal error');
	}
}

function logPHPConsole($strDebug_a)
{
	logDebugSpecified(LOG_PHPCONSOLE_FILE, $strDebug_a, '');
}

function logPrinting($strDebug_a)
{
	logDebugSpecified(LOG_PRINTING_FILE, $strDebug_a, '');
}

function logRepository($strDebug_a)
{
	logDebugSpecified(LOG_REPOSITORY_FILE, $strDebug_a, '');
}

function logRouting($strDebug_a)
{
	logDebugSpecified(LOG_ROUTING_FILE, $strDebug_a, '');
}

function logSecurity($strDebug_a)
{
	logDebugSecurity(LOG_SECURITY_FILE, $strDebug_a, '');
}

function logSetting($strDebug_a)
{
	logDebugSpecified(LOG_SETTING_FILE, $strDebug_a, '');
}

// log some SQL
function logSQL($strLogFile_a, $blnFatal_a, $strSQL_a, $strError_a, $intAffected_a)
{
    if ($intAffected_a > 0) 
	{
        logDebug($strSQL_a . ', ' . $intAffected_a . ' records affected', '');
        logDebugSpecified($strLogFile_a, $strSQL_a . ', ' . $intAffected_a . ' records affected', '');
    } 
	else 
	{
        logDebug($strSQL_a, '');
        logDebugSpecified($strLogFile_a, $strSQL_a, '');
    }

    if ($strError_a != '') 
	{
        logError($blnFatal_a, 'failed sql: ' . $strSQL_a . ', error: ' . $strError_a);
    }
}
