<?php

function getFormatter_rostertemplate_detail($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"rosterday" => $arrRowUnformatted_a['g842ac7fc_e994_459f_b510_48b8f8e74239_rosterday'],
		"plannedstarttime" => $arrRowUnformatted_a['g842ac7fc_e994_459f_b510_48b8f8e74239_plannedstarttime'],
		"plannedendtime" => $arrRowUnformatted_a['g842ac7fc_e994_459f_b510_48b8f8e74239_plannedendtime'],
		"plannedtotalhours" => $arrRowUnformatted_a['g842ac7fc_e994_459f_b510_48b8f8e74239_plannedtotalhours'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
