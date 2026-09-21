<?php

// transaction statuses:
//	COMPLETE = Product Delivered
//	PEN-APP = Pending Processing of Application
//	PEN-PAY = Pending Confirmation of Payment
// 	PEN-PROCESS = Pending Processing (in processing, not yet known if PEN-APP or PEN-PAY)
//	CANCELLED = cancelled
//
// if the total price is 0 then:
//
// 		paymentStatus = 'FREE'
//		transactionStatus = 'PEN-PROCESS' (upon commit products, it may go to PEN-APP or COMPLETE)
//
// else if the total price is > 0 and an immediate payment type (cc or paypal) then:
//
// 		paymentStatus = 'PAID'
//		transactionStatus = 'PEN-PROCESS' (upon commit products, it may go to PEN-APP or COMPLETE)
//
// else if the total price is > 0 and is not an immediate payment type (bank transfer) then:
//
//		paymentStatus = 'PEN-PAY'
//		transactionStatus = 'PEN-PAY'
// endif
//
// if no errors so far
//		update flag IS_PAID = 'Y' with payment status of paymentStatus
// 		call the payment gateway and store the response
// endif
//
// move the transaction to tbltransactionpending, transactionline to tbltransactionlinepending
//
function submitPayment($objConn_a, $strClientID_a, $strLogin_a, $strPaymentDate_a, $blnIsImmediate_a, $strPaymentMethodID_a, $arrCard_a)
{
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNamePaymentMethod = getTableNameEntity("paymentmethod", false);
	$strTableNamePaymentStatus = getTableNameEntity("paymentstatus", false);
	$strTableNameProduct = getTableNameEntity("product", false);
	$strTableNameTransaction = getTableNameEntity("transaction", false);
	$strTableNameTransactionLine = getTableNameEntity("transactionline", false);
	$strTableNameTransactionLinePending = getTableNameEntity("transactionlinepending", false);
	$strTableNameTransactionPending = getTableNameEntity("transactionpending", false);
	
    $arrResult = [];
	$strResult = "";

	$fltTotalIncGST = 0;
	$strPaymentDescription = '';
    $strPaymentMethodDescription = '';
	$strPaymentStatusID = '';
	$strPaymentStatusDescription = '';
	$strStatusCode = '';
	$strStatusDescription = '';
	$strTransactionPendingID = '';	// this is the resulting transactionid
    
    if (strlen($strPaymentMethodID_a) > 0)
    {    
        $strSQL = "select ffb775d2a9_59b7_453b_91ce_7621548ffe81_description returnvalue from ~TABLENAMEPAYMENTMETHOD~ where id = ~PAYMENTMETHODID~";
        $strSQL = str_replace('~TABLENAMEPAYMENTMETHOD~', ff($strTableNamePaymentMethod), $strSQL);	
        $strSQL = str_replace('~PAYMENTMETHODID~', ff($strPaymentMethodID_a), $strSQL);	
        $strPaymentMethodDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
    }
    
	$strSQL = "select id returnvalue from ~TABLENAMETRANSACTION~ where client_id = ~CLIENTID~";
	$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strTransactionID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	
	if (strlen($strTransactionID) > 0)
	{
		$strSQL = "select g31f68ccc_e168_40b8_a7db_693353aa7aea_receiptnumber returnvalue from ~TABLENAMETRANSACTION~ where id = ~TRANSACTIONID~";
		$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
		$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
		$strInvoiceCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

		$strSQL = "
select tl.id, tl.product_id productid, p.description productdescription, tl.gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceincgst priceincgst 
from ~TABLENAMETRANSACTIONLINE~ tl, ~TABLENAMEPRODUCT~ p 
where p.id = tl.product_id and tl.transaction_id = ~TRANSACTIONID~";
		$strSQL = str_replace('~TABLENAMETRANSACTIONLINE~', ff($strTableNameTransactionLine), $strSQL);
		$strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);
		$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
		$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		while ($arrRow = dbReadRecord($objResult)) 
		{
			$strProductID = $arrRow['productid'];
			$strDescription = $arrRow['productdescription'];
			$fltPriceIncGST = $arrRow['priceincgst'];
			
			$fltTotalIncGST += $fltPriceIncGST;
			
			if (strlen($strPaymentDescription) > 0)
			{
				$strPaymentDescription .= ", ";
			}
		}
		dbCloseRecordset($objResult);

		$strInvoiceDescription = $strPaymentDescription;
		
		$strFullName = "";
		$strEmail = "";
		$strAddress = "";
		$strPostcode = "";

		$strSQL = "select ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_accountname accountname, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_emailaddress emailaddress, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_billingaddressline1 addressline1, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_billingaddressline2 addressline2, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_billingpostcode postcode from ~TABLENAMEACCOUNT~ where client_id = ~CLIENTID~";
		$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
		$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		while ($arrRow = dbReadRecord($objResult)) 
		{
			$strFullName = $arrRow['accountname'];
			$strEmail = $arrRow['emailaddress'];
			$strAddress = trim($arrRow['addressline1'] . " " . $arrRow['addressline2']);
			$strPostcode = $arrRow['postcode'];
		}
		dbCloseRecordset($objResult);

		$blnTransactionPending = false;
		
		// do all the payment functionality before performing the actual payment via a payment gateway
		// this is so that if the payment gateway was to fail, we can rollback this functionality
		// if done in referse since the payment gateway cannot be within a transaction, then we would have taken the customer's
		// payment and internally failed to track it
		dbBeginTrans($objConn_a, __FUNCTION__);
		
		if ($fltTotalIncGST == 0)
		{
			// free
			$strSQL = "select id returnvalue from ~TABLENAMEPAYMENTSTATUS~ where code in ('COMPLIMENTARY','FREE')";
			$strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);	
			$strPaymentStatusID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
            
            $strSQL = "select description returnvalue from ~TABLENAMEPAYMENTSTATUS~ where code in ('COMPLIMENTARY','FREE')";
			$strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);	
			$strPaymentStatusDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			
			$strStatusCode = 'PEN-PROCESS';
			$strStatusDescription = 'Pending Processing';
				
			$blnTransactionPending = true;
		}
		else if ($blnIsImmediate_a)
		{
			if (strlen($strPaymentMethodID_a) == 0)
			{
				$strResult = "Payment method is required.";
				dbRaiseCustomError($objConn_a, $strResult);
			}
			else
			{
				// paid
				$strSQL = "select id returnvalue from ~TABLENAMEPAYMENTSTATUS~ where code = 'PAID'";
				$strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);	
				$strPaymentStatusID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
                
                $strSQL = "select description returnvalue from ~TABLENAMEPAYMENTSTATUS~ where code = 'PAID'";
                $strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);	
                $strPaymentStatusDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
				
				$strStatusCode = 'PEN-PROCESS';
				$strStatusDescription = 'Pending Processing';
				
				$blnTransactionPending = true;
			}
		}
		else
		{
			if (strlen($strPaymentMethodID_a) == 0)
			{
				$strResult = "Payment method is required.";
				dbRaiseCustomError($objConn_a, $strResult);
			}
			else
			{
				// pending confirmation
				$strSQL = "select id returnvalue from ~TABLENAMEPAYMENTSTATUS~ where code = 'PEN-PAY'";
				$strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);	
				$strPaymentStatusID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
                
                $strSQL = "select description returnvalue from ~TABLENAMEPAYMENTSTATUS~ where code = 'PEN-PAY'";
                $strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);	
                $strPaymentStatusDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
				
				$strStatusCode = 'PEN-PAY';
				$strStatusDescription = 'Pending Confirmation of Payment';
				
				$blnTransactionPending = true;
			}
		}
		
		if ((strlen($strResult) == 0) && (strlen($strPaymentMethodID_a) == 0))	// for FREE or GRACE
		{
			// update the status
			$strSQL = "select jsondata returnvalue from ~TABLENAMETRANSACTION~ where id = ~TRANSACTIONID~";
			$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
			$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
			$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			                                    
			$arrJSONData = json_decode($strJSONData, true);

            $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYMENTDATE", $strPaymentDate_a);
            $arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYMENTSTATUS", $strPaymentStatusID, $strPaymentStatusDescription);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "IS_PAID", 'Y');
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "STATUSCODE", $strStatusCode);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "STATUSDESCRIPTION", $strStatusDescription);
            
            
			$strJSONData = json_encode($arrJSONData);
			
			// update transaction to paid
			$strSQL = "update ~TABLENAMETRANSACTION~ set jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where id = ~TRANSACTIONID~";
			$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
			$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
			$strSQL = str_replace('~JSONDATA~', ffn($strJSONData), $strSQL);	
			$strSQL = str_replace('~MODIFYUSER~', ff($strLogin_a), $strSQL);
			$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				
			exposeEntityData($objConn_a, 'SYSTEMFORM', 'TRANSACTION', $strTransactionID, $strJSONData);
		}
		
		if ((strlen($strResult) == 0) && (strlen($strPaymentMethodID_a) > 0))	// for non-FREE
		{
			// update the status
			$strSQL = "select jsondata returnvalue from ~TABLENAMETRANSACTION~ where id = ~TRANSACTIONID~";
			$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
			$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
			$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			                                    
			$arrJSONData = json_decode($strJSONData, true);
			
            $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYMENTDATE", $strPaymentDate_a);
            $arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYMENTMETHOD", $strPaymentMethodID_a,  $strPaymentMethodDescription);
            $arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYMENTSTATUS", $strPaymentStatusID, $strPaymentStatusDescription);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "IS_PAID", 'Y');
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "STATUSCODE", $strStatusCode);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "STATUSDESCRIPTION", $strStatusDescription);
            
			$strJSONData = json_encode($arrJSONData);
			
			// update transaction to paid
			$strSQL = "update ~TABLENAMETRANSACTION~ set jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where id = ~TRANSACTIONID~";
			$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
			$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
			$strSQL = str_replace('~JSONDATA~', ffn($strJSONData), $strSQL);	
			$strSQL = str_replace('~MODIFYUSER~', ff($strLogin_a), $strSQL);
			$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				
			exposeEntityData($objConn_a, 'SYSTEMFORM', 'TRANSACTION', $strTransactionID, $strJSONData);
			
			// pay for the product if using cc
			$blnIsCC = false;
			$strSQL = "select ffb775d2a9_59b7_453b_91ce_7621548ffe81_iscc returnvalue from ~TABLENAMEPAYMENTMETHOD~ where id = ~PAYMENTMETHODID~";
			$strSQL = str_replace('~TABLENAMEPAYMENTMETHOD~', ff($strTableNamePaymentMethod), $strSQL);	
			$strSQL = str_replace('~PAYMENTMETHODID~', ff($strPaymentMethodID_a), $strSQL);	
			$blnIsCC = toBoolean(dbReadValue($objConn_a, $strSQL, __FUNCTION__));
			
			if ($blnIsCC && ($fltTotalIncGST > 0))
			{
				$strCCName = $arrCard_a['ccaccountname'];
				$strCCNumber = $arrCard_a['ccnumber'];
				$strCCNumber = str_replace(" ", "", $strCCNumber);
				//$strCCNumber = '4111111111111111';
				$strCCMonth = $arrCard_a['ccmonth'];
				$strCCYear = $arrCard_a['ccyear'];;
				$strCCSecurityCode = $arrCard_a['ccsecuritycode'];;
				
				if (dependencies('plugins/paymentplugins/' . PAYMENTPROVIDER)) 
				{
					logDebug(PAYMENTPROVIDER . ': ' . $strFullName . ', ' . $strEmail . ', ' . $strAddress . ', ' . $strPostcode . ', ' . $strInvoiceDescription . ', ' . $strInvoiceCode . ', ' . $strCCName . ', ' . $strCCNumber . ', ' . $strCCMonth . ', ' . $strCCYear . ', ' . $strCCSecurityCode . ', ' . $fltTotalIncGST, '');
					$strResult = call_user_func(PAYMENTPROVIDER, $strFullName, $strEmail, $strAddress, $strPostcode, $strInvoiceDescription, $strInvoiceCode, $strCCName, $strCCNumber, $strCCMonth, $strCCYear, $strCCSecurityCode, $fltTotalIncGST);

					// rollback the transaction (raise a custom error then endtrans) then start a new one for the payment gate way response
					// we must do this regardless of error or not, because if we previously had an error we need to remove it
					if (strlen($strResult) > 0)
					{
						dbRaiseCustomError($objConn_a, $strResult);	// raise the custom error before end trans
					}
					dbEndTrans($objConn_a, __FUNCTION__);
					if (strlen($strResult) > 0)
					{
						$blnTransactionPending = false;	// the transaction is not pending if we have an error
					}
					dbBeginTrans($objConn_a, __FUNCTION__);
					
					$strSQL = "select jsondata returnvalue from ~TABLENAMETRANSACTION~ where id = ~TRANSACTIONID~";
					$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
					$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
					$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
					
					$arrJSONData = json_decode($strJSONData, true);
					
					$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYMENTGATEWAYRESPONSE", $strResult);
		
					$strJSONData = json_encode($arrJSONData);
					
					// store the payment response
					$strSQL = "update ~TABLENAMETRANSACTION~ set jsondata = '~JSONDATA~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where id = ~TRANSACTIONID~";
					$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
					$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
					$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);	
					$strSQL = str_replace('~MODIFYUSER~', ff($strLogin_a), $strSQL);
					$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
					dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			
					exposeEntityData($objConn_a, 'SYSTEMFORM', 'TRANSACTION', $strTransactionID, $strJSONData);
				}
			}    
		}
		
		if ($blnTransactionPending)	
		{
			// move transaction to pending table
			// get the entity ids
			$strEntityID = getEntityID($objConn_a, "systemform");
			$strDataEntityID = getEntityID($objConn_a, "transactionpending");
			
			$strSQL = "
insert into ~TABLENAMETRANSACTIONPENDING~ (
client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, 
document_id,
progress,
applicationdate,
g31f68ccc_e168_40b8_a7db_693353aa7aea_paymentdate, 
g31f68ccc_e168_40b8_a7db_693353aa7aea_receiptnumber, 
paymentmethod_id, 
g31f68ccc_e168_40b8_a7db_693353aa7aea_paymentmethod,
paymentstatus_id, 
g31f68ccc_e168_40b8_a7db_693353aa7aea_paymentstatus,
g31f68ccc_e168_40b8_a7db_693353aa7aea_is_paid, 
g31f68ccc_e168_40b8_a7db_693353aa7aea_priceexgst,
g31f68ccc_e168_40b8_a7db_693353aa7aea_priceincgst,
g31f68ccc_e168_40b8_a7db_693353aa7aea_statuscode, 
g31f68ccc_e168_40b8_a7db_693353aa7aea_statusdescription) 
select 
client_id, ~ENTITYID~, ~DATAENTITYID~, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, 
document_id,
progress,
applicationdate,
g31f68ccc_e168_40b8_a7db_693353aa7aea_paymentdate, 
g31f68ccc_e168_40b8_a7db_693353aa7aea_receiptnumber, 
paymentmethod_id, 
g31f68ccc_e168_40b8_a7db_693353aa7aea_paymentmethod,
paymentstatus_id, 
g31f68ccc_e168_40b8_a7db_693353aa7aea_paymentstatus,
g31f68ccc_e168_40b8_a7db_693353aa7aea_is_paid, 
g31f68ccc_e168_40b8_a7db_693353aa7aea_priceexgst,
g31f68ccc_e168_40b8_a7db_693353aa7aea_priceincgst,
g31f68ccc_e168_40b8_a7db_693353aa7aea_statuscode, 
g31f68ccc_e168_40b8_a7db_693353aa7aea_statusdescription
from ~TABLENAMETRANSACTION~ where id = ~TRANSACTIONID~
";
			$strSQL = str_replace('~TABLENAMETRANSACTIONPENDING~', ff($strTableNameTransactionPending), $strSQL);
			$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
			$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
			$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
			$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
			$strSQL = str_replace('~STATUSCODE~', ff($strStatusCode), $strSQL);
			$strSQL = str_replace('~STATUSDESCRIPTION~', ff($strStatusDescription), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			$strTransactionPendingID = dbLastInsertID($objConn_a);
			
			// get the entity ids
			$strEntityID = getEntityID($objConn_a, "systemform");
			$strDataEntityID = getEntityID($objConn_a, "transactionlinepending");
			
			$strSQL = "
insert into ~TABLENAMETRANSACTIONLINEPENDING~ (
client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, 
is_processed,
transactionpending_id, 
applicantproduct_id, 
applicant_id, 
product_id, 
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_product,
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicanttype, 
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicant,
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_description, 
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceexgst, 
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceincgst) 
select 
client_id, ~ENTITYID~, ~DATAENTITYID~, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, 
'N',
~TRANSACTIONPENDINGID~, 
applicantproduct_id, 
applicant_id, 
product_id, 
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_product,
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicanttype, 
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicant,
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_description, 
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceexgst, 
gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_priceincgst
from ~TABLENAMETRANSACTIONLINE~ where transaction_id = ~TRANSACTIONID~
";
			$strSQL = str_replace('~TABLENAMETRANSACTIONLINEPENDING~', ff($strTableNameTransactionLinePending), $strSQL);
			$strSQL = str_replace('~TABLENAMETRANSACTIONLINE~', ff($strTableNameTransactionLine), $strSQL);
			$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
			$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
			$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
			$strSQL = str_replace('~TRANSACTIONPENDINGID~', ff($strTransactionPendingID), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			
			$strSQL = "delete from ~TABLENAMETRANSACTIONLINE~ where transaction_id = ~TRANSACTIONID~";
			$strSQL = str_replace('~TABLENAMETRANSACTIONLINE~', ff($strTableNameTransactionLine), $strSQL);
			$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			
			$strSQL = "delete from ~TABLENAMETRANSACTION~ where id = ~TRANSACTIONID~";
			$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
			$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		}
			
		dbEndTrans($objConn_a, __FUNCTION__);
	}
	    
    $arrResult = [
        'error' => $strResult,
        'newtransaction_id' => $strTransactionPendingID
    ];
    
	return $arrResult;
}