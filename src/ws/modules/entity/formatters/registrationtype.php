<?php

function getFormatter_registrationtype($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"applications" => $arrRowUnformatted_a['ff4909db1e_8bee_4c2b_be10_6d0853789635_applications'],
		"isemployer" => $arrRowUnformatted_a['ff4909db1e_8bee_4c2b_be10_6d0853789635_isemployer'],
		"isindividual" => $arrRowUnformatted_a['ff4909db1e_8bee_4c2b_be10_6d0853789635_isindividual'],
		"displayorder" => $arrRowUnformatted_a['ff4909db1e_8bee_4c2b_be10_6d0853789635_displayorder'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
