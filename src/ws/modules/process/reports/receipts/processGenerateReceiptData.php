<?php

function processGenerateReceiptData($objConn_a, $strClientID_a, $strUserID_a, $strDeviceID_a, $strDocumentTypeCode_a, $arrFilter_a, $blnPreview_a)
{
    $strTableNamePaymentStatus = getTableNameEntity("paymentstatus", false);
    $strTableNameProduct = getTableNameEntity("product", false);
	$strTableNameTransaction = getTableNameEntity('transaction', false);
    $strTableNameTransactionHistory = getTableNameEntity("transactionhistory", false);
	$strTableNameTransactionLine = getTableNameEntity('transactionline', false);
    $strTableNameTransactionLineHistory = getTableNameEntity("transactionlinehistory", false);
	$strTableNameTransactionLinePending = getTableNameEntity("transactionlinepending", false);
	$strTableNameTransactionPending = getTableNameEntity("transactionpending", false);
	$strTableNameAccount = getTableNameEntity('account', false);
	$strTableNamePaymentmethod = getTableNameEntity('paymentmethod', false);;
	
	$arrResult = array("result"=> STAT_ERROR, "error"=>"Generate Receipt Report Data Error", "tagtype"=>"", "tag"=>"", "path"=>"");

    if (dependencies('batch/batchJobAdd') &&
        dependencies('setting/settingGet') &&
        dependencies('docs/documentSimpleAdd') &&
        dependencies('print/printJobAdd')) 
	{
        // filter stuff
        $strTransactionID = '';
        $strInCart = '';
        $blnInCart = true;
        
        foreach ($arrFilter_a as $objField) 
		{
            $strFilterField = $objField['field'];
            $strFilterValue = $objField['value'];

            //$strCartID = '';

            switch ($strFilterField) 
			{
                case 'transaction_id':
                    $strTransactionID = $strFilterValue;
                    break;
            
				case 'transactionhistory_id':
                    $strTransactionID = $strFilterValue;
                    break;
                
				case 'transactionpending_id':
                    $strTransactionID = $strFilterValue;
                    break;
                
				case 'in_cart':
                    $strInCart = $strFilterValue;
                    break;
            }
        }
        
        if (strlen($strTransactionID) > 0) 
		{
            $strClientID = $strClientID_a;
			$strSystemOwnerClientID = getSystemOwnerClientID($objConn_a);
            $blnInCart = $strInCart === 'Y';
            $arrTransaction = array();
            
			$strBusinessName = settingGet($objConn_a, 'CORE', 'BNAME', $strSystemOwnerClientID, '', '', '', __FUNCTION__);
			$strBusinessNumber = settingGet($objConn_a, 'CORE', 'BNUM', $strSystemOwnerClientID, '', '', '', __FUNCTION__);
			$strBusinessPhone = settingGet($objConn_a, 'CORE', 'BPHONE', $strSystemOwnerClientID, '', '', '', __FUNCTION__);
			$strBusinessAddress = settingGet($objConn_a, 'CORE', 'BADDR', $strSystemOwnerClientID, '', '', '', __FUNCTION__);
            
			$strSuburb = '';
            $strState = '';
            $strPostcode = '';
            
            $arrBusinessData = array(
                'name' => $strBusinessName,
                'abn' => $strBusinessNumber,
                'address' => $strBusinessAddress,
                'suburb' => $strSuburb,
                'state' => $strState,
                'postcode' => $strPostcode
            );
            
            $arrTransaction['businessdata'] = $arrBusinessData;
            $arrTransaction['invoicedate'] = getISODate();
            
            // fetch transaction (cart)
			$strSQL = "select id returnvalue from ~TABLENAMEPAYMENTSTATUS~ where code = 'PAID'";
			$strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);
			$strPaidID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			$strPayerName = '';
			$strPayerAddressLine1 = '';
			$strPayerAddressLine2 = '';
			$strPayerSuburb = '';
			$strPayerState = '';
			$strPayerPostcode = '';
			$strPayerNotes = '';
            
			$strSQL = "select 
				paymentstatus_id, 
				paymentmethod_id, 
				g31f68ccc_e168_40b8_a7db_693353aa7aea_payername payername,
				g31f68ccc_e168_40b8_a7db_693353aa7aea_payeraddressline1 payeraddressline1,
				g31f68ccc_e168_40b8_a7db_693353aa7aea_payeraddressline2 payeraddressline2,
				g31f68ccc_e168_40b8_a7db_693353aa7aea_payersuburb payersuburb,
				g31f68ccc_e168_40b8_a7db_693353aa7aea_payerstate payerstate,
				g31f68ccc_e168_40b8_a7db_693353aa7aea_payerpostcode payerpostcode,
				g31f68ccc_e168_40b8_a7db_693353aa7aea_payernotes payernotes,
				g31f68ccc_e168_40b8_a7db_693353aa7aea_paymentdate paymentdate, 
				g31f68ccc_e168_40b8_a7db_693353aa7aea_receiptnumber receiptnumber, 
				g31f68ccc_e168_40b8_a7db_693353aa7aea_is_paid is_paid, 
				document_id, 
				progress 
				from ~TABLETRANSACTION~ 
				where id = ~TRANSACTIONID~";

            if ($blnInCart)
            {
				$strSQL = str_replace('~TABLETRANSACTION~', ff($strTableNameTransaction), $strSQL);
            }
            else
            {
				$strSQL = str_replace('~TABLETRANSACTION~', ff($strTableNameTransactionPending), $strSQL);
            }

			$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
            
            $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
            
            if ($arrRow = dbReadRecord($objResult)) 
            {
                $arrTransaction['receiptnumber'] = $arrRow['receiptnumber'];
                $arrTransaction['is_paid'] = $arrRow['paymentstatus_id'] == $strPaidID;

				$strPayerName = $arrRow['payername'];
				$strPayerAddressLine1 = $arrRow['payeraddressline1'];
				$strPayerAddressLine2 = $arrRow['payeraddressline2'];
				$strPayerSuburb = $arrRow['payersuburb'];
				$strPayerState = $arrRow['payerstate'];
				$strPayerPostcode = $arrRow['payerpostcode'];
				$strPayerNotes = $arrRow['payernotes'];
                //$arrTransaction['invoicedate'] = $arrRow['paymentdate'];
            }
            
            dbCloseRecordset($objResult);
            
            $arrPayerData = array(
                'payername' => $strPayerName,
                'addressline1' => $strPayerAddressLine1,
				'addressline2' => $strPayerAddressLine2,
                'suburb' => $strPayerSuburb,
                'state' => $strPayerState,
                'postcode' => $strPayerPostcode,
				'notes' => $strPayerNotes
            );
			
            $arrTransaction['payerdata'] = $arrPayerData;

            // fetch transactionline (cartline)
            
			$strSQL = "select product_id, gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicanttype applicanttype, applicant_id, gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_description description, gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_product product, gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceincgst price_incgst, gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceexgst price_exgst, gffe35a8ad_d290_4ae3_8800_f2935dd30d07_graceperiod graceperiod 
						from ~TABLENAMETRANSACTIONLINE~ tl, ~TABLENAMEPRODUCT~ p 
						where p.id = tl.product_id and tl.transaction_id = ~TRANSACTIONID~";
			$strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);

            if ($blnInCart)
            {
				$strSQL = str_replace('~TABLENAMETRANSACTIONLINE~', ff($strTableNameTransactionLine), $strSQL);        
            }
            else
            {                    
				$strSQL = str_replace('~TABLENAMETRANSACTIONLINE~', ff($strTableNameTransactionLinePending), $strSQL);        
            }

			$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
        
			$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        
			$arrTimePeriod = array('d' => 'day', 'm' => 'month', 'y' => 'year');
            $arrTransactionLines = array();
            
            while ($arrRow = dbReadRecord($objResult)) 
			{ 
                $strGracePeriod = $arrRow['graceperiod'];
                $strProductID = $arrRow['product_id'];
                $strApplicantID = $arrRow['applicant_id'];
                $strApplicantType = $arrRow['applicanttype'];
                $strInGracePeriod = 'N';

                if (strlen($strGracePeriod) > 0)
                {   
                    $strSQL = "";
                    if ($strApplicantType == 'CLIENT')
                    {
                        $strSQL = "
            select max(applicationdate) returnvalue from (
                select t.applicationdate from ~TABLENAMETRANSACTIONHISTORY~ t, ~TABLENAMETRANSACTIONLINEHISTORY~ tl 
                where t.client_id = ~CLIENTID~ and tl.transactionhistory_id = t.id and tl.gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicanttype = 'CLIENT' and tl.applicant_id = ~APPLICANTID~ and tl.product_id = ~PRODUCTID~
                union
                select t.applicationdate from ~TABLENAMETRANSACTIONPENDING~ t, ~TABLENAMETRANSACTIONLINEPENDING~ tl 
                where t.client_id = ~CLIENTID~ and tl.transactionpending_id = t.id and tl.gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicanttype = 'CLIENT' and tl.applicant_id = ~APPLICANTID~ and tl.product_id = ~PRODUCTID~ and t.paymentstatus_id = ~PAIDID~
            ) temp
            ";
                    }
                    else
                    {
                        // force no application date for non-client
                        $strSQL = "select '' returnvalue from ~TABLENAMETRANSACTIONHISTORY~ where 1=0";
                    }
                    $strSQL = str_replace('~TABLENAMETRANSACTIONPENDING~', ff($strTableNameTransactionPending), $strSQL);
                    $strSQL = str_replace('~TABLENAMETRANSACTIONHISTORY~', ff($strTableNameTransactionHistory), $strSQL);
                    $strSQL = str_replace('~TABLENAMETRANSACTIONLINEHISTORY~', ff($strTableNameTransactionLineHistory), $strSQL);
                    $strSQL = str_replace('~TABLENAMETRANSACTIONLINEPENDING~', ff($strTableNameTransactionLinePending), $strSQL);
                    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
                    $strSQL = str_replace('~PRODUCTID~', ff($strProductID), $strSQL);
                    $strSQL = str_replace('~APPLICANTID~', ff($strApplicantID), $strSQL);
                    $strSQL = str_replace('~PAIDID~', ff($strPaidID), $strSQL);
                    $strApplicationDate = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

                    if (strlen($strApplicationDate) > 0)
                    { 
						$strNumber = $strGracePeriod;
						$strNumber = str_replace("d", "", $strNumber);
						$strNumber = str_replace("m", "", $strNumber);
						$strNumber = str_replace("y", "", $strNumber);
						$strDMY = str_replace($strNumber, "", $strGracePeriod);

                        $strTimePeriod = $arrTimePeriod[$strDMY];

                        $strGracePeriodTimeStamp = strtotime($strApplicationDate . " + $strNumber $strTimePeriod");

                        if ($strGracePeriodTimeStamp > time())
                        {                     
                            $strInGracePeriod = 'Y';
                        }
                    }
                }
                
				$floatPriceIncGST = $arrRow['price_incgst'];
				$floatPriceExGST = $arrRow['price_exgst'];
                
                $arrTransactionLines[] = array(
                    'description'  => $arrRow['description'],
                    'price_incgst' => $floatPriceIncGST,
                    'price_exgst'  => $floatPriceExGST,
                    'ingraceperiod' => $strInGracePeriod
                );
            }
            
            dbCloseRecordset($objResult);
            
            $arrTransaction['transactionlines'] = $arrTransactionLines;
                        
            $strSQL = "select * from ~TABLEENTITYACCOUNT~ where client_id = ~CLIENTID~";
            $strSQL = str_replace('~TABLEENTITYACCOUNT~', ff($strTableNameAccount), $strSQL);
            $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
            
            $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
            
            if ($arrRow = dbReadRecord($objResult)) 
            {
                $arrClientData = array(
					'ownerclient'   => ($strSystemOwnerClientID == $strClientID),
                    'clientname'   => $arrRow['ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_accountname'],
                    'businessname' => $arrRow['ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_businessname'],
                    'abn'          => $arrRow['ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_abn'],
                    'address1'     => $arrRow['ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_billingaddressline1'],
                    'address2'     => $arrRow['ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_billingaddressline2'],
                    'suburb'       => $arrRow['ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_billingsuburb'],
                    'state'        => $arrRow['ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_billingstate'],
                    'postcode'     => $arrRow['ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_billingpostcode']
                );
            }
            
            dbCloseRecordset($objResult);
                        
            $arrTransaction['clientdata'] = $arrClientData;
            
            //fetch methods
            $strSQL = "select code, description, ffb775d2a9_59b7_453b_91ce_7621548ffe81_taxinvoicenotes as notes 
						from ~TABLEENTITYPAYMENTMETHOD~ 
						where 
						is_enabled='~ENABLED~' and 
						ffb775d2a9_59b7_453b_91ce_7621548ffe81_taxinvoicenotes IS NOT NULL and 
						ffb775d2a9_59b7_453b_91ce_7621548ffe81_taxinvoicenotes <> ''  
						order by cast(ffb775d2a9_59b7_453b_91ce_7621548ffe81_displayorder as unsigned)";
            
            $strSQL = str_replace('~TABLEENTITYPAYMENTMETHOD~', ff($strTableNamePaymentmethod), $strSQL);
            $strSQL = str_replace('~ENABLED~', "Y", $strSQL);
            
            $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
            $arrPaymentMethods = array();
            
            while ($arrRow=dbReadRecord($objResult))
            { 
                $arrPaymentMethods[] = array(
                    'code' => $arrRow['code'],
                    'description' => $arrRow['description'],
                    'notes' => $arrRow['notes']
                );                                
            }
            
            dbCloseRecordset($objResult);
                        
            $arrDocumentData = array();
            $arrDocumentData['transaction'] = $arrTransaction;
            $arrDocumentData['paymentmethods'] = $arrPaymentMethods;
            
            // number of copies
            //debug('1');
            // copies not set JT
            $intNumberOfCopies = 1;
            $arrDocumentData['numberofcopies'] = $intNumberOfCopies;

            // read the document type
            $strDocumentTypeID = getDocumentTypeIDByCode($objConn_a, $strDocumentTypeCode_a);
            
            // set margins
            $arrMargins = array('top' => 0, 'left' => 0);
            $arrDocumentData['margins'] = json_encode($arrMargins);

            $objDocument = json_encode($arrDocumentData);

            dbBeginTrans($objConn_a, __FUNCTION__);

            // START print job updates
            $strCartID = $strTransactionID;
            $strCode = $strCartID; //$objOrderFirstItem['ordercode']; 
            $strDescription = 'receipt-' . $arrTransaction['receiptnumber']; //$objOrderFirstItem['ordercode'];
            $strFilename = $strDescription . '.pdf';
			$strComponentFilename = 'data.json';
            $strMetaData = '{ "cart_id":"' . $strCartID . '" }';
            
            $strDocumentID = documentSimpleAdd($objConn_a, $strClientID_a, $strUserID_a, $strDocumentTypeID, $strCode, $strDescription, $strComponentFilename, $strFilename, $objDocument, $strMetaData, DR_PRINTJOB, "");

			// document created, spool the document
			if (strlen($strDocumentID))
			{
				if ($blnPreview_a)
				{
					// printing meta contains:
					//	layout_id
					//	printertype_id
					//	numberofcopies
					//	leftmargin
					//	topmargin

					$strLayoutID = '';	// TODO need to add the concept of receipt layouts as a table and a setting, search processGenerateReceipts.php for LAYOUTIDTODO
					$intNumberOfCopies = 1;
					$strPrinterID = 0; //settingGet($objConn_a, 'CORE', 'P4OTH', $strClientID_a, '', '', $strDeviceID_a, __FUNCTION__);
					$strPrinterTypeID = 0; //settingGet($objConn_a, 'DISPATCH', 'PRINTERTYPE', $strClientID_a, '', $strPrinterID, $strDeviceID_a, __FUNCTION__);
					$strLeftMargin = 0; //settingGet($objConn_a, 'DISPATCH', 'LEFTMARGIN', $strClientID_a, '', $strPrinterID, $strDeviceID_a, __FUNCTION__);
					$strTopMargin = 0; //settingGet($objConn_a, 'DISPATCH', 'TOPMARGIN', $strClientID_a, '', $strPrinterID, $strDeviceID_a, __FUNCTION__);

					//$strPrintingMeta = '{"layout_id": "'. $strLayoutID .'","printertype_id": "'. $strPrinterTypeID .'","numberofcopies": "'. $intNumberOfCopies .'","leftmargin": "'. $strLeftMargin .'","topmargin": "'. $strTopMargin .'" }';
					$objPrintingMeta = new genericObject();
					$objPrintingMeta->layout_id = $strLayoutID;
					$objPrintingMeta->printertype_id = $strPrinterTypeID;
					$objPrintingMeta->numberofcopies = $intNumberOfCopies;
					$objPrintingMeta->leftmargin = $strLeftMargin;
					$objPrintingMeta->topmargin = $strTopMargin;
					$strPrintingMeta = json_encode($objPrintingMeta);

					// create the document
					$arrResult = processGenerateDocument($objConn_a, $strClientID_a, $strDocumentID, $strPrintingMeta);
					$strDocumentID = $arrResult["tag"];
					$strMetaData = $arrResult["metadata"];
					$arrMetaData = json_decode($strMetaData, true);
				}
				else
				{
					// create a printjob instead
					$strPrintJobCode = $strCode;
					$strPrintJobDescription = $strDescription;
					$strPrinter = '';

					$strPrintJobID = printJobAdd($objConn_a, $strClientID_a, $strUserID_a, $strDocumentID, $strPrintJobCode, $strPrintJobDescription, $strPrinterQueueID, STAT_READY, $strPrinter, $strMetaData);
				}
			}

			dbEndTrans($objConn_a, __FUNCTION__);

            if (strlen($strDocumentID) > 0)
			{
				$arrResult["result"] = STAT_COMPLETED;
				$arrResult["error"] = "";
				$arrResult["tagtype"] = 'document_id';
				$arrResult["tag"] = $strDocumentID;
                $arrResult["path"] = '';
			}
        }
    }

    return $arrResult;
}
