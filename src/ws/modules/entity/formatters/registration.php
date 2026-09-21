<?php

function getFormatter_registration($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"isenabled" => $arrRowUnformatted_a['is_enabled'],
		"modified" => getDateStringOut($arrRowUnformatted_a['modifydatetime']),

		// put custom fields here
		"registrationtypedesc" => $arrRowUnformatted_a['ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_registrationtypedesc'],
		"clientcode" => $arrRowUnformatted_a['ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_clientcode'],
		"accountname" => $arrRowUnformatted_a['ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_accountname'],
		"ipaddress" => $arrRowUnformatted_a['ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_ipaddress'],
		"is_confirmed" => $arrRowUnformatted_a['ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_isconfirmed'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
