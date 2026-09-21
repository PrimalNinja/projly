<?php

function getFormatter_sprint($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
    //$arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
        
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
		"plannedstartdate" => $arrRowUnformatted_a['ga627d4c2_b700_48ab_8722_e19c42b41036_plannedstartdate'],
		"startdate" => getDateStringOut($arrRowUnformatted_a['ga627d4c2_b700_48ab_8722_e19c42b41036_startdate']),
		"plannedenddate" => getDateStringOut($arrRowUnformatted_a['ga627d4c2_b700_48ab_8722_e19c42b41036_plannedenddate']),
		"enddate" => getDateStringOut($arrRowUnformatted_a['ga627d4c2_b700_48ab_8722_e19c42b41036_enddate']),
        "estimatedhours" => $arrRowUnformatted_a['ga627d4c2_b700_48ab_8722_e19c42b41036_estimatedhours'],
        "estimatedminutes" => $arrRowUnformatted_a['ga627d4c2_b700_48ab_8722_e19c42b41036_estimatedminutes'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
