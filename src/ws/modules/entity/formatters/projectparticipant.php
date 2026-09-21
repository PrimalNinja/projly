<?php

function getFormatter_projectparticipant($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"projectstakeholder" => $arrRowUnformatted_a['fff8db5be0_1045_441f_b7a2_769fd8de46f0_projectstakeholder'],
		"participanttype" => $arrRowUnformatted_a['fff8db5be0_1045_441f_b7a2_769fd8de46f0_projectparticipanttype'],
		"costcategory" => $arrRowUnformatted_a['fff8db5be0_1045_441f_b7a2_769fd8de46f0_projectcostcategory'],
		"billingcategory" => $arrRowUnformatted_a['fff8db5be0_1045_441f_b7a2_769fd8de46f0_projectbillingcategory'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
