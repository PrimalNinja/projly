<?php

function getFormatter_fileformattype($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"version" => $strVersion,
		"isenabled" => $arrRowUnformatted_a['is_enabled'],
		"modified" => getDateStringOut($arrRowUnformatted_a['modifydatetime']),

		// put custom fields here
		"is_userdefined" => $arrRowUnformatted_a['g60c467c2_432e_4dff_90f2_4963ae121143_is_userdefined'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
