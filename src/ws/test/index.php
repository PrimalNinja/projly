
<?php

// base includes
require_once('../inc-env.php');
require_once('../inc-constants.php');
require_once('../inc-settings.php');
require_once('../inc-temporaryvalues.php');
require_once('../inc-app-' . APP_CODE . '.php');
require_once(WS_PATH . 'modules/core/inc-constants.php');
require_once(WS_PATH . 'modules/system/framework.php'); 

// declarations for the entry points
$arrParameters = array();
$strDataID = "";
$strFunction = "";

// include entry points
//require_once(WS_PATH . 'modules/archive/inc-entry.php');
//require_once(WS_PATH . 'modules/batch/inc-entry.php');
require_once(WS_PATH . 'modules/core/inc-entry.php');
require_once(WS_PATH . 'modules/dash/inc-entry.php');
require_once(WS_PATH . 'modules/docs/inc-entry.php');
require_once(WS_PATH . 'modules/entity/inc-entry.php');
require_once(WS_PATH . 'modules/esb/inc-entry.php');
require_once(WS_PATH . 'modules/msg/inc-entry.php');
require_once(WS_PATH . 'modules/process/inc-entry.php');
require_once(WS_PATH . 'modules/projly/inc-entry.php');
require_once(WS_PATH . 'modules/security/inc-entry.php');
require_once(WS_PATH . 'modules/setting/inc-entry.php');
require_once(WS_PATH . 'modules/system/inc-entry.php');
require_once(WS_PATH . 'modules/utils/inc-entry.php');

//if (dependencies(dependenciesFetch('utils')))
//{
	//for ($i = 1; $i <= 500; $i++) 
	//{
		//echo(createIDXName() . "<br>");
	//}
//}

// test modules
if (dependencies(dependenciesFetch('system')))
{
	echo('system module ok<br><br>');
}

if (dependencies(dependenciesFetch('application')))
{
	echo('application module ok<br><br>');
}

if (dependencies(dependenciesFetch('batch')))
{
	echo('batch module ok<br><br>');
}

if (dependencies(dependenciesFetch('cart')))
{
	echo('cart module ok<br><br>');
}

if (dependencies(dependenciesFetch('checkdigit')))
{
	echo('checkdigit module ok<br><br>');
}

if (dependencies(dependenciesFetch('core')))
{
	echo('core module ok<br><br>');
}

if (dependencies(dependenciesFetch('crypt')))
{
	echo('crypt module ok<br><br>');
}

if (dependencies(dependenciesFetch('dash')))
{
	echo('dash module ok<br><br>');
}

if (dependencies(dependenciesFetch('developer')))
{
	echo('developer module ok<br><br>');
}

if (dependencies(dependenciesFetch('docs')))
{
	echo('docs module ok<br><br>');
}

if (dependencies(dependenciesFetch('entity')))
{
	echo('entity module ok<br><br>');
}

if (dependencies(dependenciesFetch('entity/actions')))
{
	echo('entity actions ok<br><br>');
}

if (dependencies(dependenciesFetch('entity/beforeafter')))
{
	echo('entity beforeafter ok<br><br>');
}

if (dependencies(dependenciesFetch('entity/filters')))
{
	echo('entity filters ok<br><br>');
}

if (dependencies(dependenciesFetch('entity/flags')))
{
	echo('entity flags ok<br><br>');
}

if (dependencies(dependenciesFetch('entity/formatters')))
{
	echo('entity formatters ok<br><br>');
}

if (dependencies(dependenciesFetch('entity/headers')))
{
	echo('entity headers ok<br><br>');
}

if (dependencies(dependenciesFetch('esb')))
{
	echo('esb module ok<br><br>');
}

if (dependencies(dependenciesFetch('import')))
{
	echo('import module ok<br><br>');
}

if (dependencies(dependenciesFetch('integration')))
{
	echo('integration module ok<br><br>');
}

if (dependencies(dependenciesFetch('msg')))
{
	echo('message module ok<br><br>');
}

if (dependencies(dependenciesFetch('poc')))
{
	echo('poc module ok<br><br>');
}

// if (dependencies(dependenciesFetch('plugins/authenticationplugins')))
// {
	// echo('authenticationplugins module ok<br><br>');
// }

if (dependencies(dependenciesFetch('plugins/cryptplugins')))
{
	echo('cryptplugins module ok<br><br>');
}

if (dependencies(dependenciesFetch('plugins/paymentplugins')))
{
	echo('paymentplugins module ok<br><br>');
}

if (dependencies(dependenciesFetch('process')))
{
	echo('process module ok<br><br>');
}

if (dependencies(dependenciesFetch('projly')))
{
	echo('projly module ok<br><br>');
}

if (dependencies(dependenciesFetch('public')))
{
	echo('public module ok<br><br>');
}

if (dependencies(dependenciesFetch('report')))
{
	echo('report module ok<br><br>');
}

if (dependencies(dependenciesFetch('security')))
{
	echo('security module ok<br><br>');
}

if (dependencies(dependenciesFetch('setting')))
{
	echo('setting module ok<br><br>');
}

if (dependencies(dependenciesFetch('sync')))
{
	echo('sync module ok<br><br>');
}

if (dependencies(dependenciesFetch('utils')))
{
	echo('utils module ok<br><br>');
}

echo('password encryption method: ' . DECRYPTION_TYPE_PASSWORD2WAY . '<br>');
$strEncrypted = encryptPassword2Way('system', 'sysadmin', strtolower('passw0rd'));
echo('password encryption test: ' . $strEncrypted . '<br>');
$strDecrypted = decryptPassword2Way('system', 'sysadmin', $strEncrypted);
echo('password decryption test: ' . $strDecrypted . '<br>');

echo('<br>');
echo('id encryption method: ' . DECRYPTION_TYPE_ID2WAY . '<br>');
$strEncrypted = encryptid('1234567890');
echo('id encryption test: ' . $strEncrypted . '<br>');
$strDecrypted = decryptid($strEncrypted);
echo('id decryption test: ' . $strDecrypted . '<br>');

echo('<br>');
echo('checks finished<br>');

//debug("debugger is on");	// this fails if PHP debugger is off

?>