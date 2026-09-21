<?php

// fetch an account
function actionCoreABNLookup($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $arrResult = array();

    // permission check
    if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) { return false; }

    if (dependencies('3p/ABNLookup/abnLookup')) 
	{
        // parameters
        $arrFilter = getJSONParameter($arrParameters_a, 'filter');
        $arrOrder = getJSONParameter($arrParameters_a, 'order');

        $strABN = $arrFilter[0]['value'];
        $arrResult = abnLookup(ABNLOOKUPGUID, $strABN);
    }

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}

