<?php

function getFormatter_integrationoutbound($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
    //$arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
        
    $strFormEntityCode = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'ENTITY' );
	$strVersion = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'DATAHEADER', 'DATAVERSION' );
    
    $arrResult = array(
		"id" => secureValue($strEntityType_a . ffel($strEntityCode_a), $arrRowUnformatted_a['id']),
		"rownum" => $intRowNum_a,
        "formentitycode" => $strFormEntityCode,
        "code" => $arrRowUnformatted_a['code'],
        "description" => $arrRowUnformatted_a['description'],
        //"is_enabled" => $arrRowUnformatted_a['is_enabled'],
		"version" => $strVersion,
		"isenabled" => $arrRowUnformatted_a['is_enabled'],
		"modified" => getDateStringOut($arrRowUnformatted_a['modifydatetime']),

        // put custom fields here
        "outboundtype" => $arrRowUnformatted_a['g561d6970_cd14_4e7b_8ac4_460ae9f6a750_integrationoutboundtype'],
		"authenticationtype" => $arrRowUnformatted_a['g561d6970_cd14_4e7b_8ac4_460ae9f6a750_authenticationtype'],
        "parameters" => $arrRowUnformatted_a['g561d6970_cd14_4e7b_8ac4_460ae9f6a750_parameters'],
		

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
