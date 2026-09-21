<?php

function getFormatter_systembuild($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
        'folder' => $arrRowUnformatted_a['g3addd7a5_6125_499d_b87a_c827161eae5f_folder'],
        'date' => $arrRowUnformatted_a['g3addd7a5_6125_499d_b87a_c827161eae5f_date'],
        'time' => $arrRowUnformatted_a['g3addd7a5_6125_499d_b87a_c827161eae5f_time'],
        'status' => $arrRowUnformatted_a['g3addd7a5_6125_499d_b87a_c827161eae5f_status'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
