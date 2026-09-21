<?php

function actionGenerateReportToPDF($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $blnResult = true;
    $strResult = "";
    
    if (dependencies('batch/batchJobAdd')) 
	{
		// permission check
		if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}
		
        // parameters
        $strReportCode = getJSONParameter($arrParameters_a, 'reportcode');

        // initialisations
        $strClientID = $_SESSION['server_loggedin_clientid'];
        $strUserID = $_SESSION['server_loggedin_userid'];
        $strDeviceID = $_SESSION['server_deviceid'];

        $strTimeSheetUserID = revertSecuredValue($strFilterValue, 'id', true);
        $objField['value'] = $strTimeSheetUserID;
        $blnFilterFound = true;


		// TODO ALISTAIR this might be correct, i am unsure where it is used
        if ($blnFilterFound) 
        {
            // start trans
            dbBeginTrans($objConn_a, __FUNCTION__);

            $arrJSON[] = array(
                "userid" => $strUserID,
                "deviceid" => $strDeviceID,
                "printjob" => $arrPrintJob
            );

            $strJSON = json_encode($arrJSON);
            $strCode = '';
            $arrResult = batchJobAdd($objConn_a, $strClientID, $strUserID, '2', $strCode, $strDescription, 'PROCESS_GENERATEDOCUMENT', $strJSON, false, $blnPreview);

            // commit trans
            $blnResult = dbEndTrans($objConn_a, __FUNCTION__);
        } 
        else 
        {
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Invalid generate report filter.', array());
        }        

        //Read File
        $strFilePath = APP_PATH . 'ws/modules/entity/reports/' . strtolower($strReportCode) . '.json';
        $strJSONData = loadFile($strFilePath);
        $arrJSONData = json_decode($strJSONData);

        $strHTML = "";

        //Loop Sections
        if ($arrJSONData != null)
        {
            foreach ($arrJSONData as $keySection => $arrSection)
            {
                
                $strSectionCode = $arrSection['sectioncode'];
                $strSectionType = $arrSection['sectiontype'];
                $strSectionTitle = $arrSection['sectiontitle'];

                $strHTML .= "<strong>$strSectionTitle</strong>";
                $strHTML .= "<br />";
                $strHTML .= "<div>";
    
                $arrFields = $arrSection['fields'];
    
                foreach ($arrFields as $keyField => $arrField)
                {
                    $strDataType = $arrField['p_datatype'];
                    $strFieldCode = $arrField['p_name'];
                    $strFieldValue = $arrField['p_value'];

                    $strInputType = 'text';
                    if ($strDataType == 'text')
                    {
                        $strInputType = 'text';                        
                    }

                    $strHTML .= "<div><label>$strFieldCode</label>: <input type='$strInputType' value='$strValue' /><div>";
                }    

                $strHTML .= "<div>";
            }
        }

        //Generate PDF
        //Codes here to generate the PDF
   }

    //return  $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, "Hello World $strJSONData", array());
    return  $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strHTML, array());
}
