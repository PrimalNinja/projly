<?php

function getFormatter_printjob($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
    $arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
        
	// note: the default doesn't read from the jsondata as there may not be any
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
		"queueowner" => $arrRowUnformatted_a['gb398c9db_2272_4748_907c_d9685b924329_queueowner'],
		"printqueue" => $arrRowUnformatted_a['gb398c9db_2272_4748_907c_d9685b924329_printqueue'],
		"jobdatetime" => getDateTimeStringOut($arrRowUnformatted_a['gb398c9db_2272_4748_907c_d9685b924329_jobdatetime']),
		"device" => $arrRowUnformatted_a['gb398c9db_2272_4748_907c_d9685b924329_device'],
		"printer" => $arrRowUnformatted_a['gb398c9db_2272_4748_907c_d9685b924329_printer'],
		"status" => $arrRowUnformatted_a['gb398c9db_2272_4748_907c_d9685b924329_status'],		
		"moreinfo" => $arrRowUnformatted_a['gb398c9db_2272_4748_907c_d9685b924329_moreinfo'],		
        
		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
