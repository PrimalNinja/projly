<?php

function getFormatter_salespersoncodelog($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"date" => getDateStringOut($arrRowUnformatted_a['g8f690085_6d2f_4296_a16a_ff1d1d43b0bb_date']),
		"salespersoncode" => $arrRowUnformatted_a['g8f690085_6d2f_4296_a16a_ff1d1d43b0bb_salespersoncode'],
		"exception" => $arrRowUnformatted_a['g8f690085_6d2f_4296_a16a_ff1d1d43b0bb_exception'],
		"soldtoclient" => $arrRowUnformatted_a['g8f690085_6d2f_4296_a16a_ff1d1d43b0bb_soldtoclient'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
