<?php

function getFormatter_systemmoduletask($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
        "command" => $arrRowUnformatted_a['g4768521c_4d28_41fe_a8f8_6e0ec6cfee71_command'],
        "parameters" => $arrRowUnformatted_a['g4768521c_4d28_41fe_a8f8_6e0ec6cfee71_parameters'],
        "isprocessed" => $arrRowUnformatted_a['g4768521c_4d28_41fe_a8f8_6e0ec6cfee71_is_processed'],
        "starttime" => $arrRowUnformatted_a['g4768521c_4d28_41fe_a8f8_6e0ec6cfee71_starttime'],
        "endtime" => $arrRowUnformatted_a['g4768521c_4d28_41fe_a8f8_6e0ec6cfee71_endtime'],
        //"sortorder" => $arrRowUnformatted_a['sortorder'],
        //"sortorder2" => $arrRowUnformatted_a['g4768521c_4d28_41fe_a8f8_6e0ec6cfee71_sortorder2'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
