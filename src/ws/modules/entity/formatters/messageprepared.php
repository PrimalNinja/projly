<?php

function getFormatter_messageprepared($objConn_a, $arrRowUnformatted_a, $strEntityType_a, $strEntityCode_a, $intRowNum_a, $intRecordCount_a, $intLimit_a) 
{
    //$arrJSONData = json_decode($arrRowUnformatted_a['jsondata'], true);
        
    $strFormEntityCode = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'FORMHEADER', 'ENTITY' );
	$strVersion = ''; //formValueGetBySectionTypeFieldCode( $arrJSONData, 'DATAHEADER', 'DATAVERSION' );
    
	$strRecipients = $arrRowUnformatted_a['recipients'];
	$strRecipients = str_replace('[{"recipient":"', "", $strRecipients);
	$strRecipients = str_replace('"}]', "", $strRecipients);
	
    $arrResult = array(
		"id" => secureValue($strEntityType_a . ffel($strEntityCode_a), $arrRowUnformatted_a['id']),
		"rownum" => $intRowNum_a,
        //"DT_RowId" => "row_" . $intRowNum_a, // for datatables
        "formentitycode" => $strFormEntityCode,
        "recipients" => $strRecipients,
        "subject" => $arrRowUnformatted_a['subject'],
		"priority" => $arrRowUnformatted_a['priority'],
		"retries" => $arrRowUnformatted_a['retries'],
		"sent" => $arrRowUnformatted_a['sent'],
		"modified" => getDateStringOut($arrRowUnformatted_a['modifydatetime']),

		// put custom fields here

		"recordcount" => $intRecordCount_a,
		"limited" => $intLimit_a //NONAJAXGRIDLIMIT
    );  
    
    return $arrResult;
}
