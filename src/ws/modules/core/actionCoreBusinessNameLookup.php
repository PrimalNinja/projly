<?php

// fetch an account
function actionCoreBusinessNameLookup($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $arrResult = array();

    // permission check
    if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) { return false; }

    if (dependencies('3p/ABNLookup/businessNameLookup')) 
	{
        // parameters
        $arrFilter = getJSONParameter($arrParameters_a, 'filter');
        $arrOrder = getJSONParameter($arrParameters_a, 'order');

        $strBusinessName = $arrFilter[0]['value'];
        $arrResult = businessNameLookup(ABNLOOKUPGUID, $strBusinessName);
    }

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
