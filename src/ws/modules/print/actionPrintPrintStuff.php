<?php
// print stuff (multiple jobs at once) (put them in the printer queue)
//
function actionPrintPrintStuff($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $arrResult = array("result"=> STAT_ERROR, "error"=>"Printing Error", "tagtype"=>"", "tag"=> "");

    $blnResult = true;
	$blnValidFilter = true;
    $strResult = "";
    $blnFilterFound = false;
    
	// permission check
	if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}
	
    if (dependencies('print/printPrintStuff')) 
	{
        // parameters
        $arrPrintJobs = getJSONParameter($arrParameters_a, 'printjobs');

        // initialisations
        $strClientID = $_SESSION['server_loggedin_clientid'];
        $strUserID = $_SESSION['server_loggedin_userid'];
        $strDeviceID = $_SESSION['server_deviceid'];

        for ($intI = 0; $intI < count($arrPrintJobs); $intI++) 
		{
            
            $arrPrintJob = $arrPrintJobs[$intI];
            $arrFilter = &$arrPrintJob['filter'];

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
                    // case 'date':
                        // $strDate = $strFilterValue;
                        // $objField['value'] = $strDate;
                        // $blnFilterFound = true;
                        // break;                                        
                    
                    // case 'formreport_id':
                        // $strFormReportID = revertSecuredValue($strFilterValue, 'id', false);
                        // $objField['value'] = $strFormReportID;
                        // $blnFilterFound = true;
                        // break;
                    
                    // case 'user_id':
                        // $strTimeSheetUserID = revertSecuredValue($strFilterValue, 'id', true);
                        // $objField['value'] = $strTimeSheetUserID;
                        // $blnFilterFound = true;
                        // break;

                    // case 'client_id':
                        // $strClientID = revertSecuredValue($strFilterValue, 'id', true);
                        // $objField['value'] = $strClientID;
                        // $blnFilterFound = true;
                        // break;
                }
            }

            if ($blnFilterFound) 
			{
				dbBeginTrans($objConn_a, __FUNCTION__);
				$arrResult = printPrintStuff($objConn_a, $strSecurityToken_a, $strDataID_a, $arrPrintJob, $strClientID, $strUserID, $strDeviceID);
				$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
            } 
			else 
			{
                $blnValidFilter = false;
            }
        }

		if ($blnValidFilter)
		{
			if ($blnResult) 
			{
				$strMessage = "";
				if (array_key_exists('error', $arrResult) && strlen($arrResult['error']) > 0)
				{
					$strMessage = $arrResult['error'];
				}
				$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, $strMessage, encodeTagIDs($arrResult));
			} 
			else 
			{
				$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Invalid print jobs.', array());
			}
		}
		else
		{
			$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Invalid print job filter.', array());
		}
    }

    return $strResult;
}
