<?php

function getFormatter_integrationtask($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
    //$arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
        
    $strFormEntityCode = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'ENTITY' );
	$strVersion = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'DATAHEADER', 'DATAVERSION' );
    
    $arrResult = array(
		"id" => secureValue($strEntityType_a . ffel($strEntityCode_a), $arrRowUnformatted_a['id']),
		"rownum" => $intRowNum_a,
        "formentitycode" => $strFormEntityCode,
        "code" => $arrRowUnformatted_a['code'],
        "description" => $arrRowUnformatted_a['description'],
        //"is_enabled" => $arrRowUnformatted_a['is_enabled'],
		"version" => $strVersion,
		"isenabled" => $arrRowUnformatted_a['is_enabled'],
		"modified" => getDateStringOut($arrRowUnformatted_a['modifydatetime']),

        // put custom fields here
        "outboundguid" => $arrRowUnformatted_a['gd554dd80_b3d7_4406_b011_168477f243d9_outboundguid'],
        "server" => $arrRowUnformatted_a['gd554dd80_b3d7_4406_b011_168477f243d9_server'],
		"parameters" => $arrRowUnformatted_a['gd554dd80_b3d7_4406_b011_168477f243d9_parameters'],
        "is_processed" => $arrRowUnformatted_a['is_processed'],
        "status" => $arrRowUnformatted_a['status'],
		

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
