<?php

function actionSecurityFetchCharts($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{	
    $arrCharts = array();
    
	if (dependencies('security/fetchSecurityCharts'))
	{
		$arrCharts = fetchSecurityCharts($objConn_a);
	}
    
	return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrCharts);
}
