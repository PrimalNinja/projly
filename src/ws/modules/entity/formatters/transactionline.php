<?php

function getFormatter_transactionline($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
    //$arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
        
    $strFormEntityCode = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'ENTITY' );
	$strVersion = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'DATAHEADER', 'DATAVERSION' );
    
	$strStatus = "Not Yet Delivered";
	$strApplicantProductID = $arrRowUnformatted_a['applicantproduct_id'];
	if (strlen($strApplicantProductID) > 0)
	{
		$strStatus = "Delivered";
	}
	
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
		"workqueuecode" => $arrRowUnformatted_a['gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_workqueuecode'],
		"product" => $arrRowUnformatted_a['gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_product'],
		"description" => $arrRowUnformatted_a['gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_description'],
		"applicant" => $arrRowUnformatted_a['gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicant'],
		"applicanttype" => $arrRowUnformatted_a['gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicanttype'],
		"priceexgst" => $arrRowUnformatted_a['gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceexgst'],
		"priceincgst" => $arrRowUnformatted_a['gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceincgst'],
		"status" => $strStatus,

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
