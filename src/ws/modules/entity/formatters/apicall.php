<?php

function getFormatter_apicall($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
    //$arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
        
    $strFormEntityCode = "N/A"; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'ENTITY' );
	$strVersion = "N/A"; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'DATAHEADER', 'DATAVERSION' );
    
    $arrResult = array(
		"id" => secureValue($strEntityType_a . ffel($strEntityCode_a), $arrRowUnformatted_a['id']),
		"rownum" => $intRowNum_a,
        //"DT_RowId" => "row_" . $intRowNum_a, // for datatables
        "formentitycode" => $strFormEntityCode,
        "code" => $arrRowUnformatted_a['code'],
        "description" => $arrRowUnformatted_a['description'],
		"version" => $strVersion,

		// put custom fields here
		"datetime" => $arrRowUnformatted_a['modifydatetime'],
		"clientname" => $arrRowUnformatted_a['ga0c53bc1_9f46_489f_be5e_09c27f7ef3f6_clientname'],
		"apicalltype" => $arrRowUnformatted_a['ga0c53bc1_9f46_489f_be5e_09c27f7ef3f6_apicalltype'],
		"subtype" => $arrRowUnformatted_a['ga0c53bc1_9f46_489f_be5e_09c27f7ef3f6_subtype'],
		"callcount" => $arrRowUnformatted_a['ga0c53bc1_9f46_489f_be5e_09c27f7ef3f6_callcount'],
		"issuccessful" => $arrRowUnformatted_a['ga0c53bc1_9f46_489f_be5e_09c27f7ef3f6_issuccessful'],
		"notes" => $arrRowUnformatted_a['ga0c53bc1_9f46_489f_be5e_09c27f7ef3f6_notes'],
		
		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
