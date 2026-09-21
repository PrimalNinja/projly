<?php

function actionCartFetchCMSThankyou($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
    $arrResult = array();

    // permission check
    if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) { return false; }
    
	if (dependencies('core/fetchCMSContent'))
	{
		$strContent = fetchCMSContent($objConn_a, 'PAYMENTTHANKYOU');
		$arrResult[] = array('content' => $strContent);
	}

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
