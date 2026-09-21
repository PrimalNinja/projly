<?php

	// form loading / js & css caching
	define('CLIENT_VERSION', '1.1.1-BETA.1');				// internal version for server communication, should be MAJOR.MINOR.PATCH-ALPHA/BETA.X

	// enable showing of the Products Payment Form upon first login
	define('ENABLE_PAYMENTONLOGIN', 'FALSE');

	// modules to load, case sensitive
	define('COREMODULES', 'core');							// usually only a single core module (minimum required for login form etc)
	define('ENABLE_EXTEND', 'TRUE');
	define('ENABLE_WIDGETS', 'TRUE');

	// searching
	define('ENABLE_PUBLICSEARCH', 'TRUE');
	define('ENABLE_BROWSERCOMPATABILITYCHECKBYPASS', 'TRUE');		// TRUE to bypass browser compatability checking, FALSE TO check

	// timers
	define('KEEPALIVE', 'TRUE');							// keep the server conneciton alive
	define('TIMER_SERVEREVENTS_FREQUENCY', '5000');			// timer frequency in milliseconds (30000 = 30 seconds), set this to 0 to turn the timer off (ie: useful when using xdebug)
	define('TIMER_SERVEREVENTS_FREQUENCY_MOB', '0');		// for mobile devices, timer frequency in milliseconds (30000 = 30 seconds), set this to 0 to turn the timer off (ie: useful when using xdebug)
	define('ERROR_INDICATOR_FAILURECOUNT', '5');				// how many failures before we display the error indicator
	define('APP_IFRAMABLE', 'TRUE');							// is the app iframable (Y-SLOW requires this to be TRUE)

	// java
	define('URL_VERIFY_JAVA', 'https://www.java.com/verify/');	// URL to verify Java (note this could be a google search if oracle keeps moving things)

	// framework related
	define('ENABLE_BOOTSTRAP', 'TRUE');							// true if bootstrap is to be enabled
	define('DEVICE_WIDTH', 'device-width');								// put a number (eg '1000') or 'device-width'

	// analytics
	define('ANALYTICS_URL', '');
	//define('ANALYTICS_URL', 'http://localhost/analytics');	// URL to the analytics server (if there is one)

	// client related
	define('DATE_OUTPUTFORMAT', 'dd/mm/yy');				// output date format. valid formats: dd/mm/yy, mm/dd/yy, yy/mm/dd, dd-mm-yy, mm-dd-yy, yy-mm-dd
	define('DATE_OUTPUTFORMATDESC', 'DD/MM/YYYY');
	define('TOTAL_SPLASH_IMAGES', '4');						// how many splash images do we have

	// security
	define('SESSION_NAME', APP_CODE . '_client');	// session name
	define('SESSION_SECURITY', 'client_security_dictionary');	// security dictionary name
	define('ALLOW_STAY_LOGGEDIN', 'TRUE');					// this should be TRUE only if we want to allow users to be authenticated based on a cookie
	define('DEFAULT_ENTITY', 'public');						// this is used for the login form defaults for public users
	define('DEFAULT_LOGIN', 'public');						// 		entity, login, password should all be 'public'
	define('DEFAULT_PASSWORD', 'public');
	define('ENABLE_QUICKLOGIN', 'TRUE');

	// paths and urls
	define('APP_LOGIN', 'login.php');						// app login page
	define('APP_LOGOUT', 'logout.php');						// app logout page
	define('APP_RETURN', '');								// app return page
	define('EXTEND_PAGE', 'extend.php');					// extension window
	define('URL_WEBSERVICE', 'ws/server.php');	// our webservice
	// define('URL_WEBSERVICE', 'ws/server.php?XDEBUG_SESSION_START=fms_server');	// our webservice when debugging with xdebug
	// define('URL_WEBSERVICE', 'http://localhost:52959/wsn/server.aspx');			// our webservice (c# testing)

	// theme related
	define('DEFAULT_THEME', 'DEFAULT');						// the theme to use if none is selected - all desktop devices fall back to this
	define('AVAILABLE_THEMES', 'DEFAULT');			// used to ensure invalid themes are not selected

	// cookies
	define('COOKIE_EXPIRY', '2');						// cookie expiry period in years
	define('COOKIE_DEVICENAME', APP_CODE . '_devicename');

	// various
	define('GOOGLE_SEARCH', 'http://www.google.com.au/search?hl=en&output=search&q=');
    define('MAX_DATAFORM_FIELD_LIMIT', '4000');

	// abn finder
	define('ABNLOOKUP_CHARACTER_LENGTH', '6');			// maximum characters to trigger abn lookup ( only when it is enabled and it is not has a button)
	define('ABNLOOKUP_DELAY_TIME', '200');				// delay time for abn lookup
	define('ENABLE_DYNAMICABNSEARCH', 'TRUE');			// enable the autocomplete to run

	// predictive text finder
	define('MINPREDICTIVECHARS', 1);
		
	// URLs
	define('URL_2DBARCODE_GENERATOR', '%URL_2DBARCODE_GENERATOR%');
?>
