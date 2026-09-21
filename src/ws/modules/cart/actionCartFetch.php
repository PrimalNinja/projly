<?php

function actionCartFetch($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $arrResult = array();

    // permissions
    if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

    // parameters

    // initialisations
    $strClientID = $_SESSION['server_loggedin_clientid'];

	if (dependencies('cart/fetchCart'))
	{
		$arrResult = fetchCart($objConn_a, $strClientID, true);
	}

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
