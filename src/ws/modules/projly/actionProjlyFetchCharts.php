<?php

function actionProjlyFetchCharts($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{
    $arrCharts = array();

	if (dependencies('projly/fetchProjlyCharts'))
	{
		$arrCharts = fetchProjlyCharts($objConn_a);
	}
    
	return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrCharts);
}
