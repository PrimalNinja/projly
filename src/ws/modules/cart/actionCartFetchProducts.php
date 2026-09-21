<?php

// fetch purchasable products
function actionCartFetchProducts($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $arrResult = array();

    // permission check
    if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) { return false; }

    // parameters
	$strFilter = getJSONParameter($arrParameters_a, 'filter');

    // initialisations
    $strClientID = $_SESSION['server_loggedin_clientid'];
	
	if (dependencies('cart/fetchProducts'))
	{
		$arrResult = fetchProducts($objConn_a, $strClientID, $strFilter, true);
	}

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
