<?php

function getFormatter_projectissue($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
    $arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
        
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
		"sprint" => $arrRowUnformatted_a['ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_sprint'],
		"issueid" => $arrRowUnformatted_a['ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_code'],
		"project" => $arrRowUnformatted_a['ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_project'],
		"projectissuepriority" => $arrRowUnformatted_a['ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_projectissuepriority'],
		"projectissuetype" => $arrRowUnformatted_a['ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_projectissuetype'],
		"projectissuestatus" => $arrRowUnformatted_a['ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_projectissuestatus'],
		"targetdate" => getDateStringOut($arrRowUnformatted_a['ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_targetdate']),
		"projectparticipant" => $arrRowUnformatted_a['ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_projectparticipant'],
        "createdate" => getDateStringOut($arrRowUnformatted_a['ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_createdate']),
        "estimatedhours" => $arrRowUnformatted_a['ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_estimatedhours'],
        "estimatedminutes" => $arrRowUnformatted_a['ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_estimatedminutes'],
        "actualhours" => $arrRowUnformatted_a['ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_actualhours'],
        "actualminutes" => $arrRowUnformatted_a['ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_actualminutes'],
        
		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
