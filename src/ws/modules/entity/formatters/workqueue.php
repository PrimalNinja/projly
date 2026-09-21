<?php

function getFormatter_workqueue($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"modifyuser" => $arrRowUnformatted_a['modifyuser'],
		"modified" => getDateStringOut($arrRowUnformatted_a['modifydatetime']),

		// put custom fields here
		"created" => $arrRowUnformatted_a['createdatetime'],
		"workqueuecode" => $arrRowUnformatted_a['workqueueitemheader_code'],
		"longdescription" => $arrRowUnformatted_a['workqueueitemheader_longdescription'],
		"progress" => $arrRowUnformatted_a['progress'],
		"workqueuestatus" => $arrRowUnformatted_a['workqueueitemheader_workqueuestatus'],
		"assignedto" => $arrRowUnformatted_a['workqueueitemheader_assignedto'],

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
