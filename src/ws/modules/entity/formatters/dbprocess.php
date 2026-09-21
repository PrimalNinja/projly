<?php

function getFormatter_dbprocess($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
    //$arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
        
	// note: the default doesn't read from the jsondata as there may not be any
    $strFormEntityCode = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'ENTITY' );
	$strVersion = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'DATAHEADER', 'DATAVERSION' );
    
    $arrResult = array(
		"id" => secureValue($strEntityType_a . ffel($strEntityCode_a), $arrRowUnformatted_a['id']),
		"rownum" => $intRowNum_a,
        //"DT_RowId" => "row_" . $intRowNum_a, // for datatables
        "formentitycode" => $strFormEntityCode,
        "code" => "",
        "description" => "",
		"version" => $strVersion,
		"isenabled" => "",
		"modified" => "",

		// put custom fields here
		"processid" => $arrRowUnformatted_a['g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_id'],
		"user" => $arrRowUnformatted_a['g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_user'],
		"host" => $arrRowUnformatted_a['g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_host'],
		"db" => $arrRowUnformatted_a['g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_db'],
		"command" => $arrRowUnformatted_a['g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_command'],
		"time" => $arrRowUnformatted_a['g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_time'],
		"state" => $arrRowUnformatted_a['g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_state'],
		"info" => $arrRowUnformatted_a['g24e3d61f_aa39_4d5a_b654_cc55e2d69fc9_info'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
