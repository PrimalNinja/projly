<?php

function getFormatter_contact($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"branch" => $arrRowUnformatted_a['g5ccad758_2962_475f_bc56_bb9b2d584efd_branch'],
		"contacttype" => $arrRowUnformatted_a['g5ccad758_2962_475f_bc56_bb9b2d584efd_contacttype'],
		"status" => $arrRowUnformatted_a['g5ccad758_2962_475f_bc56_bb9b2d584efd_status'],
		"fullname" => $arrRowUnformatted_a['g5ccad758_2962_475f_bc56_bb9b2d584efd_fullname'],
		"postcode" => $arrRowUnformatted_a['g5ccad758_2962_475f_bc56_bb9b2d584efd_postcode'],
		"country" => $arrRowUnformatted_a['g5ccad758_2962_475f_bc56_bb9b2d584efd_country'],
		"phone" => $arrRowUnformatted_a['g5ccad758_2962_475f_bc56_bb9b2d584efd_phone'],
		"mobile" => $arrRowUnformatted_a['g5ccad758_2962_475f_bc56_bb9b2d584efd_mobile'],
		"emailaddress" => $arrRowUnformatted_a['g5ccad758_2962_475f_bc56_bb9b2d584efd_emailaddress'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
