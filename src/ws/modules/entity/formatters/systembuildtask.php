<?php

function getFormatter_systembuildtask($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
    $arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
	       
    $strFormEntityCode = formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'ENTITY' );
	$strVersion = formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'FORMVERSION' );

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
        "command" => $arrRowUnformatted_a['gd8fac874_b432_4019_b5b0_abefded76d05_command'],
        "parameters" => $arrRowUnformatted_a['gd8fac874_b432_4019_b5b0_abefded76d05_parameters'],
        "isprocessed" => $arrRowUnformatted_a['gd8fac874_b432_4019_b5b0_abefded76d05_is_processed'],
        "starttime" => $arrRowUnformatted_a['gd8fac874_b432_4019_b5b0_abefded76d05_starttime'],
        "endtime" => $arrRowUnformatted_a['gd8fac874_b432_4019_b5b0_abefded76d05_endtime'],
        //"sortorder" => $arrRowUnformatted_a['sortorder'],
        //"sortorder2" => $arrRowUnformatted_a['g4768521c_4d28_41fe_a8f8_6e0ec6cfee71_sortorder2'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
