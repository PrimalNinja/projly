<?php

// fetch a page given a filename
function actionFetchPage($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameDocument = getTableNameEntity("document", false);
	$strTableNameDocumentType = getTableNameEntity("documenttype", false);
                
    $arrResult = array();
	
    if (dependencies('docs/documentClusterLoad')) 
	{
		// permission check
		if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}

        $strDocumentID = revertSecuredValue(getJSONParameter($arrParameters_a, 'documentid'), 'id', true);
        $strFileName = getJSONParameter($arrParameters_a, 'pagename');

logPrinting("filename: " . $strFileName . " at location: actionFetchPage");	

        $strDescription = "";
		$strFilenameBase = "";
		
		$strSQL = "select description, filenamebase from ~TABLENAMEDOCUMENT~ where id = ~DOCUMENTID~";
		$strSQL = str_replace('~TABLENAMEDOCUMENT~', ff($strTableNameDocument), $strSQL);
        $strSQL = str_replace('~DOCUMENTID~', ff($strDocumentID), $strSQL);

        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		if ($arrRow = dbReadRecord($objResult)) 
		{
			$strDescription = $arrRow['description'];			
			$strFilenameBase = $arrRow['filenamebase'];
        }
		dbCloseRecordset($objResult);
		
        // load the data from the datafile
		$strDocumentRoot = getDocumentRootPathByDocumentID($objConn_a, $strDocumentID);
        $strDocumentData = documentClusterLoad($strFilenameBase, $strFileName, $strDocumentRoot);

        $arrResult = array(
            "page" => $strDocumentData
        );

logPrinting("documentroot: " . $strDocumentRoot . " at location: actionFetchPage");	
    }

    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
