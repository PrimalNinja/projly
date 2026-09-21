<?php

	require_once('_release.php');

	define('APP_CODE', 'projlyclient');						// valid codes: moq

	// form loading / js & css caching
	define('CLIENT_VERSION', '1.1.1-BETA.1');				// internal version for server communication, should be MAJOR.MINOR.PATCH-ALPHA/BETA.X
	define('SERVER_VERSION', '1.1.1-BETA.1');				// internal version for server communication, should be MAJOR.MINOR.PATCH-ALPHA/BETA.X

	// paths and urls
	define('APP_PATH', 'd:/dev/projly-dev/');						// where APP is installed for JC
	//define('APP_PATH', 'c:/wamp/www/fms-dev/');			// where APP is installed for FW
	define('DYNAMIC_APP_DIR', 'app' . RELEASE_SUFFIX);			// should be 'app/' when developing or CLIENT_VERSION . '/' for deploy
	define('WIKI_PATH', APP_PATH . 'wiki/');				// where WIKI is installed

	define('URL_WEBSITE', 'https://projly.com.au/');		// main website
	define('URL_APP_PATH', 'http://localhost/projly-dev/');				// external location of the app also with domain name.  Jzebra HTML generator seems to need this full qualified domain name.

	define('URL_PWDCONFIRM_PATH', 'http://localhost/projly-dev/#!core.frmPasswordConfirmation');	// where does password confirmation go
	define('URL_PWDFAIL_PATH', 'http://localhost/projly-dev/#!core.frmPasswordFailed');		// where does password fail go

	define('URL_REGCONFIRM_PATH', 'http://localhost/projly-dev/#!core.frmRegisterConfirmation');	// where does registration confirmation go
	define('URL_REGFAIL_PATH', 'http://localhost/projly-dev/#!core.frmRegisterFailed');		// where does registration fail go

	define('URL_VERCONFIRM_PATH', '');	// where does account verification confirmation go
	define('URL_VERFAIL_PATH', '');		// where does account verification fail go

	define('URL_2DBARCODE_GENERATOR', 'http://localhost/projly-dev/generatebarcode.php');	// where is the 2d barcode generator

	// system owner area
	define('SYSTEMOWNER_CLIENT', 'owner');	// the owner of the system which maintains all other clients
    define('REPLYTO_SYSTEMOWNERUSER', 'admin');
    
	// payment details
	define('PAYMENTPROVIDER_DESCRIPTION', 'Test Dummy');
	define('PAYMENTPROVIDER', 'paymentPlugin_DummyGateway');			// payment provider plugin (pluginPayment_DummyGateway or pluginPayment_EwayDirect or pluginPayment_NabTransact or pluginPayment_NabTransactTest)

	// payment plugin config for the following plugins: pluginPayment_EwayDirect
	define('EWAY_DEFAULT_CUSTOMER_ID', '1234567890');	// customer's eway account id
    define('EWAY_USELIVE', 'FALSE');				// use live or test data
    define('EWAY_DEFAULT_LIVE_GATEWAY', 'FALSE'); //FALSE sets to testing mode, TRUE to live mode
    define('EWAY_DEFAULT_PAYMENT_METHOD', 'REAL_TIME'); // possible values are: REAL_TIME, REAL_TIME_CVN, GEO_IP_ANTI_FRAUD

    define('EWAY_REBILL_INTERVAL', '10');
    define('EWAY_REBILL_INTERVAL_TYPE', '4'); // 1 = day, 2 = week, 3 = month, 4 = year
    define('EWAY_LIVE_INTERVAL', '365');    // in days (used to calculate the expiry date or rebilling date from the original payment date)

	// payment plugin config for the following plugins: pluginPayment_NabTransact
    define('NABTRANSACT_LIVE_URL', 'https://transact.nab.com.au/live/xmlapi/payment');
    define('NABTRANSACT_LIVE_MERCHANTID', '');
    define('NABTRANSACT_LIVE_MERCHANTPWD', '');
	
	// payment plugin config for the following plugins: nabTransactTest
    define('NABTRANSACT_TEST_URL', 'https://demo.transact.nab.com.au/xmlapi/payment');
    define('NABTRANSACT_TEST_MERCHANTID', 'ABC123');
    define('NABTRANSACT_TEST_MERCHANTPWD', '1234567890');
	
	// CLIENT DATABASES
	define('ENABLE_CLIENTDATABASES', 'FALSE');

?>