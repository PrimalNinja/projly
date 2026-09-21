<?php

function getFormatter_fileformat($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"fileformattemplate" => $arrRowUnformatted_a['g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_fileformattemplate'],
		"formattype" => $arrRowUnformatted_a['g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_fileformattype'],
		"headerrows" => $arrRowUnformatted_a['g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_headerrows'],
		"is_import" => $arrRowUnformatted_a['g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_is_import'],
		"is_export" => $arrRowUnformatted_a['g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_is_export'],
		"is_default" => $arrRowUnformatted_a['g5ab8ab01_9cdd_450d_b439_de0d09dfeea5_is_default'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
