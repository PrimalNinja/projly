<?php

function fetchProjlyCharts($objConn_a) 
{
    $arrResult = array();

    $arrChartNames = ['issues_lackofthroughput_past_year', 'issues_lackofthroughput_per_project_past_year', 'issues_unresolved_by_project', 'issues_unresolved_per_project'];

    if (count($arrChartNames) > 0)
    {         
        foreach ($arrChartNames as $strChartName)
        {             
            $strJSONFile = WS_PATH . 'modules/projly/charts/' . $strChartName . '.json';
            
            if (file_exists($strJSONFile)) 
            { 
				$strChart = file_get_contents($strJSONFile);
				$arrResult[$strChartName] = json_decode($strChart, true);
            }                         
        }
    }    
    
    return $arrResult;
}
