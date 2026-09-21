<?php

function getFormatter_negotiateddiscount($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
	$arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
        
    $strFormEntityCode = formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'ENTITY' );
	$strVersion = formValueGetBySectionTypeFieldCode( $arrJSONData, 'DATAHEADER', 'DATAVERSION' );
	
    $arrResult = array(
		"id" => secureValue($strEntityType_a . ffel($strEntityCode_a), $arrRowUnformatted_a['id']),
		"rownum" => $intRowNum_a,
        "formentitycode" => $strFormEntityCode,
        "code" => $arrRowUnformatted_a['code'],
        "description" => $arrRowUnformatted_a['description'],
		"version" => $strVersion,
		"isenabled" => $arrRowUnformatted_a['is_enabled'],
		"modified" => getDateStringOut($arrRowUnformatted_a['modifydatetime']),

		// put custom fields here
		"product" => $arrRowUnformatted_a['g5ca0bf62_1e0b_4d30_ba0b_c531b5e79e71_product'],
        "forclient" => $arrRowUnformatted_a['g5ca0bf62_1e0b_4d30_ba0b_c531b5e79e71_forclient'],
        "percent" => $arrRowUnformatted_a['g5ca0bf62_1e0b_4d30_ba0b_c531b5e79e71_percent'],
        "dollar" => $arrRowUnformatted_a['g5ca0bf62_1e0b_4d30_ba0b_c531b5e79e71_dollar'],
        "priority" => $arrRowUnformatted_a['g5ca0bf62_1e0b_4d30_ba0b_c531b5e79e71_priority'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
