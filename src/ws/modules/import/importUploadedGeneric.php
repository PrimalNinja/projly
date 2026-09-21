<?php
function importUploadedGeneric($objConn_a, $strImportedFile_a, $strFilename_a, $strOriginalFilename_a, $strClientID_a)
{
	$strTableNameDocument = getTableNameEntity("document", false);
	
	$arrResult = array("result"=> STAT_ERROR, "error"=>"Upload Generic File Processing Error", "tagtype"=>"", "tag"=> "");

    if (dependencies('docs/documentSimpleCopy,docs/documentUpdate') &&
        dependencies('utils/import')) 
	{
        $strLogin = 'upload'; // future somehow workout the user (have it added to the metadata)
        $strUserID = '';

        // begin trans before we change our live tables with the data from the temporary table
        dbBeginTrans($objConn_a, __FUNCTION__);

        $strImageCode = '';

        // read the document type
        $strDocumentTypeID = getDocumentTypeIDByCode($objConn_a, DOCUMENTTYPE_GENERIC);

        // add the original document along with the error list as notes
        $strDescription = $strOriginalFilename_a;
        $strMetaData = json_encode(['originalfilename' => $strOriginalFilename_a]);
        $strDocumentID = documentSimpleCopy($objConn_a, $strClientID_a, $strUserID, $strDocumentTypeID, $strImageCode, $strDescription, $strOriginalFilename_a, TEMP_PENDING_PATH . $strFilename_a, $strMetaData, "", "");

        $strNotes = "";
        $strTags = createTagsFromFilename($strOriginalFilename_a);
        documentUpdate($objConn_a, $strClientID_a, $strDocumentID, $strNotes, $strTags);

        // get filename description
        $strSQL = "select description returnvalue from ~TABLENAMEDOCUMENT~ where client_id = ~CLIENTID~ and id = ~DOCUMENTID~";
		$strSQL = str_replace('~TABLENAMEDOCUMENT~', ff($strTableNameDocument), $strSQL);
        $strSQL = str_replace('~DOCUMENTID~', ff($strDocumentID), $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
        $strFileDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        if (dbEndTrans($objConn_a, __FUNCTION__))
        {
			$arrResult["result"] = STAT_COMPLETED;
			$arrResult["error"] = "";
			$arrResult["tagtype"] = "documentid";
			$arrResult["tag"] = $strDocumentID;
            $arrResult["description"] = $strFileDescription;
        }
    }

    return $arrResult;
}
