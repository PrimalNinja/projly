<?php

function getFormatter_workqueuestatus($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"group" => $arrRowUnformatted_a['ff24319a52_14a2_4aae_a91a_4c3a5e7f42ce_group'],
		"notes" => $arrRowUnformatted_a['ff24319a52_14a2_4aae_a91a_4c3a5e7f42ce_notes'],
		"workqueueitemtype" => $arrRowUnformatted_a['ff24319a52_14a2_4aae_a91a_4c3a5e7f42ce_workqueueitemtype'],
		"iscompleted" => $arrRowUnformatted_a['ff24319a52_14a2_4aae_a91a_4c3a5e7f42ce_is_completed'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
