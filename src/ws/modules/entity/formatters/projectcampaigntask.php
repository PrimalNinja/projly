<?php

function getFormatter_projectcampaigntask($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"projectcampaign" => $arrRowUnformatted_a['g4ec1b214_f01d_4c37_beda_aa6a8dbbf78c_projectcampaign'],
		"projecttaskpriority" => $arrRowUnformatted_a['g4ec1b214_f01d_4c37_beda_aa6a8dbbf78c_projecttaskpriority'],
		"projecttaskstatus" => $arrRowUnformatted_a['g4ec1b214_f01d_4c37_beda_aa6a8dbbf78c_projecttaskstatus'],
		"targetdate" => getDateStringOut($arrRowUnformatted_a['g4ec1b214_f01d_4c37_beda_aa6a8dbbf78c_targetdate']),
		"assignedto" => $arrRowUnformatted_a['g4ec1b214_f01d_4c37_beda_aa6a8dbbf78c_assignedto'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
