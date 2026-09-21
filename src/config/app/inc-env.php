<?php

	require_once('_release.php');
	
	define('APP_CODE', 'projlyclient');						// valid codes: moq

	// paths and urls
	define('APP_HOME', '/projly-dev');						// our app home

	define('APP_DOMAIN_PATH', 'http://localhost/projly-dev/');			// path to the app not including the domain name. Jzebra HTML generator seems to need this full qualified domain name.
	
	define('POPUPBLOCKER', 'https://www.google.com.au/search?q=disable+browser+popup+blocker+for+');

	define('DYNAMIC_APP_DIR_PHP', 'app' . RELEASE_SUFFIX . '/');	// should be 'app/' when developing or CLIENT_VERSION . '/' for deploy
	define('DYNAMIC_APP_DIR_URL', 'app' . RELEASE_SUFFIX . '/');	// should be 'app/' when developing or CLIENT_VERSION . '/' for deploy
	define('SERVER_TEMP_DIR', 'd:/dev/projly-dev/ws/temp/');	// should be absolute path to the ws/temp folder
	define('DYNAMIC_CSS_DIR_URL', 'css' . RELEASE_SUFFIX . '/');	// should be 'css/' when developing or CLIENT_VERSION . '/' for deploy

	// sessions
	define('ENABLE_SESSION_COOKIES', 'FALSE');
	
	define('ENABLE_XDEVICE', 'FALSE');

	// debugging
	define('DEVELOPER', 'TRUE');						// turn on developer tools
	define('ENABLE_DEVELOPER_NOTES', 'FALSE');
	define('ENABLE_SAVEANDCLOSE', 'TRUE');
	define('DEVELOPER_NOTES', "<b>Developer Common Blockers:</b><br><br>"
	. "Read this daily or after 15 minutes of things not doing what they should be doing.<br><br>"
	. "1. Before check-in JS Lint with <b>jsl-runme.bat</b>.<br>"
	. "2. Before check-in PHP Lint with <b>ws/test</b>.<br>"
	. "3. <b>Check in nightly</b> only if project runs.<br>"
	. "4. Editing CSS? Run <b>createcssbundles-runme.bat</b>.<br>"
	. "5. Editing CSS or JS? Turn on <b>cachekiller</b>.<br>"
	. "6. Server unresponsive? Double check recently edited <b>inc-entry.php</b>.<br>"
	. "7. Changes still not showing? <b>DEBUG_SOURCE</b> should be true.<br>"
	. "8. System Error when logging in? <b>User table checksum</b> needs to be fixed.<br>"
	. "9. <b>htmlEncode</b> rendered database fields so that people's JS code entered into a database field doesn't execute.<br>"
	. "10. <b>encodeURIComponent</b> URL parameters so that GUIDs and encrypted IDs work correctly.<br>"
	. "11. Do not manually edit <b>theme.css</b> as your changes will be lost, this file is generated.<br>"
	. "12. Do not forget to put new forms in <b>jsl.default.conf, obfuscateminify-runme.bat and deleteoriginaljs-runme.xbat</b>.<br>"
	. "13. Sometimes PHP debugger stuffs up and causes the server to constantly log you out, check again with PHP debugger turned off.<br>"
	. "");
	define('DEBUG_CREDENTIALS', 'FALSE');
	define('DEBUG_JS', 'TRUE');						// turn on for some javascript debugging (writes to chrome console)
	define('DEBUG_SOURCE', 'TRUE');				// turn on source debugging (force not lazyloading the closure compiler source if it is true)
	
?>