<?php

function processGenerateFormReportData($objConn_a, $strClientID_a, $strUserID_a, $strDeviceID_a, $strDocumentTypeCode_a, $arrFilter_a)
{
    $arrResult = array("result"=> STAT_ERROR, "error"=>"Generate Timesheet Report Data Error", "tagtype"=>"", "tag"=>"", "path"=>"");

    if (dependencies('batch/batchJobAdd') &&
        dependencies('docs/documentSimpleAdd')) {

        // filter stuff
        $strFormReportID = '';
        $strFormReportHTML = '';

        foreach ($arrFilter_a as $objField) {
            $strFilterField = $objField['field'];
            $strFilterValue = $objField['value'];

            switch ($strFilterField) {
                case 'formreport_id':
                    $strFormReportID = $strFilterValue;
                    break;
                case 'html':
                    $strFormReportHTML = $strFilterValue;
                    break;
            }
		}
		
        // read the document type
        $strDocumentTypeID = getDocumentTypeIDByCode($objConn_a, $strDocumentTypeCode_a);
		$objDocument = json_encode(array('data' => $strFormReportHTML));


		dbBeginTrans($objConn_a, __FUNCTION__);

		$strCode = $strFormReportID; // put the issue id in here for labels so that people will find this type of document when searching for a issue id
		$strDescription = 'formreport-' . $strFormReportID;
		$strFilename = $strDescription . '.htm';
		$strComponentFilename = 'data.json';
		$strMetaData = '{ "timesheet_id":"' . $strFormReportID . '" }';

		//creates a json document
		$strDocumentID = documentSimpleAdd($objConn_a, $strClientID_a, $strUserID_a, $strDocumentTypeID, $strCode, $strDescription, $strComponentFilename, $strFilename, $objDocument, $strMetaData, DR_PRINTJOB, "");

		if (dbEndTrans($objConn_a, __FUNCTION__))
		{
			$arrResult["result"] = STAT_COMPLETED;
			$arrResult["error"] = "";
			$arrResult["tagtype"] = $arrResultReport["tagtype"];
			$arrResult["tag"] = $arrResultReport["tag"];
			$arrResult["path"] = $arrResultReport["path"];
		}
    }

    return $arrResult;
}
