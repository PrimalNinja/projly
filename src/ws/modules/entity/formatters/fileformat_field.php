<?php

function getFormatter_fileformat_field($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"filedatatype" => $arrRowUnformatted_a['gdca0c616_9e33_4f80_adb1_c964e9f44713_filedatatype'],
		"position" => $arrRowUnformatted_a['gdca0c616_9e33_4f80_adb1_c964e9f44713_position'],
		"length" => $arrRowUnformatted_a['gdca0c616_9e33_4f80_adb1_c964e9f44713_length'],
		"multiplier" => $arrRowUnformatted_a['gdca0c616_9e33_4f80_adb1_c964e9f44713_multiplier'],
		"is_mandatory" => $arrRowUnformatted_a['gdca0c616_9e33_4f80_adb1_c964e9f44713_is_mandatory'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
