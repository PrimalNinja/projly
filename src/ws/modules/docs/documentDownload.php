<?php

// download a document
// in future download all the parts of a multi-part document, perhaps ZIPPED? for now we download the last file
// note: the last file should be the most viewable one for the user, the first should the the rawest
function documentDownload($objConn_a, $strClientID_a, $strDocumentID_a, $blnForceDownload_a)
{
	$blnFound = false;
	
    if (dependencies('docs/documentClusterDownload') &&
		dependencies('entity/dataaccess/document')) 
	{
		// client bypass
		$blnIgnoreClient = false;
		if (InStr(',' . IGNORECLIENT_LISTERENTITIES . ',', ',' . ffeu("DOCUMENT") . ',') >= 0)
		{
			$blnIgnoreClient = true;
		}
		
		if ($blnIgnoreClient == false)
		{
			if (InStr(',' . IGNORECLIENT_SYSTEMOWNERENTITES . ',', ',' . ffeu("DOCUMENT") . ',') >= 0)
			{
				$blnIgnoreClient = ($strClientID_a == getSystemOwnerClientID($objConn_a));
			}
		}
	
		if ($blnIgnoreClient == false)
		{
			if (InStr(',' . IGNORECLIENT_SYSTEMADMIN . ',', ',' . ffeu("DOCUMENT") . ',') >= 0)
			{
				$blnIgnoreClient = ($strClientID_a == getSystemClientID($objConn_a));
			}
		}
	
        // fetch
		$arrCustomWhere = [];
		if ($blnIgnoreClient)
		{
			// do nothing
		}
		else
		{
			$arrCustomWhere = [["n", "client_id", $strClientID_a], ["n", "id", $strDocumentID_a]];
		}

		$strFilename = "";
		$strFilenameBase = "";
		$strJSONData = "";
		$objResult = fetch_document($objConn_a, "filenamebase, jsondata, ffe70fd7f1_401b_4c5c_b096_76f67d466982_filename filename", $strDocumentID_a, $arrCustomWhere);
        if ($arrRow = dbReadRecord($objResult)) 
		{
			$strFilename = $arrRow['filename'];
			$strFilenameBase = $arrRow['filenamebase'];
			$strJSONData = $arrRow['jsondata'];
        }
        dbCloseRecordset($objResult);

		$arrJSONData = json_decode($strJSONData, true);			
		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "ffe70fd7f1-401b-4c5c-b096-76f67d466982", "FILES");

		$arrFiles = $arrJSONField['p_value'];

		$strComponentFilename = '';
		if (count($arrFiles) > 0)
		{
			$blnFound = true;
			$arrFile = $arrFiles[(count($arrFiles) - 1)];
			$strComponentFilename = $arrFile['filename'];
			//debug($strFilenameBase . '-' . $strComponentFilename); die();
			$strDocumentRoot = getDocumentRootPathByDocumentID($objConn_a, $strDocumentID_a);
			documentClusterDownload($strFilenameBase, $strFilenameBase . '-' . $strComponentFilename, $strFilename, "application/octet-stream", false, $blnForceDownload_a, $strDocumentRoot);
		}
    }
	
	if ($blnFound == false)
	{
		echo('document is not found, possibly due to permissions.');
	}

    // no need a return value. it MUST ONLY download or output the file in the browser.
    exit();
}
