<?php
function actionApplicationBuild($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{	
    $strTableNameApplication = getTableNameEntity("application", false);
    $strTableNameDocument = getTableNameEntity("document", false);

	$strResult = '';
    $strResponseMessage = '';

    if (dependencies('docs/documentSimpleCopy') && dependencies('application/buildApplication'))
    {        
        $strApplicationID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);

        $strClientID = $_SESSION['server_loggedin_clientid'];  
        $strUserID = $_SESSION['server_loggedin_userid']; 
        $strLogin = $_SESSION['server_loggedin_user']; 
        
        $strSQL = "select g375c1fc8_2001_4235_bb44_ec10fd82afc8_description description, jsondata from ~TABLENAMEAPPLICATION~ where id = ~APPLICATIONID~";
        $strSQL = str_replace('~TABLENAMEAPPLICATION~', ff($strTableNameApplication), $strSQL);
        $strSQL = str_replace('~APPLICATIONID~', ff($strApplicationID), $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        $strCodeFile = "";
        $strApplicationDescription = "";
                
        if ($arrRow = dbReadRecord($objResult))
        {
            $strApplicationDescription = $arrRow['description'];
            $arrJSONData = json_decode($arrRow['jsondata'], true);

            $strCodeFile = buildApplication($objConn_a, $strApplicationID);
            

            dbBeginTrans($objConn_a, __FUNCTION__);

            $strFilename = getFilenameGUID() . '.js';

            $strTempFile = TEMP_GENERAL_PATH . $strFilename;

            $objFile = fopen($strTempFile, 'w');
            
            fwrite($objFile, $strCodeFile);
            
            fclose($objFile);

            $strImageCode = '';
            $strDescription = $strApplicationDescription;
            $strOriginalFilename = $strFilename;

            // read the document type
            $strDocumentTypeID = getDocumentTypeIDByCode($objConn_a, DOCUMENTTYPE_GENERIC);

            // add the original document along with the error list as notes
            $strDescription = $strOriginalFilename;
            $strMetaData = '{ "originalfilename":"' . $strOriginalFilename . '" }';
            $strDocumentID = documentSimpleCopy($objConn_a, $strClientID, $strUserID, $strDocumentTypeID, $strImageCode, $strDescription, $strOriginalFilename, $strTempFile, $strMetaData, "", "");
            $strDocumentDescription = dbGetDescriptionFromID($objConn_a, $strTableNameDocument, $strDocumentID, __FUNCTION__);

            $arrDocuments = [];
            $arrDocuments[] = ['documentid' => $strDocumentID, 'filename' => $strDocumentDescription];
            $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g375c1fc8-2001-4235-bb44-ec10fd82afc8', "JSTESTDOCUMENT", $arrDocuments);
            
            $objDate = new DateTime();  
            $strDate = $objDate->format("Y-m-d");
            $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g375c1fc8-2001-4235-bb44-ec10fd82afc8', "BUILDDATE", $strDate);
                            
            $strJSONData = json_encode($arrJSONData);

            $strSQL = "update ~TABLENAMEAPPLICATION~ set jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where id = ~APPLICATIONID~";
            $strSQL = str_replace('~TABLENAMEAPPLICATION~', ff($strTableNameApplication), $strSQL);
            $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
            $strSQL = str_replace('~APPLICATIONID~', ff($strApplicationID), $strSQL);
            $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
            $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
            dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

            exposeEntityData($objConn_a, 'SYSTEMFORM', 'APPLICATION', $strApplicationID, $strJSONData);
                    
            dbEndTrans($objConn_a, __FUNCTION__);
            
            $strResponseMessage = "Application (" . $strApplicationDescription . ") build successful!";
        }
        else
        {
            $strResponseMessage = "Application build error.";
        }

        dbCloseRecordset($objResult);
                               
    }

	$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, $strResponseMessage, array());
	
	return $strResult;
}