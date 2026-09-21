<?php

function getFormatter_entityoperation_default($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"isinternal" => $arrRowUnformatted_a['ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_isinternal'],
		"requiresselection" => $arrRowUnformatted_a['ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_requiresselection'],
		"allowmultiple" => $arrRowUnformatted_a['ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_allowmultiple'],
		"iscustom" => $arrRowUnformatted_a['ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_iscustom'],
		"displayorder" => $arrRowUnformatted_a['ff0963af8f_9bda_4fda_891f_2f1c2f4c4e89_displayorder'],
		"command" => $arrRowUnformatted_a['ff19a43370_beae_4bbf_aab7_f1c1ce50c50a_command'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
