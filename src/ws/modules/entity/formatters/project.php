<?php

function getFormatter_project($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"projecttype" => $arrRowUnformatted_a['ff304902c1_b136_4ea9_b67d_72eecc322697_projecttype'],
		"associatedproject" => $arrRowUnformatted_a['ff304902c1_b136_4ea9_b67d_72eecc322697_associatedproject'],
		"projectpriority" => $arrRowUnformatted_a['ff304902c1_b136_4ea9_b67d_72eecc322697_projectpriority'],
		"projectstakeholder" => $arrRowUnformatted_a['ff304902c1_b136_4ea9_b67d_72eecc322697_projectstakeholder'],
		"projectstatus" => $arrRowUnformatted_a['ff304902c1_b136_4ea9_b67d_72eecc322697_projectstatus'],
		"startdate" => getDateStringOut($arrRowUnformatted_a['ff304902c1_b136_4ea9_b67d_72eecc322697_startdate']),
		"targetdate" => getDateStringOut($arrRowUnformatted_a['ff304902c1_b136_4ea9_b67d_72eecc322697_targetdate']),

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
