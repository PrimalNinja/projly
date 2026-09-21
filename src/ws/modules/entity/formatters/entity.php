<?php

function getFormatter_entity($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"fixedstatus" => $arrRowUnformatted_a['ffe65a2521_c3d0_42fc_8d25_dedd43876fff_fixedstatus'],
		"permissioncategory" => $arrRowUnformatted_a['ffe65a2521_c3d0_42fc_8d25_dedd43876fff_permissioncategory'],
		"isextended" => $arrRowUnformatted_a['ffe65a2521_c3d0_42fc_8d25_dedd43876fff_isextended'],
		"deferredpopulation" => $arrRowUnformatted_a['ffe65a2521_c3d0_42fc_8d25_dedd43876fff_deferredpopulation'],
		"ishidden" => $arrRowUnformatted_a['ffe65a2521_c3d0_42fc_8d25_dedd43876fff_ishidden'],
		"liverowcount" => $arrRowUnformatted_a['ffe65a2521_c3d0_42fc_8d25_dedd43876fff_liverowcount'],
		"historyrowcount" => $arrRowUnformatted_a['ffe65a2521_c3d0_42fc_8d25_dedd43876fff_historyrowcount'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
