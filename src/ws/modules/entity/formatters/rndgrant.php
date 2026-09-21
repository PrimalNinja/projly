<?php

function getFormatter_rndgrant($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
        //"description" => $arrRowUnformatted_a['description'],
		"version" => $strVersion,
		"isenabled" => $arrRowUnformatted_a['is_enabled'],
		"modified" => getDateStringOut($arrRowUnformatted_a['modifydatetime']),

		// put custom fields here
		"description" => $arrRowUnformatted_a['g6677fd35_4344_4a1f_8947_fc7bac647014_description'],
		"projectparticipant" => $arrRowUnformatted_a['g6677fd35_4344_4a1f_8947_fc7bac647014_projectparticipant'],
		"dateapplied" => getDateStringOut($arrRowUnformatted_a['g6677fd35_4344_4a1f_8947_fc7bac647014_dateapplied']),
		"dateapproved" => getDateStringOut($arrRowUnformatted_a['g6677fd35_4344_4a1f_8947_fc7bac647014_dateapproved']),
		"dategranted" => getDateStringOut($arrRowUnformatted_a['g6677fd35_4344_4a1f_8947_fc7bac647014_dategranted']),

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
