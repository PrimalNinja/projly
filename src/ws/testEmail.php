<?php

require_once('inc-env.php');
require_once('inc-constants.php');
require_once('inc-settings-test.php');
require_once('inc-temporaryvalues.php');
require_once('inc-app-' . APP_CODE . '.php');
require_once(WS_PATH . 'modules/core/inc-constants.php');
require_once(WS_PATH . 'modules/system/framework.php'); 
require_once(WS_PATH . 'modules/system/debug.php'); 
require_once(WS_PATH . 'modules/system/logging.php'); 
require_once(WS_PATH . 'modules/utils/general.php'); 
require_once(WS_PATH . 'modules/utils/dates.php'); 

if (dependencies('utils/messaging'))
{
	$arrRecipients = array();
	$arrRecipients[] = array(
		"recipient" => "test@youremailaddress.com.au"
	);
	$arrRecipients[] = array(
		"recipient" => "test@youremailaddress.com.au"
	);
	
	$strEmailBody = file_get_contents('testEmail.txt');
	
	echo(print_r($arrRecipients, true));
	if (sendMail(GENERAL_EMAIL_DOMAIN, GENERAL_EMAIL_ADDRESS, GENERAL_EMAIL_FROMNAME, $arrRecipients, GENERAL_EMAIL_FROMNAME . " test email", $strEmailBody))
	{
		echo('email test sent ok<br><br>');
	}
	else
	{
		echo('email test attempted but failed<br><br>');
	}
}
else
{
	echo('email test system error<br><br>');
}
