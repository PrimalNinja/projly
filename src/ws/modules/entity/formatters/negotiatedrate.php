<?php

function getFormatter_negotiatedrate($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"product" => $arrRowUnformatted_a['g56ec2ec9_a7c4_43cc_a6ac_9f6a710e3e94_product'],
        "forclient" => $arrRowUnformatted_a['g56ec2ec9_a7c4_43cc_a6ac_9f6a710e3e94_forclient'],
        "percent" => $arrRowUnformatted_a['g56ec2ec9_a7c4_43cc_a6ac_9f6a710e3e94_percent'],
        "dollar" => $arrRowUnformatted_a['g56ec2ec9_a7c4_43cc_a6ac_9f6a710e3e94_dollar'],
        "priority" => $arrRowUnformatted_a['g56ec2ec9_a7c4_43cc_a6ac_9f6a710e3e94_priority'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
