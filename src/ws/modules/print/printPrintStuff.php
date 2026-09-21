<?php
// print stuff (multiple jobs at once) (put them in the printer queue)

// example process to print issues:
//
// print issues
//  add batch job PROCESS_GENERATEDOCUMENT (via batchJobAdd which also indicates whether it is interactive)
//	processTask is called to either execute PROCESS_GENERATEDOCUMENT immediately or via a batch process
//
// processTask
//  calls processGenerateDocumentData to generate data via delegates
//      which generates the data into a JSON file
//      now we can print issues without looking in the database
//  calls processGenerateDocument to generate the actual document via delegates
//      processGenerateIssues
//      processGenerateIssueTypeA which generates HTML using the JSON
//  add a printjob to the queue as ready (via printJobAdd) but only if not previewing
//

function printPrintStuff($objConn_a, $strSecurityToken_a, $strDataID_a, $arrPrintJob_a, $strClientID_a, $strUserID_a, $strDeviceID_a)
{
    $arrResult = array("result"=> STAT_ERROR, "error"=>"Printing Error", "tagtype"=>"", "tag"=> "");

    $blnResult = true;
    $blnPreview = false;
    $strDescription = "";
    $strPreview = "";

    if (dependencies('batch/batchJobAdd')) 
	{
        // initialisations
        $strClientID = $strClientID_a;
        $strUserID = $strUserID_a;
        $strDeviceID = $strDeviceID_a;

		$strType = $arrPrintJob_a['type'];
		$arrFilter = $arrPrintJob_a['filter'];
		$strPreview = 'N';
		if (key_exists('preview', $arrPrintJob_a))
		{
			$strPreview = $arrPrintJob_a['preview'];
		}
		if ($strPreview == 'Y')
		{
			$blnPreview = true;
		}

		$strDate = '';
		$strFormReportID = '';

		foreach ($arrFilter as &$objField) 
		{
			
			$strFilterField = $objField['field'];
			$strFilterValue = $objField['value'];

			$strDate = '';
			$strFormReportID = '';

			switch ($strFilterField) 
			{                    
				// case 'date':				// TO TEST, NOT USED?
					// $strDate = $strFilterValue;
					// $objField['value'] = $strDate;
					// break;                                        
				
				// case 'formreport_id':		// TO TEST, NOT USED?
					// $strFormReportID = $strFilterValue;
					// $objField['value'] = $strFormReportID;
					// break;
				
				// case 'user_id':				// TO TEST, NOT USED?
					// $strTimeSheetUserID = $strFilterValue;
					// $objField['value'] = $strTimeSheetUserID;
					// break;

				// case 'client_id':			// TO TEST, NOT USED?
					// $strClientID = $strFilterValue;
					// $objField['value'] = $strClientID;
					// break;
			}
		}
	   
		switch ($strType) 
		{                
/*                 case 'formreport':				// TO TEST
				//permission check
				$strDescription = 'formreport ';
				break;  */
				
		}

		
		// start trans
		dbBeginTrans($objConn_a, __FUNCTION__);

		
		$arrJSON[] = array(
			"userid" => $strUserID,
			"deviceid" => $strDeviceID,
			"printjob" => $arrPrintJob_a
		);

		$strJSON = json_encode($arrJSON);
		$strCode = '';

		$arrResult = batchJobAdd($objConn_a, $strClientID, $strUserID, '2', $strCode, $strDescription, 'PROCESS_GENERATEDOCUMENT', $strJSON, false, $blnPreview);

		// commit trans
		$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
    }

    return $arrResult;
}

