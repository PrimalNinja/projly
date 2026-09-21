<?php

function getFormatter_clientproduct($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"applicantname" => $arrRowUnformatted_a['ff252aa5f0_e8df_4c52_a5c4_de677e1edf85_applicantname'],
		"product" => $arrRowUnformatted_a['ff252aa5f0_e8df_4c52_a5c4_de677e1edf85_product'],
		"purchasedate" => getDateStringOut($arrRowUnformatted_a['ff252aa5f0_e8df_4c52_a5c4_de677e1edf85_purchasedate']),
		"expirydate" => getDateStringOut($arrRowUnformatted_a['ff252aa5f0_e8df_4c52_a5c4_de677e1edf85_expirydate']),
		"paymentstatus" => $arrRowUnformatted_a['ff252aa5f0_e8df_4c52_a5c4_de677e1edf85_paymentstatus'],
		"paymentdate" => getDateStringOut($arrRowUnformatted_a['ff252aa5f0_e8df_4c52_a5c4_de677e1edf85_paymentdate']),

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
