<?php

	session_start();
    session_regenerate_id(true);
    session_unset();
    session_destroy();
    session_write_close();
	
	// bootstrap file for the webapp
	require_once('../inc-env.php');
	require_once('../inc-constants.php');
	require_once('../inc-settings.php');
	require_once('../inc-app-' . APP_CODE . '.php');

	//file_put_contents("d:\dev\session.log", "admin/index.php 1 " . ":\n", FILE_APPEND);	// DEBUGSESSION
	// start new session
	session_name(SESSION_NAME);
	session_start();
	
	//file_put_contents("d:\dev\session.log", "admin/index.php 2 " . ":\n", FILE_APPEND);	// DEBUGSESSION
	
    // $_SESSION['server_clientdb'] = '';
    // $_SESSION['server_loginas'] = '';
    // $_SESSION['server_deviceid'] = '';
    // $_SESSION['server_loggedin'] = false;
    // $_SESSION['server_loggedin_accountid'] = '';
    // $_SESSION['server_loggedin_agent'] = '';
    // $_SESSION['server_loggedin_batch'] = false;
    // $_SESSION['server_loggedin_client'] = '';
    // $_SESSION['server_loggedin_clientid'] = '';
    // $_SESSION['server_loggedin_cookie'] = '';
    // $_SESSION['server_loggedin_default'] = false;
	// $_SESSION['server_loggedin_developer'] = false;
	// $_SESSION['server_loggedin_emailaddress'] = '';
    // $_SESSION['server_loggedin_ipaddress'] = '';
    // $_SESSION['server_loggedin_isemployer'] = false;
	// $_SESSION['server_loggedin_isindividual'] = false;
    // $_SESSION['server_loggedin_public'] = false;
    // $_SESSION['server_loggedin_sysadmin'] = false;
    // $_SESSION['server_loggedin_system'] = false;
    // $_SESSION['server_loggedin_token'] = '';
    // $_SESSION['server_loggedin_user'] = '';
    // $_SESSION['server_loggedin_userid'] = '';

	// $_SESSION['server_returnto_token'] = "";
	// $_SESSION['server_returnto_cookie'] = "";
	// $_SESSION['server_returnto_clientdb'] = "";
	// $_SESSION['server_returnto_loginas'] = "";
	// $_SESSION['server_returnto_client'] = "";
	// $_SESSION['server_returnto_user'] = "";
	// $_SESSION['server_returnto_agent'] = "";
	// $_SESSION['server_returnto_ipaddress'] = "";

	$strAdminSuffix = '';
	$_SESSION['client_clientcode'] = "public";
	$_SESSION['client_login'] = "public";
	$_SESSION['client_password'] = "public";
	$_SESSION['client_username'] = "public";
	$_SESSION['client_loggedin'] = 'FALSE';
	$_SESSION['client_loggedin_token'] = '';
	$_SESSION['client_definitelypublic'] = 'TRUE';
	$_SESSION['client_landingpage'] = '';
	$_SESSION['client_allowjoin'] = 'FALSE';
	unset($_SESSION['client_bypassbrowsercheck']);
	unset($_SESSION['theme']);

	//file_put_contents("d:\dev\session.log", "admin/index.php 3 " . ":\n", FILE_APPEND);	// DEBUGSESSION
	
	header('Location: ' . APP_HOME . '/admin.php?uuid=' . str_replace('.', '-', uniqid('', true)));	// ? added to the end to prevent chrome from caching redirects, refer to here: https://superuser.com/questions/304589/how-can-i-make-chrome-stop-caching-redirects and https://bugs.chromium.org/p/chromium/issues/detail?id=91740
	exit;
	
?>