<?php

	// application identity
	define('APP_NAME', 'Projly');
	define('APP_SHORT_NAME', 'Projly');
	define('APP_COPYRIGHT', 'copyright(C) 2024 Projly Pty Ltd. All rights reserved.');
	define('APP_VERSION', CLIENT_VERSION . ', 12 APRIL 2024');			// display version for the user to see
	define('OS_COPYRIGHT', 'copyright(C) 2024 Mitsukibo Pty Ltd. All rights reserved.');
	define('OWNER_LINKS', 'Proudly brought to you by <a href="http://www.projly.com.au/">www.projly.com.au</a>.');
	
	// modules to be loaded
	define('CLIENTMODULES', 'core,dash,entity,leaflet,widgetclock,widgetdesktopsizer,widgetdock,widgetformbuilder,widgetspeech,widgetterm,widgettube');

	// website
	define('APP_SUPPORT', 'https://projly.com.au/#contact');	// iframe fail also goes here
	define('APP_FEEDBACK', 'https://projly.com.au/#contact');	// iframe fail also goes here
	define('URL_PRIVACY', 'http://www.projly.com.au/privacy-and-complaints-policy/');		// terms & conditions
	define('URL_TERMS', 'http://www.projly.com.au/terms-conditions/');		// privacy policy
	
    // social media links
    define('LINK_INSTAGRAM', '');
    define('LINK_FACEBOOK', '');
    define('LINK_TWITTER', '');
	define('LINK_SNAPCHAT', '');
	define('LINK_WECHAT', '');
    
	// branding related
	define('DYNAMIC_BRANDING', APP_CODE);
	define('REPORT_PRINTEDBY', '');

	// startup
	define('PUBLIC_LANDINGPAGE', 'core.frmPublic');		// public and bots go here	(supports #)			must exist
	define('WELCOMEFORM', 'dash.frmProject');			// default for logged in	(supports #)	must exist
	define('LANDINGPAGE_VIDEOID', 'd1VN6h8m498');			// youtube videoid: iv-8-EgPEY0
	define('LANDINGPAGE_AUDIO', 'TRUE');		// audio to accompany the video?
	define('ENABLE_PUBLICMENU', 'TRUE');			// enable or disable the publicly avaialble menu
	define('PROGRESSCOLOUR', 'white');			// css colour

	define('TESTSCROLL', 'TRUE');
?>