<?php

function getFormatter_reminder($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
    $arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
        
    $strFormEntityCode = formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'ENTITY' );
	$strVersion = formValueGetBySectionTypeFieldCode( $arrJSONData, 'DATAHEADER', 'DATAVERSION' );
	
	$strType = "Standard Reminder";
    
    $arrResult = array(
		"id" => secureValue($strEntityType_a . ffel($strEntityCode_a), $arrRowUnformatted_a['id']),
		"rownum" => $intRowNum_a,
        "formentitycode" => $strFormEntityCode,
        "code" => $arrRowUnformatted_a['code'],
        "description" => $arrRowUnformatted_a['adee66ca_8443_4e3e_8ddf_b5a480bf2b49_description'],
		"version" => $strVersion,
		"isenabled" => $arrRowUnformatted_a['is_enabled'],
		"modified" => getDateStringOut($arrRowUnformatted_a['modifydatetime']),

		// put custom fields here
		"date" => getDateStringOut($arrRowUnformatted_a['adee66ca_8443_4e3e_8ddf_b5a480bf2b49_date']),
		"time" => $arrRowUnformatted_a['adee66ca_8443_4e3e_8ddf_b5a480bf2b49_time'],
        "message" => $arrRowUnformatted_a['adee66ca_8443_4e3e_8ddf_b5a480bf2b49_message'],
		"type" => $strType,

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
