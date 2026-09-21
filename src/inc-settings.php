<?php

	// client related
	define('CALLERID_PUBLIC', 'public');					// client-specific PUBLIC callerid
	
	// server limits
	//$MAXUPLOADFILES = 20;									// maximum number of files
	$MAXUPLOADFILES = ini_get('max_file_uploads');			// or can use this
    $MAXUPLOADFILESIZE = 33554432; //in bytes. 32mb
	
	// Google - http://www.google.com/enterprise/earthmaps/maps-compare.html
	//define('GOOGLE_APIKEY', 'STICKYOURAPIKEYHERE'); // mitsukibo
	define('GOOGLE_CLIENTID', '');							// required for enterprise use within a firewall and some other non-FMS related uses
	define('GOOGLE_APIKEY', '');							// required if more than 25000 map views per day
	define('GOOGLE_ANALYTICS_PROPERTYID', 'UA-XXXX-Y');
	define('ZWEATHER_LOCATIONCODE', 'ASXX0075');			// Zazar

	// login/login defaults
	define('ENABLE_REGISTER', 'FALSE');						// true if registration is to be enabled (make sure client and server are both set the same)

	// extended registration settings
	define('IMMEDIATE_REGISTRATION', 'TRUE');                // true if we don't want to go through the registration email process, false if we want emails
    define('SENDVERIFICATIONEMAIL', 'TRUE');
    
    // payment methods
	define('PAYPAL_ENABLE', 'TRUE');
	define('PAYPAL_SANDBOX', 'STICKYOURSANDBOXIDHERE');
	define('PAYPAL_PRODUCTION', 'demo_production_client_id');
	define('PAYPAL_CURRENCY', 'AUD');
	define('PAYPAL_COUNTRY', 'AU');
	
    define('CCPROVIDER_ENABLE', 'TRUE');
    
    // others
    define('PHPQUERY_ENABLED', 'TRUE'); 
?>