<?php

function getFormatter_permission($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
    //$arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
        
    $strFormEntityCode = "N/A"; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'ENTITY' );
	$strVersion = "N/A"; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'DATAHEADER', 'DATAVERSION' );
	
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
        "permissioncategory" => $arrRowUnformatted_a['0000eae3_e5b8_4ebb_a3a8_50220cee15d5_permissioncategory'],
		"issysadmin" => $arrRowUnformatted_a['0000eae3_e5b8_4ebb_a3a8_50220cee15d5_issysadmin'],
		"isnonsysadmin" => $arrRowUnformatted_a['0000eae3_e5b8_4ebb_a3a8_50220cee15d5_isnonsysadmin'],
		"islicensed" => $arrRowUnformatted_a['0000eae3_e5b8_4ebb_a3a8_50220cee15d5_islicensed'],
        
		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
