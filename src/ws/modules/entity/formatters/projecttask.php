<?php

function getFormatter_projecttask($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
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
		"sprint" => $arrRowUnformatted_a['ffe63e2a32_d72a_474c_82d0_b664eef1268a_sprint'],
		"taskid" => $arrRowUnformatted_a['ffe63e2a32_d72a_474c_82d0_b664eef1268a_code'],
		"project" => $arrRowUnformatted_a['ffe63e2a32_d72a_474c_82d0_b664eef1268a_project'],
		"projectissue" => $arrRowUnformatted_a['ffe63e2a32_d72a_474c_82d0_b664eef1268a_projectissue'],
		"projecttaskpriority" => $arrRowUnformatted_a['ffe63e2a32_d72a_474c_82d0_b664eef1268a_projecttaskpriority'],
		"allocatedhours" => $arrRowUnformatted_a['ffe63e2a32_d72a_474c_82d0_b664eef1268a_allocatedhours'],
		"projecttaskstatus" => $arrRowUnformatted_a['ffe63e2a32_d72a_474c_82d0_b664eef1268a_projecttaskstatus'],
		"targetdate" => getDateStringOut($arrRowUnformatted_a['ffe63e2a32_d72a_474c_82d0_b664eef1268a_targetdate']),
		"projectparticipant" => $arrRowUnformatted_a['ffe63e2a32_d72a_474c_82d0_b664eef1268a_projectparticipant'],
        "createdate" => getDateStringOut($arrRowUnformatted_a['ffe63e2a32_d72a_474c_82d0_b664eef1268a_createdate']),

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
