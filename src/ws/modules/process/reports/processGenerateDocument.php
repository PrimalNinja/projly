<?php

// returns an error if there is one, or an empty string if no error
function processGenerateDocument($objConn_a, $strClientID_a, $strDocumentID_a, $strPrintingMeta_a)
{
	$arrResult = array("result"=> STAT_ERROR, "error"=>"Process Generate Document Error", "tagtype"=>"", "tag"=>"", "path"=>"", "metadata" =>"");

	$strTableNameDocument = getTableNameEntity("document", false);
	$strTableNameDocumentType = getTableNameEntity("documenttype", false);
	$strTableNamePrinterType = getTableNameEntity("printertype", false);

    if (dependencies('docs/documentClusterLoad') &&
		dependencies('process/reports/formreport/processGenerateFormReport') && 
		dependencies('process/reports/receipts/processGenerateReceipts'))
	{
		logPrinting("printingmeta: " . $strPrintingMeta_a . " at location: processGenerateDocument");	
		
        $arrPrintingMeta = json_decode($strPrintingMeta_a, true);

        // get the document type
        $strSQL = "
	select dt.code returnvalue
	from ~TABLENAMEDOCUMENTTYPE~ dt, ~TABLENAMEDOCUMENT~ d
	where dt.id = d.documenttype_id and d.id = ~DOCUMENTID~
	";
		$strSQL = str_replace('~TABLENAMEDOCUMENT~', ff($strTableNameDocument), $strSQL);
		$strSQL = str_replace('~TABLENAMEDOCUMENTTYPE~', ff($strTableNameDocumentType), $strSQL);
        $strSQL = str_replace('~DOCUMENTID~', $strDocumentID_a, $strSQL);
        $strDocumentTypeCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

        // read the base filename, description
		$strDescription = "";
		$strFilenameBase = "";
		$strMetaData = "";
		
		$strSQL = "select ffe70fd7f1_401b_4c5c_b096_76f67d466982_description description, filenamebase, metadata from ~TABLENAMEDOCUMENT~ where id = ~DOCUMENTID~";
		$strSQL = str_replace('~TABLENAMEDOCUMENT~', ff($strTableNameDocument), $strSQL);
        $strSQL = str_replace('~DOCUMENTID~', ff($strDocumentID_a), $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

		if ($arrRow = dbReadRecord($objResult)) 
		{
			$strDescription = $arrRow['description'];			
			$strFilenameBase = $arrRow['filenamebase'];
			$strMetaData = $arrRow['metadata'];	// this is the document metadata, not the printing metadata
        }
		dbCloseRecordset($objResult);

        $arrDocumentMetaData = json_decode($strMetaData, true);
		logPrinting("documentmetadata: " . $arrDocumentMetaData . " at location: processGenerateDocument");	

        // load the data from the datafile
		$strDocumentRoot = getDocumentRootPathByDocumentID($objConn_a, $strDocumentID_a);
        $strJSONData = documentClusterLoad($strFilenameBase, $strFilenameBase . '-' . 'data.json', $strDocumentRoot);
        $arrJSONData = json_decode($strJSONData, true);
		
		logPrinting("jsondata: " . $strJSONData . " at location: processGenerateDocument");	
				
		if (strtoupper($strDocumentTypeCode) == 'FOR') 
        {
			$strPrinterTypeID = $arrPrintingMeta['printertype_id'];
			$strPrinterType = dbReadValueByID($objConn_a, $strTableNamePrinterType, "code", $strPrinterTypeID, __FUNCTION__);
            $arrResult = processGenerateFormReport($objConn_a, $strDocumentID_a, $strDescription, $strPrinterType, $strFilenameBase, $arrJSONData);
        }
        else if (strtoupper($strDocumentTypeCode) == 'REC') 
        {
            $arrResult = processGenerateReceipts($objConn_a, $strDocumentID_a, $strDescription, $strFilenameBase, $arrJSONData, $arrDocumentMetaData, $arrPrintingMeta);
        }
    }
	
    return $arrResult;
}
