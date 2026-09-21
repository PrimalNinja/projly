<?php

// fetch an image
// example URL: http://localhost/jsoncv/fetch.php?image=abc123
function actionDocsFetchImage($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
    $strFilename = '';

    if (dependencies('docs/documentClusterDownload') &&
		dependencies('entity/dataaccess/document'))
	{
		// permission check
		if (!hasPermission($objConn_a, 'VW_DOCUMENT', __FUNCTION__, true)) {return false;}
		
        // parameters
        $strDocumentID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);
        //$strDocumentID = getJSONParameter($arrParameters_a, 'id'); // use this line for public access

        $blnForceDownload = toBoolean(getJSONParameter($arrParameters_a, 'download'));
        
        $strFilenameBase = '';

		$strFilename = "";
		$strFilenameBase = "";
		$strJSONData = "";
		
		$objResult = fetch_document($objConn_a, "filenamebase, ffe70fd7f1_401b_4c5c_b096_76f67d466982_filename filename", $strDocumentID, []);
        if ($arrRow = dbReadRecord($objResult)) {
			$strFilename = $arrRow['filename'];
			$strFilenameBase = $arrRow['filenamebase'];
			$strJSONData = $arrRow['jsondata'];
        }
        dbCloseRecordset($objResult);

		$arrJSONData = json_decode($strJSONData, true);			
		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "ffe70fd7f1-401b-4c5c-b096-76f67d466982", "FILES");

		$arrFiles = $arrJSONField['p_value'];
	
		$strOriginalFilename = '';
		if (count($arrFiles) > 0)
		{
			$arrFile = $arrFiles[0];
			$strOriginalFilename = $arrFile['filename'];
//debug($strFilenameBase . '-' . $strOriginalFilename); die();
			$strDocumentRoot = getDocumentRootPathByDocumentID($objConn_a, $strDocumentID);
			documentClusterDownload($strFilenameBase, $strFilenameBase . '-' . $strOriginalFilename, $strFilename, "application/octet-stream", false, $blnForceDownload, $strDocumentRoot);
		}
    }

    return true;
}
