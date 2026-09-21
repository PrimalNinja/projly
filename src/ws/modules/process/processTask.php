<?php
function processTask($objConn_a, $strClientID_a, $strSubtask_a, $strMetaData_a, $blnPreview_a)
{
	$arrResult = array("result"=> STAT_ERROR, "error"=>"Task Processing Error", "tagtype"=>"", "tag"=> "");
    
    if (dependencies('process/reports/processGenerateDocumentData,process/reports/processGenerateDocument,process/processUploads,print/printJobAdd'))
	{
		if ($strSubtask_a == 'PROCESS_GENERATEDOCUMENT') 
		{
			$arrResult = processGenerateDocumentData($objConn_a, $strClientID_a, $strMetaData_a, $blnPreview_a);
		} 
		else if ($strSubtask_a == 'PROCESS_UPLOADS') 
		{
			$arrResult = processUploads($objConn_a, $strClientID_a, $strMetaData_a, false);
		}
    }

    return $arrResult;
}
