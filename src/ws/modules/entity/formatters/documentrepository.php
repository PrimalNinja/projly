<?php

function getFormatter_documentrepository($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"documentretentiontype" => $arrRowUnformatted_a['g13c0b91f_45bd_492c_8b47_ce82b1757837_documentretentiontype'],
		"documentcount" => $arrRowUnformatted_a['g13c0b91f_45bd_492c_8b47_ce82b1757837_documentcount'],
		"storageused" => $arrRowUnformatted_a['g13c0b91f_45bd_492c_8b47_ce82b1757837_storageused'],
		"deletedstoragesize" => $arrRowUnformatted_a['g13c0b91f_45bd_492c_8b47_ce82b1757837_deletestoragesize'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
