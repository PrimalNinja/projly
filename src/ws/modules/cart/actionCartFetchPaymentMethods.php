<?php

// fetch active payment methods
function actionCartFetchPaymentMethods($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $arrResult = array();

    // permission check
    if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

    // parameters

	if (dependencies('cart/fetchPaymentMethods'))
	{
		$arrResult = fetchPaymentMethods($objConn_a, true);
	}

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
