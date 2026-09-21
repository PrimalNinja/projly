<?php

function getFormatter_transactionpending($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
    //$arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
        
    $strFormEntityCode = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'ENTITY' );
	$strVersion = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'DATAHEADER', 'DATAVERSION' );
    
    $arrResult = array(
		"id" => secureValue($strEntityType_a . ffel($strEntityCode_a), $arrRowUnformatted_a['id']),
		"rownum" => $intRowNum_a,
        //"DT_RowId" => "row_" . $intRowNum_a, // for datatables
        "formentitycode" => $strFormEntityCode,
        "code" => $arrRowUnformatted_a['code'],
        "description" => $arrRowUnformatted_a['description'],
		"version" => $strVersion,
		"isenabled" => $arrRowUnformatted_a['is_enabled'],
		"modified" => getDateStringOut($arrRowUnformatted_a['modifydatetime']),

		// put custom fields here
		//"created" => getDateStringOut($arrRowUnformatted_a['createdatetime']),
		"receiptnumber" => $arrRowUnformatted_a['g31f68ccc_e168_40b8_a7db_693353aa7aea_receiptnumber'],
		"paymentmethod" => $arrRowUnformatted_a['g31f68ccc_e168_40b8_a7db_693353aa7aea_paymentmethod'],
		"is_paid" => $arrRowUnformatted_a['g31f68ccc_e168_40b8_a7db_693353aa7aea_is_paid'],
		"priceexgst" => $arrRowUnformatted_a['g31f68ccc_e168_40b8_a7db_693353aa7aea_priceexgst'],
		"priceincgst" => $arrRowUnformatted_a['g31f68ccc_e168_40b8_a7db_693353aa7aea_priceincgst'],
		"paymentstatus" => $arrRowUnformatted_a['g31f68ccc_e168_40b8_a7db_693353aa7aea_paymentstatus'],
		"statusdescription" => $arrRowUnformatted_a['g31f68ccc_e168_40b8_a7db_693353aa7aea_statusdescription'],
		"payername" => $arrRowUnformatted_a['g31f68ccc_e168_40b8_a7db_693353aa7aea_payername'],
		"notes" => $arrRowUnformatted_a['g31f68ccc_e168_40b8_a7db_693353aa7aea_notes'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
