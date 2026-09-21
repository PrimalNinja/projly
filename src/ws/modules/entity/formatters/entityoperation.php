<?php

function getFormatter_entityoperation($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"isfixed" => $arrRowUnformatted_a['ff19a43370_beae_4bbf_aab7_f1c1ce50c50a_isfixed'],
		"permissiondesc" => $arrRowUnformatted_a['ff19a43370_beae_4bbf_aab7_f1c1ce50c50a_permissiondesc'],
		"isinternal" => $arrRowUnformatted_a['ff19a43370_beae_4bbf_aab7_f1c1ce50c50a_isinternal'],
		"requiresselection" => $arrRowUnformatted_a['ff19a43370_beae_4bbf_aab7_f1c1ce50c50a_requiresselection'],
		"allowmultiple" => $arrRowUnformatted_a['ff19a43370_beae_4bbf_aab7_f1c1ce50c50a_allowmultiple'],
		"iscustom" => $arrRowUnformatted_a['ff19a43370_beae_4bbf_aab7_f1c1ce50c50a_iscustom'],
		"displayorder" => $arrRowUnformatted_a['ff19a43370_beae_4bbf_aab7_f1c1ce50c50a_displayorder'],
		"command" => $arrRowUnformatted_a['ff19a43370_beae_4bbf_aab7_f1c1ce50c50a_command'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
