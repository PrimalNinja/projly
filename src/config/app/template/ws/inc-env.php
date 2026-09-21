<?php

	require_once('_release.php');

	define('APP_CODE', '%APP_CODE%');						// valid codes: moq

	// form loading / js & css caching
	define('CLIENT_VERSION', '1.1.1-BETA.1');				// internal version for server communication, should be MAJOR.MINOR.PATCH-ALPHA/BETA.X
	define('SERVER_VERSION', '1.1.1-BETA.1');				// internal version for server communication, should be MAJOR.MINOR.PATCH-ALPHA/BETA.X

	// paths and urls
	define('APP_PATH', '%APP_PATH%');						// where APP is installed for JC
	//define('APP_PATH', 'c:/wamp/www/fms-dev/');			// where APP is installed for FW
	define('DYNAMIC_APP_DIR', '%DYNAMIC_APP_DIR%' . RELEASE_SUFFIX);			// should be 'app/' when developing or CLIENT_VERSION . '/' for deploy
	define('WIKI_PATH', APP_PATH . 'wiki/');				// where WIKI is installed

	define('URL_WEBSITE', '%URL_WEBSITE%');		// main website
	define('URL_APP_PATH', '%URL_APP_PATH%');				// external location of the app also with domain name.  Jzebra HTML generator seems to need this full qualified domain name.

	define('URL_PWDCONFIRM_PATH', '%URL_PWDCONFIRM_PATH%');	// where does password confirmation go
	define('URL_PWDFAIL_PATH', '%URL_PWDFAIL_PATH%');		// where does password fail go

	define('URL_REGCONFIRM_PATH', '%URL_REGCONFIRM_PATH%');	// where does registration confirmation go
	define('URL_REGFAIL_PATH', '%URL_REGFAIL_PATH%');		// where does registration fail go

	define('URL_VERCONFIRM_PATH', '%URL_VERCONFIRM_PATH%');	// where does account verification confirmation go
	define('URL_VERFAIL_PATH', '%URL_VERFAIL_PATH%');		// where does account verification fail go

	define('URL_2DBARCODE_GENERATOR', '%URL_2DBARCODE_GENERATOR%');	// where is the 2d barcode generator

	// system owner area
	define('SYSTEMOWNER_CLIENT', '%SYSTEMOWNER_CLIENT%');	// the owner of the system which maintains all other clients
    define('REPLYTO_SYSTEMOWNERUSER', '%REPLYTO_SYSTEMOWNERUSER%');
    
	// payment details
	define('PAYMENTPROVIDER_DESCRIPTION', '%PAYMENTPROVIDER_DESCRIPTION%');
	define('PAYMENTPROVIDER', '%PAYMENTPROVIDER%');			// payment provider plugin (pluginPayment_DummyGateway or pluginPayment_EwayDirect or pluginPayment_NabTransact or pluginPayment_NabTransactTest)

	// payment plugin config for the following plugins: pluginPayment_EwayDirect
	define('EWAY_DEFAULT_CUSTOMER_ID', '%EWAY_DEFAULT_CUSTOMER_ID%');	// customer's eway account id
    define('EWAY_USELIVE', '%EWAY_USELIVE%');				// use live or test data
    define('EWAY_DEFAULT_LIVE_GATEWAY', '%EWAY_DEFAULT_LIVE_GATEWAY%'); //FALSE sets to testing mode, TRUE to live mode
    define('EWAY_DEFAULT_PAYMENT_METHOD', '%EWAY_DEFAULT_PAYMENT_METHOD%'); // possible values are: REAL_TIME, REAL_TIME_CVN, GEO_IP_ANTI_FRAUD

    define('EWAY_REBILL_INTERVAL', '10');
    define('EWAY_REBILL_INTERVAL_TYPE', '4'); // 1 = day, 2 = week, 3 = month, 4 = year
    define('EWAY_LIVE_INTERVAL', '365');    // in days (used to calculate the expiry date or rebilling date from the original payment date)

	// payment plugin config for the following plugins: pluginPayment_NabTransact
    define('NABTRANSACT_LIVE_URL', '%NABTRANSACT_LIVE_URL%');
    define('NABTRANSACT_LIVE_MERCHANTID', '%NABTRANSACT_LIVE_MERCHANTID%');
    define('NABTRANSACT_LIVE_MERCHANTPWD', '%NABTRANSACT_LIVE_MERCHANTPWD%');
	
	// payment plugin config for the following plugins: nabTransactTest
    define('NABTRANSACT_TEST_URL', '%NABTRANSACT_TEST_URL%');
    define('NABTRANSACT_TEST_MERCHANTID', '%NABTRANSACT_TEST_MERCHANTID%');
    define('NABTRANSACT_TEST_MERCHANTPWD', '%NABTRANSACT_TEST_MERCHANTPWD%');
	
	// CLIENT DATABASES
	define('ENABLE_CLIENTDATABASES', '%ENABLE_CLIENTDATABASES%');

?>