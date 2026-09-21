<?php

function actionPrintJobFetchNext($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameDevice = getTableNameEntity("device", false);
	$strTableNameDocument = getTableNameEntity("document", false);
    $strTableNamePrintQueue = getTableNameEntity("printqueue", false);
    $strTableNamePrintJob = getTableNameEntity("printjob", false);

    $blnResult = false;
    $strResult = "";

    if (dependencies('setting/settingGet') &&
		dependencies('process/reports/processGenerateDocument')) 
	{
		// permission check
		if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}
		
        $arrResult = array();
		$blnContinue = false;

        // initialisations
        $strClientID = $_SESSION['server_loggedin_clientid'];
        $strUserID = $_SESSION['server_loggedin_userid'];
        $strDeviceID = $_SESSION['server_deviceid'];
        $strPrintJobID = '';
		$strDevice = '';
		$strPrinter = '';

        // get a list of printer queues that we are monitoring based on our computer
        $arrPrinterQueueList = settingGet($objConn_a, 'CORE', 'PRINTFROMQ', $strClientID, '', '', $strDeviceID, __FUNCTION__);

        // iterate throught the queue list and create a queueid list
        $strQueueList = '';
        for ($intI = 0; $intI < count($arrPrinterQueueList); $intI++) 
		{
            $strQueueID = $arrPrinterQueueList[$intI];

            // verify the queueid
            $strSQL = "select id returnvalue from ~TABLENAMEPRINTQUEUE~ where client_id = ~CLIENTID~ and id = ~PRINTQUEUEID~";
            $strSQL = str_replace('~TABLENAMEPRINTQUEUE~', ff($strTableNamePrintQueue), $strSQL);
            $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
            $strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
            $strSQL = str_replace('~PRINTQUEUEID~', ff($strQueueID), $strSQL);
            $strQueueID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

            $strSQL = "select description returnvalue from ~TABLENAMEDEVICE~ where client_id = ~CLIENTID~ and id = ~DEVICEID~";
            $strSQL = str_replace('~TABLENAMEDEVICE~', ff($strTableNameDevice), $strSQL);
            $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
            $strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
            $strSQL = str_replace('~DEVICEID~', ff($strDeviceID), $strSQL);
            $strDevice = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			
			logPrinting("device: " . $strDevice . " at location: actionPrintJobFetchNext");	

            // if the queueid is valid, add it to the list
            if (strlen($strQueueID) > 0) 
			{
                if (strlen($strQueueList) == 0) 
				{
                    $strQueueList = $strQueueID;
                } 
				else 
				{
                    $strQueueList .= "," . $strQueueID;
                }
            }
        }

        // if we have a list of queues, check
        if (strlen($strQueueList) > 0) 
		{
			logPrinting("queuelist: " . $strQueueList . " at location: actionPrintJobFetchNext");	
			
            $strSQL = "select pj.id, pj.document_id, pj.jsondata jsondata, pj.gb398c9db_2272_4748_907c_d9685b924329_status status, gb398c9db_2272_4748_907c_d9685b924329_metadata metadata
						from ~TABLENAMEPRINTJOB~ pj, ~TABLENAMEPRINTQUEUE~ pq
						where 
						pj.client_id = ~CLIENTID~ and 
						pq.id = pj.printqueue_id and 
						pq.client_id = pj.client_id and 
						pq.id in (~PRINTQUEUELIST~) and 
						pj.gb398c9db_2272_4748_907c_d9685b924329_status in ('~STATUSPENDING~', '~STATUSPREPARING~', '~STATUSREADY~')
						order by pj.id";
            $strSQL = str_replace('~TABLENAMEPRINTJOB~', ff($strTableNamePrintJob), $strSQL);
            $strSQL = str_replace('~TABLENAMEPRINTQUEUE~', ff($strTableNamePrintQueue), $strSQL);
            $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
            $strSQL = str_replace('~PRINTQUEUELIST~', $strQueueList, $strSQL);
            $strSQL = str_replace('~STATUSPENDING~', STAT_PENDING, $strSQL);
            $strSQL = str_replace('~STATUSPREPARING~', STAT_PREPARING, $strSQL);
            $strSQL = str_replace('~STATUSREADY~', STAT_READY, $strSQL);
            
            $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
			$blnTryAgain = true;
            while (($arrRow = dbReadRecord($objResult)) && ($blnTryAgain)) 
			{
				$strPrintJobID = $arrRow["id"];
                $strDocumentID = $arrRow["document_id"];
				$strJSONData = $arrRow["jsondata"];
				//$strMetaData = $arrRow["metadata"];	// don't use the exposed metadata field as it is truncated JSON
				
				$arrJSONData = json_decode($strJSONData, true);
				$strStatus = $arrRow["status"];
				$strMoreInfo = "";
				$strMetaData = formValueGetBySectionCodeFieldCode($arrJSONData, "gb398c9db-2272-4748-907c-d9685b924329", "METADATA");
				logPrinting("metadata: " . $strMetaData . " at location: actionPrintJobFetchNext");	

				$arrMetaData = json_decode($strMetaData, true);

				// make sure that any non-completed statuses, the first is ready before resuming
				if ($strStatus == STAT_READY)
				{
					// make the status preparing
					// dbBeginTrans($objConn_a, __FUNCTION__);
					
					// $arrJSONData = json_decode($strJSONData, true);
					// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb398c9db-2272-4748-907c-d9685b924329', "STATUS", STAT_PREPARING);
					// $strJSONData = json_encode($arrJSONData);

					// $strSQL = "update ~TABLENAMEPRINTJOB~ set jsondata = '~JSONDATA~' where client_id = ~CLIENTID~ and id = ~PRINTJOBID~";
					// $strSQL = str_replace('~TABLENAMEPRINTJOB~', ff($strTableNamePrintJob), $strSQL);
					// $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
					// $strSQL = str_replace('~PRINTJOBID~', ff($strPrintJobID), $strSQL);
					// $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
					// dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

					// exposeEntityData($objConn_a, 'SYSTEMFORM', 'PRINTJOB', $strPrintJobID, $strJSONData);

					// $blnResult = dbEndTrans($objConn_a, __FUNCTION__);
					
					// fetch the filename
					$strSQL = "select filenamebase returnvalue from ~TABLENAMEDOCUMENT~ where id = ~DOCUMENTID~";
					$strSQL = str_replace('~TABLENAMEDOCUMENT~', ff($strTableNameDocument), $strSQL);
					$strSQL = str_replace('~DOCUMENTID~', ff($strDocumentID), $strSQL);
					$strFilenameBase = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
					$strDocumentRoot = getDocumentRootPathByDocumentID($objConn_a, $strDocumentID);
					$strPath = getClusterPath($strDocumentRoot, $strFilenameBase, false, true);

					//$strDocumentData = documentClusterLoad($strFilenameBase, $strFilenameBase . '-data.json', $strDocumentRoot);
					//$arrDocumentData = json_decode($strDocumentData, true);
					$strDocumentType = $arrMetaData["documenttype"];

					// printing meta contains:
					//	layout_id
					//	printertype_id
					//	numberofcopies
					//	leftmargin
					//	topmargin
					$strLayoutID = "";
					$intNumberOfCopies = "";
					$strPrinterID = "";

					// printer is known only by the actual computer performing the printing, not the one that put the job in the queue
					$strPrinterID = settingGet($objConn_a, 'CORE', 'P4OTH', $strClientID, '', '', $strDeviceID, __FUNCTION__);
					
					logPrinting("documenttype: " . $strDocumentType . " at location: actionPrintJobFetchNext");	
						
					if ((strlen($strPrintJobID) > 0) && (strlen($strPrinterID) > 0))
					{
						$strPrinter = settingGet($objConn_a, 'DISPATCH', 'PRINTER', $strClientID, '', $strPrinterID, $strDeviceID, __FUNCTION__);
						$strPrinterTypeID = settingGet($objConn_a, 'DISPATCH', 'PRINTERTYPE', $strClientID, '', $strPrinterID, $strDeviceID, __FUNCTION__);
						$strLeftMargin = settingGet($objConn_a, 'DISPATCH', 'LEFTMARGIN', $strClientID, '', $strPrinterID, $strDeviceID, __FUNCTION__);
						$strTopMargin = settingGet($objConn_a, 'DISPATCH', 'TOPMARGIN', $strClientID, '', $strPrinterID, $strDeviceID, __FUNCTION__);

						if (strlen($strPrinter) > 0)
						{
							logPrinting("printer: " . $strPrinter . " at location: actionPrintJobFetchNext");	

							//$strPrintingMeta = '{"layout_id": "'. $strLayoutID .'","printertype_id": "'. $strPrinterTypeID .'","numberofcopies": "'. $intNumberOfCopies .'","leftmargin": "'. $strLeftMargin .'","topmargin": "'. $strTopMargin .'" }';
							$objPrintingMeta = [
								'layout_id' => $strLayoutID,
								'printertype_id' => $strPrinterTypeID,
								'numberofcopies' => $intNumberOfCopies,
								'leftmargin' => $strLeftMargin,
								'topmargin' => $strTopMargin
							];
							$strPrintingMeta = json_encode($objPrintingMeta, JSON_FORCE_OBJECT);

							logPrinting("printingmeta: " . $strPrintingMeta . " at location: actionPrintJobFetchNext");	

							// create the document
							$arrResultDoc = processGenerateDocument($objConn_a, $strClientID, $strDocumentID, $strPrintingMeta);
							$strResult = $arrResultDoc["result"];
							$strError = $arrResultDoc["error"];
							$strDocumentID = $arrResultDoc["tag"];
							$strMetaData = $arrResultDoc["metadata"];
							$arrMetaData = json_decode($strMetaData, true);
							
							if ($strResult == STAT_ERROR)
							{
								$blnTryAgain = false;
								$strStatus = STAT_ERROR;
								$strMoreInfo = $strError;
								$blnContinue = true;
							}
							else
							{
								logPrinting("documentid: " . $strDocumentID . " at location: actionPrintJobFetchNext");	
								
								//$strMetaData = documentClusterLoad($strFilenameBase, $strFilenameBase . '-metadata.json', $strDocumentRoot);
								//$arrMetaData = json_decode($strMetaData, true);
								$intPages = $arrMetaData["pages"];
								$strOrientation = $arrMetaData["orientation"];
								$strPrinterType = $arrMetaData["printertype"];
								$strDocumentType = $arrMetaData["documenttype"];
								$strDocumentName = $arrMetaData["documentname"];
								$blnLegacy = toBoolean($arrMetaData["legacy"]);
								$strLegacy = 'N';
								if ($blnLegacy)
								{
									$strLegacy = 'Y';
								}

								$strFileType = '';
								if (strtoupper($strPrinterType) == 'POSTSCRIPT') 
								{
									$strFileType = '.html';
								} 
								else 
								{ 
									$strFileType = '.txt';
								}

								for ($intPage = 1; $intPage <= $intPages; $intPage++) 
								{
									$arrResult[] = array(
										"printjob_id" => secureEntityValue('PRINTJOB', $strPrintJobID),
										"printer" => $strPrinter,
										"printertype" => $strPrinterType,
										"orientation" => $strOrientation,
										"documentid" => secureEntityValue('DOCUMENT', $strDocumentID),
										"documenttype" => $strDocumentType,
										"documentname" => $strDocumentName,
										"path" => $strPath,
										"page" => $intPage,
										"pages" => $intPages,
										"legacy" => $strLegacy,
										"filename" => $strFilenameBase . '-' . $intPage . $strFileType,
									);
								}
								
								$blnTryAgain = false;
								$strStatus = STAT_PRINTING;
								$strMoreInfo = "";
								$blnContinue = true;
							}
						}
					}
				}
				else
				{
					$blnTryAgain = false;
				}
            } 
            dbCloseRecordset($objResult);
        }

        if ($blnContinue) 
		{
            dbBeginTrans($objConn_a, __FUNCTION__);
			
			// update the printer
			//$arrJSONData = json_decode($strJSONData, true);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb398c9db-2272-4748-907c-d9685b924329', "DEVICE", $strDevice);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb398c9db-2272-4748-907c-d9685b924329', "PRINTER", $strPrinter);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb398c9db-2272-4748-907c-d9685b924329', "STATUS", $strStatus);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb398c9db-2272-4748-907c-d9685b924329', "MOREINFO", $strMoreInfo);
			// UNCOMMENT BELOW LINE FOR TESTING
			//$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gb398c9db-2272-4748-907c-d9685b924329', "STATUS", STAT_READY);
			$strJSONData = json_encode($arrJSONData);

			$strSQL = "update ~TABLENAMEPRINTJOB~ set jsondata = '~JSONDATA~' where client_id = ~CLIENTID~ and id = ~PRINTJOBID~";
			$strSQL = str_replace('~TABLENAMEPRINTJOB~', ff($strTableNamePrintJob), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
			$strSQL = str_replace('~PRINTJOBID~', ff($strPrintJobID), $strSQL);
			$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			exposeEntityData($objConn_a, 'SYSTEMFORM', 'PRINTJOB', $strPrintJobID, $strJSONData);

            $blnResult = dbEndTrans($objConn_a, __FUNCTION__);
			
			logPrinting("print job returned at location: actionPrintJobFetchNext\n\n");	
        }
		else
		{
			$arrResult[] = array(
				"printjob_id" => ''
			);
			
			logPrinting("NO print job returned at location: actionPrintJobFetchNext\n\n");	

			$blnResult = true;
		}

        if ($blnResult) 
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
        } 
		else 
		{
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error fetching Print Job', array());
        }
    }

    return $strResult;
}
