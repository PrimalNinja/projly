<?php

function getFormatter_systemmodule_entity($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
    $strTableNameEntity = getTableNameEntity("entity", false);
    
    $arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
	       
    $strFormEntityCode = formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'ENTITY' );
	$strVersion = formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'FORMVERSION' );
	
	$strEntityID = $arrRowUnformatted_a['entityentity_id'];

	$strSQL = "select ffe65a2521_c3d0_42fc_8d25_dedd43876fff_permissioncategory returnvalue from ~TABLENAMEENTITY~ where id = ~ENTITYID~";
	$strSQL = str_replace('~TABLENAMEENTITY~', ff($strTableNameEntity), $strSQL);
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
	$strCategory = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
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
		"category" => $strCategory,
        "entityentity" => $arrRowUnformatted_a['g4f6f774a_fe22_46c1_819b_ad161593721b_entityentity'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
