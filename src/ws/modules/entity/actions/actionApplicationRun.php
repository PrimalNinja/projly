<?php
function actionApplicationRun($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{	
    $strTableNameApplication = getTableNameEntity("application", false);
    $strTableNameDocument = getTableNameEntity("document", false);

    $arrResult = [];
    $blnResult = true;
    $strResponseMessage = '';
    $strCodeFile = '';
   
    $strApplicationID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);

    $strSQL = "select g375c1fc8_2001_4235_bb44_ec10fd82afc8_description description, jsondata from ~TABLENAMEAPPLICATION~ where id = ~APPLICATIONID~";
    $strSQL = str_replace('~TABLENAMEAPPLICATION~', ff($strTableNameApplication), $strSQL);
    $strSQL = str_replace('~APPLICATIONID~', ff($strApplicationID), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

    $strCodeFile = "";
    $strApplicationDescription = "";

    if ($arrRow = dbReadRecord($objResult))
    {
        $strDocumentID = '';
        $strApplicationDescription = $arrRow['description'];
        $arrJSONData = json_decode($arrRow['jsondata'], true);

        $arrDocuments = formValueGetBySectionCodeFieldCode($arrJSONData, 'g375c1fc8-2001-4235-bb44-ec10fd82afc8', "JSLOGICDOCUMENT");
        
        if (isset($arrDocuments[0]) && isset($arrDocuments[0]['documentid']))
        {
            $strDocumentID = $arrDocuments[0]['documentid'];
        }
        
        if (strlen($strDocumentID) > 0)
        {
            $strSQL = "select d.filenamebase, d.ffe70fd7f1_401b_4c5c_b096_76f67d466982_filename filename
            from ~TABLENAMEDOCUMENT~ d where d.id=~DOCUMENTID~";
            $strSQL = str_replace('~TABLENAMEDOCUMENT~', ff($strTableNameDocument), $strSQL);
            $strSQL = str_replace('~DOCUMENTID~', ff($strDocumentID), $strSQL);
            $objResult2 = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

            if ($arrDocRow = dbReadRecord($objResult2))
            {
				$strREL_CURRENTREPOSITORY_PRINT_DOCUMENT = '';
				if (ENABLE_CLIENTDATABASES == 'TRUE')
				{
					if (getSessionDB(__FUNCTION__) == "client")
					{
						$strREL_CURRENTREPOSITORY_PRINT_DOCUMENT = REL_CLIENTREPOSITORY_PRINT_DOCUMENT;
					}
					else if (getSessionDB(__FUNCTION__) == "system")
					{
						$strREL_CURRENTREPOSITORY_PRINT_DOCUMENT = REL_SYSTEMREPOSITORY_PRINT_DOCUMENT;
					}
				}
				else
				{
					$strREL_CURRENTREPOSITORY_PRINT_DOCUMENT = REL_SYSTEMREPOSITORY_PRINT_DOCUMENT;
				}

                //$strURL = getClusterPath($strREL_CURRENTREPOSITORY_PRINT_DOCUMENT, $arrDocRow['filenamebase'], false, false) . $arrDocRow['filenamebase'] . '-';
                $strURL = getClusterPath($strREL_CURRENTREPOSITORY_PRINT_DOCUMENT, $arrDocRow['filenamebase'], false, false) . $arrDocRow['filenamebase'] . '-';
				$strFilename = pathinfo($arrDocRow['filename'], PATHINFO_FILENAME);
				$strExtension = pathinfo($arrDocRow['filename'], PATHINFO_EXTENSION);

                $strFileURL = URL_APP_PATH . $strURL . $strFilename . '.' . $strExtension;	
                                                       
                $arrResult['file_url'] = $strFileURL;
                $strResponseMessage = ""; //"Application (" . $strApplicationDescription . ") run finished!";
            }
            else
            {
                $strResponseMessage = "Application (" . $strApplicationDescription . ") JS Logic document file not found.";
                $blnResult = false;
            }
            
            dbCloseRecordset($objResult2);
        }
        else
        {
            $strResponseMessage = "Application (" . $strApplicationDescription . ") JS Logic document not found. Publish your application first.";
            $blnResult = false;
        }                 
    }
    else
    {
        $strResponseMessage = "Application build error.";
    }

    dbCloseRecordset($objResult);
                               
    
    if ($blnResult)
    {
	    $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, $strResponseMessage, $arrResult);
    }
    else
    {
        $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strResponseMessage, $arrResult);
    }

	return $strResult;
}