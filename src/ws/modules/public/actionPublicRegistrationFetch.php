<?php

// fetch registration details for confirmation page
function actionPublicRegistrationFetch($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a, $strDeviceIDCookie_a)
{
	// permission check
	if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}
	
	$arrResult[] = array(
		"clientcode" => "",
		"login" => ""
	);

	if (isset($_SESSION['last_clientcode']))
	{
		$arrResult[0]["clientcode"] = $_SESSION['last_clientcode'];
	}

	if (isset($_SESSION['last_login']))
	{
		$arrResult[0]["login"] = $_SESSION['last_login'];
	}
	
    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
