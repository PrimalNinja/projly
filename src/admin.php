<?php

	$strAdminSuffix = '/admin.php?uuid=' . str_replace('.', '-', uniqid('', true));	// ? added to the end to prevent chrome from caching redirects, refer to here: https://superuser.com/questions/304589/how-can-i-make-chrome-stop-caching-redirects and https://bugs.chromium.org/p/chromium/issues/detail?id=91740
	$blnStopHashChange = 'FALSE';
	//file_put_contents("d:\dev\session.log", "admin.php\n", FILE_APPEND);	// DEBUGSESSION
	require_once('inc-index1.php');
	require_once(DYNAMIC_APP_DIR_PHP . 'inc-startos.php');
	require_once('inc-index2.php');

?>