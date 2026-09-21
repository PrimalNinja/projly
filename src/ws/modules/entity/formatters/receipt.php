<?php

function getFormatter_receipt($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"invoicenumber" => $arrRowUnformatted_a['ff6b4c87cd_9b74_40de_9375_83f280731846_invoicenumber'],
		"invoicedate" => getDateStringOut($arrRowUnformatted_a['ff6b4c87cd_9b74_40de_9375_83f280731846_invoicedate']),
		"billingbillto" => $arrRowUnformatted_a['ff6b4c87cd_9b74_40de_9375_83f280731846_billingbillto'],
		"billingsuburb" => $arrRowUnformatted_a['ff6b4c87cd_9b74_40de_9375_83f280731846_billingsuburb'],
		"billingstate" => $arrRowUnformatted_a['ff6b4c87cd_9b74_40de_9375_83f280731846_billingstate'],
		"billingpostcode" => $arrRowUnformatted_a['ff6b4c87cd_9b74_40de_9375_83f280731846_billingpostcode'],
		"abn" => $arrRowUnformatted_a['ff6b4c87cd_9b74_40de_9375_83f280731846_abn'],
        "file_id" => secureValue($strEntityType_a . "DOCUMENT", $arrRowUnformatted_a['document_id']),
		"subtotal" => $arrRowUnformatted_a['ff6b4c87cd_9b74_40de_9375_83f280731846_subtotal'],
		"gst" => $arrRowUnformatted_a['ff6b4c87cd_9b74_40de_9375_83f280731846_gst'],
		"total" => $arrRowUnformatted_a['ff6b4c87cd_9b74_40de_9375_83f280731846_total'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
