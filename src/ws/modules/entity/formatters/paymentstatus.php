<?php

function getFormatter_paymentstatus($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"displayorder" => $arrRowUnformatted_a['ffb6a06aec_dd39_4675_826e_4c434f1e886f_displayorder'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
