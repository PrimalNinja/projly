<?php

	// bootstrap file for the webapp
	require_once('inc-env.php');
	require_once('inc-constants.php');
	require_once('inc-settings.php');
	require_once('inc-app-' . APP_CODE . '.php');
	
	session_start();

	$strMapsProvider = '';

	$blnRefresh = false;

	require_once(DYNAMIC_APP_DIR_PHP . 'inc-utils.php');
	require_once(DYNAMIC_APP_DIR_PHP . 'inc-botserver.php');

	$LOGGEDIN = 'FALSE';

	$strLandingVideoID = LANDINGPAGE_VIDEOID;

	// remove landing video if booking or visitor
	if (isset($_GET['novideo']))
	{
		$strLandingVideoID = '';
	}

	// the loggedin parameters is sent to here from frmLogin if we only just logged in
	if (isset($_GET['loggedin']))
	{
		if ($_GET['loggedin'] == 'true')
		{
			$LOGGEDIN = 'TRUE';
			//$strAdminSuffix = '';
			$_SESSION['client_loggedin'] = 'TRUE';
			$blnRefresh = true;
		}
	} else if (isset($_SESSION['client_loggedin']))
	{
		$LOGGEDIN = $_SESSION['client_loggedin'];
	}

	if (!isset($_SESSION['client_allowjoin']))
	{
		$_SESSION['client_allowjoin'] = 'TRUE';
	}

	if (!isset($_SESSION['client_landingpage']))
	{
		$_SESSION['client_landingpage'] = WELCOMEFORM;
	}
	else
	{
		if ($_SESSION['client_landingpage'] == '')
		{
			$_SESSION['client_landingpage'] = WELCOMEFORM;
		}
	}

	if ($LOGGEDIN == 'FALSE')
	{
		$_SESSION['client_definitelypublic'] = 'FALSE';
	}
	
	if (($LOGGEDIN == 'TRUE') && (isset($_SESSION['client_loggedin_token'])))
	{
		// do nothing
	}
	else
	{
		if (isset($_SESSION['client_loggedin_token']))
		{
			if (strlen($_SESSION['client_loggedin_token']) == 0)
			{
				$_SESSION['client_loggedin_token'] = getGUID();
				//file_put_contents("d:\dev\session.log", "inc-index1.php 2 " . $_SESSION['client_loggedin_token'] . ":" . $_SESSION['client_loggedin_token'] . "\n", FILE_APPEND);	// DEBUGSESSION
			}
		}
		else
		{
			$_SESSION['client_loggedin_token'] = getGUID();
			//file_put_contents("d:\dev\session.log", "inc-index1.php 2 " . $_SESSION['client_loggedin_token'] . ":" . $_SESSION['client_loggedin_token'] . "\n", FILE_APPEND);	// DEBUGSESSION
		}
	}
	//echo('client_loggedin_token 3: ' . $_SESSION["client_loggedin_token"] . '<br><hr>');	// DEBUGSESSION

	$BYPASSBROWSERCHECK = ENABLE_BROWSERCOMPATABILITYCHECKBYPASS;
	if (isset($_GET['bypassbrowsercheck']))
	{
		if ($_GET['bypassbrowsercheck'] == 'true')
		{
			$BYPASSBROWSERCHECK = 'TRUE';
		}
	}
	$_SESSION['client_bypassbrowsercheck'] = $BYPASSBROWSERCHECK;

	// read theme parameters
	if (isset($_GET['theme']))
	{
		$_SESSION['theme'] = $_GET['theme'];
		$blnRefresh = true;
	}

	// theme selection
	if (($LOGGEDIN == 'TRUE') && (isset($_SESSION['theme'])))
	{
		$strTheme = $_SESSION['theme'];
	}
	else
	{
		$strTheme = DEFAULT_THEME;
	}

	// bots
	$strPublicLandingPage = '';
	if (isset($_GET['_escaped_fragment_']))
	{
		$strPublicLandingPage = $_GET['_escaped_fragment_'];
	}
	else
	{
		$strPublicLandingPage = PUBLIC_LANDINGPAGE;
		$strPublicLandingPage = str_replace('#', '', $strPublicLandingPage);
	}

	require_once(DYNAMIC_APP_DIR_PHP . 'inc-head-app.php');
	require_once(DYNAMIC_APP_DIR_PHP . 'inc-body-app.php');
	if ($_SESSION['client_bypassbrowsercheck'] == 'FALSE')
	{
		require_once(DYNAMIC_APP_DIR_PHP . 'inc-browserdetect.php');
	}
	require_once(DYNAMIC_APP_DIR_PHP . 'inc-header.php');

	// initialise security model
	if (isset($_SESSION[SESSION_SECURITY]))
	{
		// do nothing
	}
	else
	{
		$_SESSION[SESSION_SECURITY] = array();
	}

	// fetch the databse name
	$intResponseCode = 0;
	$strDatabaseName = 'UNKNOWN';
	$strResponseMessage = '';
	$arrResult = getDatabaseName($osCall);
	
	// $strJSON = ajaxRequestCreate("public_getdatabasename", array());
	// $arrJSON = ajaxCall(true, APP_DOMAIN_PATH . URL_WEBSERVICE, $strJSON);
	$intResponseCode = intval($arrResult['responsecode']);
	if (($intResponseCode == 1) || ($intResponseCode == 2))
	{
		$strResponseMessage = $arrResult['message'];
	}
	if (($intResponseCode == 0) || ($intResponseCode == 1))
	{
		$strDatabaseName = $arrResult['response']['databasename'];
	}

	$strAppHome = APP_HOME;
	if (isset($strAdminSuffix))
	{
		$strAppHome = $strAppHome . $strAdminSuffix;
	}
	
	//$intResponseCode = 2;
	//$strResponseMessage = "hello";

	// pass PHP stuff to JavaScript
	startJavaScript();

	// os-specific constants
	passPHPValue('ABNLOOKUP_CHARACTER_LENGTH', ABNLOOKUP_CHARACTER_LENGTH);
	passPHPValue('ABNLOOKUP_DELAY_TIME', ABNLOOKUP_DELAY_TIME);
	passPHPValue('ALLOW_STAY_LOGGEDIN', ALLOW_STAY_LOGGEDIN);
	passPHPValue('ANALYTICS_URL', ANALYTICS_URL);
	passPHPValue('APP_CODE', APP_CODE);
	passPHPValue('APP_COPYRIGHT', APP_COPYRIGHT);
	passPHPValue('APP_DOMAIN_PATH', APP_DOMAIN_PATH);
	passPHPValue('APP_FEEDBACK', APP_FEEDBACK);
	passPHPValue('APP_HOME', $strAppHome);
	passPHPValue('APP_LOGIN', APP_LOGIN);
	passPHPValue('APP_LOGOUT', APP_LOGOUT);
	passPHPValue('APP_NAME', APP_NAME);
	passPHPValue('APP_RETURN', APP_RETURN);
	passPHPValue('APP_SHORT_NAME', APP_SHORT_NAME);
	passPHPValue('APP_SUPPORT', APP_SUPPORT);
	passPHPValue('APP_VERSION', APP_VERSION);
	passPHPValue('CALLERID_PUBLIC', CALLERID_PUBLIC);
	passPHPValue('CLIENTMODULES', CLIENTMODULES);
	passPHPValue('CLIENT_VERSION', CLIENT_VERSION);
	passPHPValue('COOKIE_EXPIRY', COOKIE_EXPIRY);
	passPHPValue('COOKIE_DEVICENAME', COOKIE_DEVICENAME);
	passPHPValue('DATE_OUTPUTFORMAT', DATE_OUTPUTFORMAT);
	passPHPValue('DATE_OUTPUTFORMATDESC', DATE_OUTPUTFORMATDESC);
	passPHPValue('DEBUG_JS', DEBUG_JS);
	passPHPValue('DEBUG_SOURCE', DEBUG_SOURCE);
	passPHPValue('DEFAULT_ENTITY', DEFAULT_ENTITY);
	passPHPValue('DEFAULT_LOGIN', DEFAULT_LOGIN);
	passPHPValue('DEFAULT_PASSWORD', DEFAULT_PASSWORD);
	passPHPValue('DEFAULT_THEME', DEFAULT_THEME);
	passPHPValue('DEVELOPER', DEVELOPER);
	passPHPValue('DEVELOPER_NOTES', DEVELOPER_NOTES);
	passPHPValue('DEVICE_WIDTH', DEVICE_WIDTH);
	passPHPValue('DYNAMIC_APP_DIR_PHP', DYNAMIC_APP_DIR_PHP);
	passPHPValue('DYNAMIC_APP_DIR_URL', DYNAMIC_APP_DIR_URL);
	passPHPValue('DYNAMIC_BRANDING', DYNAMIC_BRANDING);
	passPHPValue('ENABLE_BOOTSTRAP', ENABLE_BOOTSTRAP);
	passPHPValue('ENABLE_DEVELOPER_NOTES', ENABLE_DEVELOPER_NOTES);
	passPHPValue('ENABLE_EXTEND', ENABLE_EXTEND);
	passPHPValue('ENABLE_REGISTER', ENABLE_REGISTER);
	passPHPValue('ENABLE_WIDGETS', ENABLE_WIDGETS);
    passPHPValue('ENABLE_QUICKLOGIN', ENABLE_QUICKLOGIN);
    passPHPValue('ENABLE_DYNAMICABNSEARCH', ENABLE_DYNAMICABNSEARCH);
	passPHPValue('ENABLE_SAVEANDCLOSE', ENABLE_SAVEANDCLOSE);
	passPHPValue('ENABLE_XDEVICE', ENABLE_XDEVICE);
	passPHPValue('ERROR_INDICATOR_FAILURECOUNT', ERROR_INDICATOR_FAILURECOUNT);
	passPHPValue('EXTEND_PAGE', EXTEND_PAGE);
	passPHPValue('IMMEDIATE_REGISTRATION', IMMEDIATE_REGISTRATION);
	passPHPValue('KEEPALIVE', KEEPALIVE);
	passPHPValue('MAXUPLOADFILES', $MAXUPLOADFILES);
    passPHPValue('MAXUPLOADFILESIZE', $MAXUPLOADFILESIZE);
    passPHPValue('MAX_DATAFORM_FIELD_LIMIT', MAX_DATAFORM_FIELD_LIMIT);
	passPHPValue('OS_COPYRIGHT', OS_COPYRIGHT);
	passPHPValue('PAYPAL_CURRENCY', PAYPAL_CURRENCY);
	passPHPValue('PAYPAL_ENABLE', PAYPAL_ENABLE);
	passPHPValue('PAYPAL_SANDBOX', PAYPAL_SANDBOX);
	passPHPValue('PAYPAL_PRODUCTION', PAYPAL_PRODUCTION);
	passPHPValue('ENABLE_PAYMENTONLOGIN', ENABLE_PAYMENTONLOGIN);
	passPHPValue('PAYLOAD', 'PAYLOAD');
	passPHPValue('POPUPBLOCKER', POPUPBLOCKER);
	passPHPValue('PROGRESSCOLOUR', PROGRESSCOLOUR);
    passPHPValue('REPORT_PRINTEDBY', REPORT_PRINTEDBY);
	passPHPValue('STOP_HASHCHANGE', $blnStopHashChange);
	passPHPValue('TESTSCROLL', TESTSCROLL);
	passPHPValue('TIMER_SERVEREVENTS_FREQUENCY', TIMER_SERVEREVENTS_FREQUENCY);
	passPHPValue('TIMER_SERVEREVENTS_FREQUENCY_MOB', TIMER_SERVEREVENTS_FREQUENCY_MOB);
	passPHPValue('TOTAL_SPLASH_IMAGES', TOTAL_SPLASH_IMAGES);
	passPHPValue('URL_PRIVACY', URL_PRIVACY);
	passPHPValue('URL_TERMS', URL_TERMS);
	passPHPValue('URL_VERIFY_JAVA', URL_VERIFY_JAVA);
	passPHPValue('URL_WEBSERVICE', URL_WEBSERVICE);
	passPHPValue('URL_2DBARCODE_GENERATOR', URL_2DBARCODE_GENERATOR);

	// variables
	passPHPValue('BYPASSBROWSERCHECK', $BYPASSBROWSERCHECK);
	passPHPValue('CLIENT_ALLOWJOIN', $_SESSION['client_allowjoin']);
	passPHPValue('DEVICEIDCOOKIE', ''); //$_SESSION['client_loggedin_token']);
	passPHPValue('WELCOMEFORM', $_SESSION['client_landingpage']);
	passPHPValue('DATABASENAME', $strDatabaseName);
	passPHPValue('ENABLE_PUBLICMENU', ENABLE_PUBLICMENU);
	passPHPValue('LANDINGPAGE_VIDEOID', $strLandingVideoID);
	passPHPValue('LANDINGPAGE_AUDIO', LANDINGPAGE_AUDIO);
	passPHPValue('LOGGEDIN', $LOGGEDIN);
	passPHPValue('PUBLIC_LANDINGPAGE', $strPublicLandingPage);
	passPHPValue('PUBLIC_LANDINGPAGEID', $strPublicLandingPageID);
	passPHPValue('REFRESH', $blnRefresh); // refresh so we can get rid of the URL parameters
	passPHPValue('RESPONSECODE', $intResponseCode . "");
	passPHPValue('RESPONSEMESSAGE', $strResponseMessage);
	passPHPValue('SECURITY_TOKEN', $_SESSION['client_loggedin_token']);
	passPHPValue('SELECTED_THEME', $strTheme);

	// entity constants
	passPHPValue('ENTITY_PRINTJOB', ENTITY_PRINTJOB);

	// list constants
	passPHPValue('LIST_DATAAUDITLOGS', LIST_DATAAUDITLOGS);
	passPHPValue('LIST_FILEFORMATINSTALLABLE', LIST_FILEFORMATINSTALLABLE);
	passPHPValue('LIST_PROFILEINSTALLABLE', LIST_PROFILEINSTALLABLE);

	//predictive text finder
	passPHPValue('MINPREDICTIVECHARS', MINPREDICTIVECHARS);

	// response codes
	passPHPValue('RC_NOERROR', RC_NOERROR);
	passPHPValue('RC_NOERRORMESSAGE', RC_NOERRORMESSAGE);
	passPHPValue('RC_ERRORMESSAGE', RC_ERRORMESSAGE);
	passPHPValue('RC_LOGOUT', RC_LOGOUT);

	endJavaScript();
	//file_put_contents("d:\dev\session.log", "inc-index1.php 3 " . $_SESSION['client_loggedin_token'] . ":" . $_SESSION['client_loggedin_token'] . "\n", FILE_APPEND);	// DEBUGSESSION
	require_once('inc-devsetup.php');
	
?>