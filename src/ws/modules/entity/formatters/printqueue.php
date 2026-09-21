<?php

function getFormatter_printqueue($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
    $arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
        
	// note: the default doesn't read from the jsondata as there may not be any
    $strFormEntityCode = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'ENTITY' );
	$strVersion = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'DATAHEADER', 'DATAVERSION' );
    
    $arrResult = array(
		"id" => secureValue($strEntityType_a . ffel($strEntityCode_a), $arrRowUnformatted_a['id']),
		"rownum" => $intRowNum_a,
        "formentitycode" => $strFormEntityCode,
		"code" => $arrRowUnformatted_a['gff9b32c5_96c5_4850_8834_3ed24e70d727_code'],
		"description" => $arrRowUnformatted_a['gff9b32c5_96c5_4850_8834_3ed24e70d727_description'],
		"is_enabled" => $arrRowUnformatted_a['is_enabled'],
        
		"is_public" => $arrRowUnformatted_a['is_public'],
		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
