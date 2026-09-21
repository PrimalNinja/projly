<?php

function getFormatter_projectparticipantactivity($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"projectparticipant" => $arrRowUnformatted_a['ff27b72e84_73b1_4771_bfe5_c908f2fe50e2_projectparticipant'],
		"projecttask" => $arrRowUnformatted_a['ff27b72e84_73b1_4771_bfe5_c908f2fe50e2_projecttask'],
		"roster" => $arrRowUnformatted_a['ff27b72e84_73b1_4771_bfe5_c908f2fe50e2_roster'],
		"activitydate" => getDateStringOut($arrRowUnformatted_a['ff27b72e84_73b1_4771_bfe5_c908f2fe50e2_activitydate']),
		"starttime" => $arrRowUnformatted_a['ff27b72e84_73b1_4771_bfe5_c908f2fe50e2_starttime'],
		"endtime" => $arrRowUnformatted_a['ff27b72e84_73b1_4771_bfe5_c908f2fe50e2_endtime'],
		"totalhours" => $arrRowUnformatted_a['ff27b72e84_73b1_4771_bfe5_c908f2fe50e2_totalhours'],
		"totalminutes" => $arrRowUnformatted_a['ff27b72e84_73b1_4771_bfe5_c908f2fe50e2_totalminutes'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
