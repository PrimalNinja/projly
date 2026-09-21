<?php

function fetchSecurityCharts($objConn_a) 
{	
    $arrResult = array();

    $arrChartNames = ['login_failed_past2weeks', 'logins_past4weeks', 'registrations_all', 'registrations_past4weeks'];

    if (count($arrChartNames) > 0)
    {         
        foreach ($arrChartNames as $strChartName)
        {             
            $strJSONFile = WS_PATH . 'modules/security/charts/' . $strChartName . '.json';
            
            if (file_exists($strJSONFile)) 
            { 
				$strChart = file_get_contents($strJSONFile);
				$arrResult[$strChartName] = json_decode($strChart, true);
            }                         
        }
    }    
    
    return $arrResult;
}
