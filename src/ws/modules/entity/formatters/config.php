<?php

function getFormatter_config($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
        "command" => $arrRowUnformatted_a["gb018840e_bc4f_45fc_b8a6_2c6338ca1be1_command"],
        "parameters" => $arrRowUnformatted_a["gb018840e_bc4f_45fc_b8a6_2c6338ca1be1_parameters"],
        "isprocessed" => $arrRowUnformatted_a["gb018840e_bc4f_45fc_b8a6_2c6338ca1be1_is_processed"],
        "starttime" => $arrRowUnformatted_a["gb018840e_bc4f_45fc_b8a6_2c6338ca1be1_starttime"],
        "endtime" => $arrRowUnformatted_a["gb018840e_bc4f_45fc_b8a6_2c6338ca1be1_endtime"],
        "isready" => $arrRowUnformatted_a["gb018840e_bc4f_45fc_b8a6_2c6338ca1be1_is_ready"],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
