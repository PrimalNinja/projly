<?php

function getFormatter_sequence($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"module" => $arrRowUnformatted_a['g9a1757b7_1a28_408e_8f6f_201a26e9b9ac_module'],
		"sequencetype" => $arrRowUnformatted_a['g9a1757b7_1a28_408e_8f6f_201a26e9b9ac_sequencetype'],
		"prefix" => $arrRowUnformatted_a['g9a1757b7_1a28_408e_8f6f_201a26e9b9ac_prefix'],
		"start" => $arrRowUnformatted_a['g9a1757b7_1a28_408e_8f6f_201a26e9b9ac_start'],
		"end" => $arrRowUnformatted_a['g9a1757b7_1a28_408e_8f6f_201a26e9b9ac_end'],
		"next" => $arrRowUnformatted_a['g9a1757b7_1a28_408e_8f6f_201a26e9b9ac_next'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
