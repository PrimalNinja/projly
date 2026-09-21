<?php

function getFormatter_profile_permission($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
	$strTableNamePermission = getTableNameEntity("permission", false);
	$strTableNamePermissionCategory = getTableNameEntity("permissioncategory", false);
			
    //$arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
        
    $strFormEntityCode = "N/A"; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'ENTITY' );
	$strVersion = "N/A"; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'DATAHEADER', 'DATAVERSION' );
    
    
	// now get the permissioncategory jsondata for permissioncategory metadata
	$strSQL = "select pc.description returnvalue from ~TABLENAMEPERMISSION~ p, ~TABLENAMEPERMISSIONCATEGORY~ pc where p.permissioncategory_id = pc.id and p.id = ~PERMISSIONID~";
	$strSQL = str_replace('~TABLENAMEPERMISSIONCATEGORY~', ff($strTableNamePermissionCategory), $strSQL);
	$strSQL = str_replace('~TABLENAMEPERMISSION~', ff($strTableNamePermission), $strSQL);
	$strSQL = str_replace('~PERMISSIONID~', ff($arrRowUnformatted_a['permission_id']), $strSQL);
	$strPermissionCategoryDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__); 
	
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
        "permissioncategory" => $strPermissionCategoryDescription,
        //"module" => $strModuleDescription,

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
