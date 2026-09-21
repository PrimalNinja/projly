<?php

function getFormatter_roster($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"date" => $arrRowUnformatted_a['g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_date'],
		"projectparticipant" => $arrRowUnformatted_a['g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_projectparticipant'],
		"plannedstarttime" => $arrRowUnformatted_a['g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_plannedstarttime'],
		"plannedendtime" => $arrRowUnformatted_a['g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_plannedendtime'],
		"plannedtotalhours" => $arrRowUnformatted_a['g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_plannedtotalhours'],
		"clockontime" => $arrRowUnformatted_a['g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_clockontime'],
		"clockofftime" => $arrRowUnformatted_a['g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_clockofftime'],
		"totalhours" => $arrRowUnformatted_a['g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_totalhours'],
		"totalminutes" => $arrRowUnformatted_a['g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_totalminutes'],
		"cancelled" => $arrRowUnformatted_a['g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_cancelled'],
		"managersignoff" => $arrRowUnformatted_a['g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_managersignoff'],
		"hrsignoff" => $arrRowUnformatted_a['g7b9e72db_4138_48fc_9ef1_24595d3e3fb9_hrsignoff'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
