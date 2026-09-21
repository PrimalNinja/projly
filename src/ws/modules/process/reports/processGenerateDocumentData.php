<?php
// returns an error if there is one, or an empty string if no error
function processGenerateDocumentData($objConn_a, $strClientID_a, $strMetaData_a, $blnPreview_a)
{
	$arrResult = array("result"=> STAT_ERROR, "error"=>"Generate Document Data Error", "tagtype"=>"", "tag"=> "");

	// dependencies('process/reports/formreport/processGenerateFormReportData') &&
    if (dependencies('process/reports/receipts/processGenerateReceiptData')) 
	{

        $arrMetadata = json_decode($strMetaData_a, true);

        // metadata parameters
        $strUserID = $arrMetadata[0]["userid"];
        $strDeviceID = $arrMetadata[0]["deviceid"];
        $arrPrintJob = $arrMetadata[0]["printjob"];

        $strDocumentType = strtoupper($arrPrintJob['type']);
        $arrFilter = $arrPrintJob['filter'];
        
        switch ($strDocumentType) 
		{            
/*             case 'FORMREPORT':
				// result, error, tagtype, tag, path
                $arrResult = processGenerateFormReportData($objConn_a, $strClientID_a, $strUserID, $strDeviceID, 'FOR', $arrFilter);
                break; 
*/
				
            case 'RECEIPT':
            case 'RECEIPTS':
				// result, error, tagtype, tag, path
                $arrResult = processGenerateReceiptData($objConn_a, $strClientID_a, $strUserID, $strDeviceID, 'REC', $arrFilter, $blnPreview_a);
                break;
        }
    }
	
    return $arrResult;
}
