<?php

$g_intIndent = 0;
$g_strCurrentFunction = '';

function logAPICallRequestResponse($strDebug_a)
{
	logDebugSpecified(LOG_APICALL_FILE, $strDebug_a, '');
}

// log something into the debug log
function logDebug($strDebug_a, $strIndent_a)
{
	logDebugSpecified(LOG_DEBUG_FILE, $strDebug_a, $strIndent_a);
}

// log something into a specified debug log
function logDebugSpecified($strLogFile_a, $strDebug_a, $strIndent_a)
{
	global $g_intIndent;
	global $g_strCurrentFunction;

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
		error_log(str_repeat(' ', $g_intIndent) . 'D: ' . getDateTime() . ' - ' . $strDebug_a . "\r\n", 3, $strLogFile_a);
	}
	
	if ($strIndent_a == 'S')
	{
		$g_intIndent = $g_intIndent + 2;
	}
}

// log an error
function logError($blnFatal_a, $strError_a)
{
	if (strlen(LOG_ERROR_FILE) > 0)
	{
		error_log('E: ' . getDateTime() . ' - ' . $strError_a . "\r\n", 3, LOG_ERROR_FILE);
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
	logDebugSpecified(LOG_PHPCONSOLE_FILE, $strDebug_a, 0);
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
	logDebugSpecified(LOG_SECURITY_FILE, $strDebug_a, '');
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

?>