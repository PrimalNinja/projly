<?php

function getFormatter_product($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"applications" => $arrRowUnformatted_a['gffe35a8ad_d290_4ae3_8800_f2935dd30d07_applications'],
		"producttype" => $arrRowUnformatted_a['gffe35a8ad_d290_4ae3_8800_f2935dd30d07_producttype'],
        "requirements" => $arrRowUnformatted_a['gffe35a8ad_d290_4ae3_8800_f2935dd30d07_requirements'],
        "behaviourcategory" => $arrRowUnformatted_a['gffe35a8ad_d290_4ae3_8800_f2935dd30d07_behaviourcategory'],
		"workqueueitemtype" => $arrRowUnformatted_a['gffe35a8ad_d290_4ae3_8800_f2935dd30d07_workqueueitemtype'],
		"displayorder" => $arrRowUnformatted_a['gffe35a8ad_d290_4ae3_8800_f2935dd30d07_displayorder'],
		"priceexgst" => $arrRowUnformatted_a['gffe35a8ad_d290_4ae3_8800_f2935dd30d07_priceexgst'],
		"priceincgst" => $arrRowUnformatted_a['gffe35a8ad_d290_4ae3_8800_f2935dd30d07_priceincgst'],
		"profilelist" => $arrRowUnformatted_a['gffe35a8ad_d290_4ae3_8800_f2935dd30d07_profilelist'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
