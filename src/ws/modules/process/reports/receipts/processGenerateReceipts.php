<?php

// returns an error if there is one, or an empty string if no error
function processGenerateReceipts($objConn_a, $strDocumentID_a, $strDescription_a, $strFilenameBase_a, $arrJSONData_a, $arrDocumentMetaData_a, $arrPrintingMeta_a)
{
	$strTableNamePrinterType = getTableNameEntity("printertype", false);

    $arrResult = array("result"=> STAT_ERROR, "error"=>"Process Generate Receipts Error", "tagtype"=>"", "tag"=>"", "path"=>"", "metadata" =>"");

	//$strPrinterTypeID = $arrPrintingMeta_a['printertype_id'];
	$strPrinterType = "POSTSCRIPT"; //dbReadValueByID($objConn_a, $strTableNamePrinterType, "code", $strPrinterTypeID, __FUNCTION__);

	// TODO , search processGenerateReceiptData.php for LAYOUTIDTODO (here we should read the LayoutID)
	$strLayout = 'TYPEA';	// TODO: read from the metadata
	
	logPrinting("printertype: " . $strPrinterType . " at location: processGenerateReceipts");	
	logPrinting("layout: " . $strLayout . " at location: processGenerateReceipts");	

	if (strtoupper($strPrinterType) == 'POSTSCRIPT') 
    {
		if (dependencies('process/reports/receipts/processGenerateReceiptTypeA')) 
		{
			if (strtoupper($strLayout) == 'TYPEA') 
			{
				$arrResult = processGenerateReceiptTypeA($objConn_a, $strDocumentID_a, $strDescription_a, $strPrinterType, $strFilenameBase_a, $arrJSONData_a, $arrDocumentMetaData_a, $arrPrintingMeta_a);
			}        
		}
    }
	else if (strlen($strPrinterType) > 0) 
	{
		$arrResult = array("result"=> STAT_ERROR, "error"=>"Printer type is not supported for this document.", "tagtype"=>"", "tag"=>"", "path"=>"", "metadata" =>"");
	}

    return $arrResult;
}
?>