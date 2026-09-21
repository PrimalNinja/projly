<?php

function getFormatter_softwarelicence($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"software" => $arrRowUnformatted_a['g3a39f0ae_ef99_4f73_bd8f_a31eaf2a1bc8_software'],
		"version" => $arrRowUnformatted_a['g3a39f0ae_ef99_4f73_bd8f_a31eaf2a1bc8_version'],
		"licencetype" => $arrRowUnformatted_a['g3a39f0ae_ef99_4f73_bd8f_a31eaf2a1bc8_licencetype'],
		"ownerother" => $arrRowUnformatted_a['g3a39f0ae_ef99_4f73_bd8f_a31eaf2a1bc8_ownerother'],
		"filename" => $arrRowUnformatted_a['g3a39f0ae_ef99_4f73_bd8f_a31eaf2a1bc8_filename'],
		"lastauditdate" => getDateStringOut($arrRowUnformatted_a['g3a39f0ae_ef99_4f73_bd8f_a31eaf2a1bc8_lastauditdate']),
		"auditor" => $arrRowUnformatted_a['g3a39f0ae_ef99_4f73_bd8f_a31eaf2a1bc8_auditor'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
