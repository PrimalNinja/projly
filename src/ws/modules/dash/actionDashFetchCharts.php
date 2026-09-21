<?php

function actionDashFetchCharts($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{	
    $arrCharts = array();

	if (dependencies('fms/fetchFMSCharts'))
	{
		// fetch the FMS charts
		$arrCharts = fetchFMSCharts($objConn_a);
	}
    
	return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrCharts);
}