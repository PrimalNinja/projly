<?php

function getFormatter_configtask($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
    //$arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
        
    $strFormEntityCode = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'ENTITY' );
	$strVersion = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'DATAHEADER', 'DATAVERSION' );
    
    //$strParameters = json_encode(json_decode($arrRowUnformatted_a["g15aeaa60_835c_4974_87c5_e82ec903629f_parameters"], true));
    $strParameters = $arrRowUnformatted_a["g15aeaa60_835c_4974_87c5_e82ec903629f_parameters"];

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
        "command" => $arrRowUnformatted_a["g15aeaa60_835c_4974_87c5_e82ec903629f_command"],
        "parameters" => $strParameters, //$arrRowUnformatted_a["g15aeaa60_835c_4974_87c5_e82ec903629f_parameters"],
        "isprocessed" => $arrRowUnformatted_a["g15aeaa60_835c_4974_87c5_e82ec903629f_is_processed"],
        "sortorder" => $arrRowUnformatted_a["g15aeaa60_835c_4974_87c5_e82ec903629f_sortorder"],
        "starttime" => $arrRowUnformatted_a["g15aeaa60_835c_4974_87c5_e82ec903629f_starttime"],
        "endtime" => $arrRowUnformatted_a["g15aeaa60_835c_4974_87c5_e82ec903629f_endtime"],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
