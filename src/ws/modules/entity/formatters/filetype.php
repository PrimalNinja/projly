<?php

function getFormatter_filetype($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"is_definable" => $arrRowUnformatted_a['gc5913c98_7aff_4753_9b43_6f388e7b707f_is_definable'],
		"is_import" => $arrRowUnformatted_a['gc5913c98_7aff_4753_9b43_6f388e7b707f_is_import'],
		"is_simpleimport" => $arrRowUnformatted_a['gc5913c98_7aff_4753_9b43_6f388e7b707f_is_simpleimport'],
		"is_export" => $arrRowUnformatted_a['gc5913c98_7aff_4753_9b43_6f388e7b707f_is_export'],
		"applications" => $arrRowUnformatted_a['gc5913c98_7aff_4753_9b43_6f388e7b707f_applications'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
