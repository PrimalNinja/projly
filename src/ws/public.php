<?php

require_once('inc-env.php');
require_once('inc-constants.php');
require_once('inc-settings.php');
require_once('inc-temporaryvalues.php');
require_once('inc-app-' . APP_CODE . '.php');

$SERVICETYPE = '';
if (isset($_GET['servicetype']))
{
	if ($_GET['servicetype'] == 'barcode')
	{
		$SERVICETYPE = 'barcode';
	}
}

if ($SERVICETYPE == 'barcode')
{
	require_once(WS_PATH . 'modules/3p/barcode.php');
}

?>