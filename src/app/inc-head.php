<?php
	header('Content-Type: text/html; charset=utf-8');
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

	require_once(DYNAMIC_APP_DIR_PHP . 'inc-utils.php');
	//require_once(DYNAMIC_APP_DIR_PHP . 'inc-generic.php');
	//require_once(DYNAMIC_APP_DIR_PHP . 'inc-server.php');
	require_once(DYNAMIC_APP_DIR_PHP . 'inc-api.php');

	// theme selection
	if (isset($strTheme))
	{
		if (instr(',' . AVAILABLE_THEMES . ',', ',' . $strTheme . ',') == -1)
		{
			$strTheme = DEFAULT_THEME;
		}
	}
	else
	{
		$strTheme = DEFAULT_THEME;
	}
	$_SESSION['theme'] = $strTheme;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"> 
	<noscript><meta http-equiv="refresh" content="0;url=noscript.php"></noscript>
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=8, IE=9, IE=10, IE=edge" >
		<meta charset="UTF-8" />
		<meta name="description" content="<?php echo(APP_NAME); ?>" />
		<meta name="keywords" content="<?php echo(APP_NAME); ?>" />
		<meta name="author" content="webrenovators.com.au" />
		<meta name="Revisit-After" v="21 Days">
		<meta name="Distribution" content="Local">
		<meta name="country" content="Australia">
		<meta name="MSSmartTagsPreventParsing" content="TRUE">
		
		<!-- favicon -->
		<link rel="apple-touch-icon" sizes="120x120" href="images/icon/apple-touch-icon.png">
		<link rel="icon" type="image/png" href="images/icon/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="images/icon/favicon-16x16.png" sizes="16x16">
		<link rel="manifest" href="images/icon/site.webmanifest">
		<link rel="mask-icon" href="images/icon/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="theme-color" content="#ffffff">		
		
		<!--========= NOTE inc-os.js also changes the viewport afterwards =========-->
		<meta name="viewport" content="width=<?php echo(DEVICE_WIDTH); ?>, initial-scale=1.0, xmaximum-scale=3.0, xminimum-scale=0.25" />
	
		<!--========= BUNDLE CSS =========-->
   	 	<link rel="stylesheet" type="text/css" href="<?php echo(DYNAMIC_APP_DIR_URL); ?>bundle/3p.css" />

		<!--========= Themes =========-->
		<link rel="stylesheet" href="<?php echo(DYNAMIC_APP_DIR_URL); ?>css/themes/<?php echo(strtolower($strTheme)); ?>/theme.css">

		<!--========= Branding =========-->
		<link rel="stylesheet" href="<?php echo(DYNAMIC_APP_DIR_URL); ?>css/branding/<?php echo(strtolower($strTheme)); ?>/branding.css">
	
		<title><?php echo(APP_NAME); ?></title>

<?php

		$strJavaScript = '<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'js/jquery/jquery-1.11.2.min.js"></script>';
		$strJavaScript .= '<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'js/jquery/jquery-migrate-1.2.1.min.js"></script>';
		echo($strJavaScript);

		if (ENABLE_BOOTSTRAP == 'TRUE')
		{
			$strJavaScript .= '<link rel="stylesheet" type="text/css" href="' .DYNAMIC_APP_DIR_URL . 'bundle/bootstrap.min.css">';
			$strJavaScript .= '<link rel="stylesheet" type="text/css" href="' .DYNAMIC_APP_DIR_URL . 'bundle/bootstrap-theme.min.css">';
			$strJavaScript .= '<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'bundle/bootstrap.min.js"></script>';
			$strJavaScript .= '<link rel="stylesheet" type="text/css" href="' .DYNAMIC_APP_DIR_URL . 'bundle/bootstrap-dialog.min.css">';
			$strJavaScript .= '<script type="text/javascript" src="' . DYNAMIC_APP_DIR_URL . 'bundle/bootstrap-dialog.min.js"></script>';
			echo($strJavaScript);
		}
		
?>
		<!--fonts-->
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Lato:400,100,100italic,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
	
		<!-- font awesome -->
		<link href="<?php echo(DYNAMIC_CSS_DIR_URL); ?>font-awesome.min.css" rel="stylesheet">

		<!-- popup css -->
		<link href="<?php echo(DYNAMIC_CSS_DIR_URL); ?>magnific-popup.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="<?php echo(DYNAMIC_CSS_DIR_URL); ?>slick.css">
		<link rel="stylesheet" type="text/css" href="<?php echo(DYNAMIC_CSS_DIR_URL); ?>slick-theme.css">
	</head>
