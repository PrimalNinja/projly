<?php

	// JS Bundles (includes JQuery)
	echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'bundle/3p-nomin.js"></script>');
	echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'js/ua-parser.min.js"></script>');

	if (DEBUG_SOURCE == 'FALSE')
	{
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'bundle/3p-bootstrap.z.js"></script>');
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'bundle/3p.z.js"></script>');
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'bundle/3p-app.z.js"></script>');
	}
	else
	{
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'bundle/3p-bootstrap.js"></script>');
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'bundle/3p.js"></script>');
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'bundle/3p-app.js"></script>');
	}

	// other JS
	echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'js/jquery.blockUI.js"></script>');
	echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'js/tinymce/tinymce.min.js"></script>');
	echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'js/tinymce/jquery.tinymce.min.js"></script>');

	echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'js/ace/ace.js"></script>');



	//echo('<!--[if gte IE 8]>');
	//echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'js/fileupload/cors/jquery.xdr-transport.js"></script>');
	//echo('<![endif]-->');

	// WebRenovators
	// include required OS files

	if (DEBUG_SOURCE == 'FALSE')
	{
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'inc-nav.z.js"></script>');
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'inc-navmap-client.z.js"></script>');
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'inc-navmap-public.z.js"></script>');
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'inc-navmap-developer.z.js"></script>');
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'inc-navmap-sysadmin.z.js"></script>');
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'inc-navmap-sysowner.z.js"></script>');
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'inc-osutils.z.js"></script>');
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'inc-os.z.js"></script>');
	}
	else
	{
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'inc-nav.js"></script>');
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'inc-navmap-client.js"></script>');
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'inc-navmap-public.js"></script>');
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'inc-navmap-developer.js"></script>');
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'inc-navmap-sysadmin.js"></script>');
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'inc-navmap-sysowner.js"></script>');
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'inc-osutils.js"></script>');
		echo('<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'inc-os.js"></script>');
	}

?>
