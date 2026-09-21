<?php

	// application identity
	define('APP_NAME', 'AWAF Installer');
	define('APP_SHORT_NAME', 'AWAF Installer');
	define('APP_COPYRIGHT', 'copyright(C) 2012-2024 Mitsukibo Pty Ltd. All rights reserved.');
	define('OWNER_LINKS', 'Proudly brought to you by <a href="http://www.projly.com.au/">www.projly.com.au</a>.');
	define('SHOWLOGO', false);

	define('REQUIRED_SCRIPT_VERSION', 3);
	define('TIMEZONE', 'Australia/Melbourne');
	define('MAX_RUN_TIME', 1000);
	
	define('ENABLE_ADVANCED', true);
	define('ENABLE_DEVELOPER', true);

	define('USER_DATETIME', 'YmdHis');
	
	define('DEBUG_LOG', 'TRUE');						// put output into LOG_DEBUG_FILE (log file)

	define('LOG_APICALL_FILE', 'log/apicall.log');		// LOGGING
	define('LOG_BUILD_FILE', 'log/build.log');		// BUILD LOGGING
	define('LOG_DEBUG_FILE', 'log/debug.log');		// LOGGING
	define('LOG_DEBUG_SQL_FILE', 'log/debug_sql.log');	// SQL LOGGING
	define('LOG_ERROR_FILE', 'log/phperrors.log');	// LOGGING
	define('LOG_MISSING_FILES', 'log/missingfiles.log');	// LOGGING MISSING FILES
	define('LOG_PAYMENT_FILE', 'log/payment.log');		// PAYMENT LOGGING
	define('LOG_PHPCONSOLE_FILE', 'log/phpconsole.log'); // PHP CONSOLE LOG
	define('LOG_PRINTING_FILE', 'log/printing.log');		// LOGGING
	define('LOG_REPOSITORY_FILE', 'log/repository.log');		// LOGGING
	define('LOG_ROUTING_FILE', 'log/routing.log');		// LOGGING
	define('LOG_SECURITY_FILE', 'log/security.log');		// LOGGING
	define('LOG_SETTING_FILE', 'log/settings.log');		// LOGGING

	// system variables	
	define("SYSTEM_CLIENT", "system");
?>