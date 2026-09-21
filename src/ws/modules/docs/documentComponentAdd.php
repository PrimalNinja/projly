<?php

// add the document components with this where the component itself is stored in a variable objComponent_a, the componentid (just a suffix) is optional and should not be provided for the entry point to the document
function documentComponentAdd($objConn_a, $strDocumentID_a, $strFilename_a, $objComponent_a)
{
    if (dependencies('docs/documentClusterSave') &&
		dependencies('entity/dataaccess/document') &&
		dependencies('entity/dataaccess/documentrepository')) 
	{
		$strFilenameBase = fetchValue_document($objConn_a, "filenamebase", $strDocumentID_a, []);
				
		$strDocumentRepositoryID = fetchValue_document($objConn_a, "documentrepository_id", $strDocumentID_a, []);
		if (strlen($strDocumentRepositoryID) > 0)
		{
			$strDocumentRoot = getDocumentRootPathByDocumentID($objConn_a, $strDocumentID_a);
			$intFilesize = documentClusterSave($strFilenameBase, $strFilenameBase . '-' . $strFilename_a, $objComponent_a, $strDocumentRoot);

			$strJSONData = fetchValue_document($objConn_a, "jsondata", $strDocumentID_a, []);
			
			$arrJSONData = json_decode($strJSONData, true);
			$strStorageUsed = formValueGetBySectionCodeFieldCode($arrJSONData, "ffe70fd7f1-401b-4c5c-b096-76f67d466982", "STORAGEUSED");
			$intStorageUsed = intval($strStorageUsed, 10);
			$intStorageUsed += $intFilesize;
			
			$arrFiles = formValueGetBySectionCodeFieldCode($arrJSONData, "ffe70fd7f1-401b-4c5c-b096-76f67d466982", "FILES");
			$arrFiles[] = array(
				"documentid" => $strDocumentID_a,
				"filename" => $strFilename_a
			);	

			$arrFormFields = [["FILES", $arrFiles],
							  ["STORAGEUSED", strval($intStorageUsed)]];
			update_document($objConn_a, $arrFormFields, [], $strDocumentID_a, []);

			$strJSONData = fetchValue_documentrepository($objConn_a, "jsondata", $strDocumentRepositoryID, []);
					
			if (strlen($strJSONData) > 0)
			{
				$arrJSONData = json_decode($strJSONData, true);								
				$strStorageUsed = formValueGetBySectionCodeFieldCode($arrJSONData, "g13c0b91f-45bd-492c-8b47-ce82b1757837", "STORAGEUSED");
				$intStorageUsed = intval($strStorageUsed, 10) + $intFilesize;	
				
				$arrFormFields = [["STORAGEUSED", strval($intStorageUsed)]];
				update_documentrepository($objConn_a, $arrFormFields, [], $strDocumentRepositoryID, []);
			}
		}
	}
}
