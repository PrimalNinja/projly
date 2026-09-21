<?php

// process a command
function actionProcessCommand($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $arrResult = array();

    // permission check
    if (!hasPermission($objConn_a, 'DEVELOPER', __FUNCTION__, true)) { return false; }

    if (dependencies('system/rpncli') &&
		dependencies(dependenciesFetch('system/rpncli', false))
		) 
	{
		// parameters
		$strCommand = getJSONParameter($arrParameters_a, 'command');

		// initialisations
		$strClientID = $_SESSION['server_loggedin_clientid'];

		// as we don't need to create commands with this, let's just create the entry point automatically and exit point
		$strCommand = 'main:' . $strCommand . ' .';
		
		$objRPNCLI = new rpncli();

		// register commands
		
		// CLIENT TESTS TODO: missing modules, permission table check also, linting, creating the minify and delete z batch files - then automate it for DEV during first form opens
		// CLIENT TESTS DONE: permissions/capabilities/modules/properties in a form, missing js, z, htm, missing json files

		// SERVER TESTS TODO: permissions in a ws, missing permission files, the php bridge (using a check that doesn't use the bridge itself), linting
		// SERVER TESTS DONE: 
		
		$objRPNCLI->registerCommands(['clientcheck', 'forms', 'servercheck']);
		$objRPNCLI->registerCommands(['add', 'divide', 'multiply', 'square', 'subtract']);
		
		$arr = $objRPNCLI->prepare($strCommand);
		$arrResult = $objRPNCLI->execute($arr);
	}
	
    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
