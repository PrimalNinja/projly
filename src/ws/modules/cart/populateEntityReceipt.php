<?php

function populateEntityReceipt($objConn_a, $strClientID_a, $strTransactionID_a, $blnInCart_a) 
{ 
    
    $strTableNameAccount = getTableNameEntity('account', false);
    $strTableNamePaymentStatus = getTableNameEntity("paymentstatus", false);
    $strTableNameProduct = getTableNameEntity("product", false);
    $strTableNameReceipt = getTableNameEntity("receipt", false);
	$strTableNameTransaction = getTableNameEntity("transaction", false);
	$strTableNameTransactionHistory = getTableNameEntity("transactionhistory", false);
	$strTableNameTransactionLine = getTableNameEntity("transactionline", false);
	$strTableNameTransactionLineHistory = getTableNameEntity("transactionlinehistory", false);
	$strTableNameTransactionLinePending = getTableNameEntity("transactionlinepending", false);
	$strTableNameTransactionPending = getTableNameEntity("transactionpending", false);
    
    $strLogin = $_SESSION['server_loggedin_user'];
    
    $arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "RECEIPT");

    // get the entity ids
    $strEntityID = getEntityID($objConn_a, "systemform");
    $strDataEntityID = getEntityID($objConn_a, "receipt");
    
    $strSectionGUID = "ff6b4c87cd-9b74-40de-9375-83f280731846";
    
    $strCode = 'RECEIPT';
    $strDescription = 'Receipt';
    $strEnabled = 'Y';
    
	$strSQL = "select id, g31f68ccc_e168_40b8_a7db_693353aa7aea_paymentdate paymentdate, g31f68ccc_e168_40b8_a7db_693353aa7aea_receiptnumber receiptnumber, document_id from ~TABLENAMETRANSACTION~ where id = ~TRANSACTIONID~";
    if ($blnInCart_a)
    {
		$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
    }
    else
    {
		$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransactionPending), $strSQL);
    }
	$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID_a), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

    $strInvoiceNo = '';
    $strInvoiceDate = getISODate();
    $strReceiptDocumentDescription = '';
    $strReceiptDocumentID = '';
    //$strTransactionID = '';
        
    if ($arrRow = dbReadRecord($objResult)) 
    {
        //$strTransactionID = //$arrRow['id'];
        $strInvoiceNo = $arrRow['receiptnumber'];        
        $strReceiptDocumentID = $arrRow['document_id'];
        $strReceiptDocumentDescription = $arrRow['receiptnumber'];
    }
    
    dbCloseRecordset($objResult);
    
    $strSubTotal = 0;
    $strGST = 0;
    $strTotal = 0;
    
    if (strlen($strTransactionID_a) > 0)
    {
        
		$strSQL = "
			select product_id, gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicanttype applicanttype, applicant_id, gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_description description, gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_product product, gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceincgst price_incgst, gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceexgst price_exgst, gffe35a8ad_d290_4ae3_8800_f2935dd30d07_graceperiod graceperiod 
			from ~TABLENAMETRANSACTIONLINE~ tl, ~TABLENAMEPRODUCT~ p 
			where p.id = tl.product_id and tl.transaction_id = ~TRANSACTIONID~";
		$strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);
        if ($blnInCart_a)
        {
			$strSQL = str_replace('~TABLENAMETRANSACTIONLINE~', ff($strTableNameTransactionLine), $strSQL);        
        }
        else
        {
			$strSQL = str_replace('~TABLENAMETRANSACTIONLINE~', ff($strTableNameTransactionLinePending), $strSQL);        
        }
		$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID_a), $strSQL);
        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        
        $arrTimePeriod = array('d' => 'day', 'm' => 'month', 'y' => 'year');
		$strSQL = "select id returnvalue from ~TABLENAMEPAYMENTSTATUS~ where code = 'PAID'";
		$strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);
		$strPaidID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
        
        
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
				$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
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

            $arrNegotiatedPrice = array(
                'priceincgst' => $arrRow['price_incgst'],
                'priceexgst' => $arrRow['price_exgst']
            );
            
			if (dependencies('cart/calculateNegotiatedPrice'))
			{
				$arrNegotiatedPrice = calculateNegotiatedPrice($objConn_a, $strProductID, $strClientID_a, $arrRow['price_incgst']);
			}
            
            if ((toBoolean($strInGracePeriod)) && ($arrNegotiatedPrice['priceincgst'] == 0))
            { 
                $floatPriceIncGST = 0;
                $floatPriceExGST = 0;
            }
            else
            { 

                $floatPriceIncGST = $arrNegotiatedPrice['priceincgst'];
                $floatPriceExGST = $arrNegotiatedPrice['priceexgst'];
            }
            
            $strSubTotal += $floatPriceIncGST - $floatPriceExGST;
            $strGST += $floatPriceExGST;        
        }
        
        $strSubTotal = number_format($strSubTotal,2);
        $strGST = number_format($strGST,2);
        $strTotal = number_format($strSubTotal + $strGST,2);

        dbCloseRecordset($objResult);
    }
    
    $strSQL = "select ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_accountname accountname, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_emailaddress emailaddress, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_billingaddressline1 addressline1, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_billingaddressline2 addressline2, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_billingpostcode postcode, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_billingsuburb billingsuburb, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_billingstate billingstate, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_abn abn from ~TABLENAMEACCOUNT~ where client_id = ~CLIENTID~";
    $strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    
    if ($arrRow = dbReadRecord($objResult)) 
    {
        $strBillingBillTo = $arrRow['accountname'];
        $strEmail = $arrRow['emailaddress'];
        $strBillingAddressLine1 = $arrRow['addressline1'];
        $strBillingAddressLine2 = $arrRow['addressline2'];
        $strSuburb = $arrRow['billingsuburb'];
        $strState = $arrRow['billingstate'];
        $strPostcode = $arrRow['postcode'];
        $strABN = $arrRow['abn'];
    }
    
    dbCloseRecordset($objResult);
                
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, $strSectionGUID, "INVOICENUMBER", $strInvoiceNo);
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, $strSectionGUID, "INVOICEDATE", $strInvoiceDate);
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, $strSectionGUID, "BILLINGBILLTO", $strBillingBillTo);
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, $strSectionGUID, "BILLINGADDRESSLINE1", $strBillingAddressLine1);
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, $strSectionGUID, "BILLINGADDRESSLINE2", $strBillingAddressLine2);
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, $strSectionGUID, "BILLINGSUBURB", $strSuburb);
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, $strSectionGUID, "BILLINGSTATE", $strState);
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, $strSectionGUID, "BILLINGPOSTCODE", $strPostcode);
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, $strSectionGUID, "ABN", $strABN);
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, $strSectionGUID, "SUBTOTAL", $strSubTotal);
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, $strSectionGUID, "GST", $strGST);
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, $strSectionGUID, "TOTAL", $strTotal);
    $arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, $strSectionGUID, "DOCUMENT", $strReceiptDocumentID, $strReceiptDocumentDescription);
    
    $strJSONData = json_encode($arrJSONData);
    
    dbBeginTrans($objConn_a, __FUNCTION__);

    $strSQL =
        "
    insert into ~TABLENAMERECEIPT~ (client_id, entity_id, dataentity_id, code, description, is_enabled, jsondata, data_client_id, modifyuser, modifydatetime)
    values (~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ENABLED~', '~JSONDATA~', ~DATACLIENTID~,'~MODIFYUSER~', '~MODIFYDATETIME~')
    ";

    $strSQL = str_replace('~TABLENAMERECEIPT~', ff($strTableNameReceipt), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
    $strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
    $strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
    $strSQL = str_replace('~CODE~', ff($strCode), $strSQL);
    $strSQL = str_replace('~DESCRIPTION~', ff($strDescription), $strSQL);
    $strSQL = str_replace('~ENABLED~', ff($strEnabled), $strSQL);
    $strSQL = str_replace('~DATACLIENTID~', ff($strClientID_a), $strSQL);
    $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
    $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
    $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);

    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

    $strReceiptID = dbLastInsertID($objConn_a);

    exposeEntityData($objConn_a, 'SYSTEMFORM', 'RECEIPT', $strReceiptID, $strJSONData);
        
    return dbEndTrans($objConn_a, __FUNCTION__);
}