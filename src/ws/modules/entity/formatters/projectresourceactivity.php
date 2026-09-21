<?php

function getFormatter_projectresourceactivity($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
    $arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
        
    $strFormEntityCode = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'ENTITY' );
	$strVersion = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'DATAHEADER', 'DATAVERSION' );
    
    $arrResult = array(
		"id" => secureValue($strEntityType_a . ffel($strEntityCode_a), $arrRowUnformatted_a['id']),
		"rownum" => $intRowNum_a,
        //"DT_RowId" => "row_" . $intRowNum_a, // for datatables
        "formentitycode" => $strFormEntityCode,
        "code" => $arrRowUnformatted_a['code'],
        "description" => $arrRowUnformatted_a['description'],
		"version" => $strVersion,
		"isenabled" => $arrRowUnformatted_a['is_enabled'],
		"modified" => getDateStringOut($arrRowUnformatted_a['modifydatetime']),

		// put custom fields here
		"projectresource" => $arrRowUnformatted_a['ff190fe024_9e8f_4815_a4e0_5f5dfd7a1e65_projectresource'],
		"projecttask" => $arrRowUnformatted_a['ff190fe024_9e8f_4815_a4e0_5f5dfd7a1e65_projecttask'],
		"activitydate" => getDateStringOut($arrRowUnformatted_a['ff190fe024_9e8f_4815_a4e0_5f5dfd7a1e65_activitydate']),
		"starttime" => getDateStringOut($arrRowUnformatted_a['ff190fe024_9e8f_4815_a4e0_5f5dfd7a1e65_starttime']),
		"endtime" => $arrRowUnformatted_a['ff190fe024_9e8f_4815_a4e0_5f5dfd7a1e65_endtime'],
		"totalhours" => $arrRowUnformatted_a['ff190fe024_9e8f_4815_a4e0_5f5dfd7a1e65_totalhours'],
		"totalminutes" => $arrRowUnformatted_a['ff190fe024_9e8f_4815_a4e0_5f5dfd7a1e65_totalminutes'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
