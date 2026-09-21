<?php

// transaction statuses:
//	COMPLETE = Product Delivered
//	PEN-APP = Pending Processing of Application
//	PEN-PAY = Pending Confirmation of Payment
// 	PEN-PROCESS = Pending Processing (in processing, not yet known if PEN-APP or PEN-PAY)
//	CANCELLED = cancelled
//
// process payments
//
//		submitPayment (make payments, put transaction into tbltransactionpending)
//		commitProducts (create applications where required, completed transactions put into tbltransactionhistory)
//		populateEntityReceipt
//		clearCart (remove all from tbltransaction & tbltransactionline)
//
// logic:
// if paid immediately it stays in the transaction table after submitPayment
// if payment is deferred it is moved to transactionpending within submitPayment
// after that...
// we go to commitProducts
// commitProducts now... the only things remaining will be not already pending payment within the transaction table
// some child records can be pending application and some might be immediate, but before we can add the pending application children, we need to add the pending application parent
// so... we put all parents into the pending application
function actionCartCommit($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNamePaymentMethod = getTableNameEntity("paymentmethod", false);
    $strTableNameTransaction = getTableNameEntity("transaction", false);
    $strTableNameTransactionPending = getTableNameEntity("transactionpending", false);
    
	$strResult = "";
    $strError = "";

	if (dependencies('cart/clearCart,cart/commitProducts,cart/submitPayment,cart/generateReceipt,cart/updateTransactionLinePrices,cart/updateTransactionPrices'))
	{
		// permissions
		if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}
		
		// parameters
		$strPaymentMethodID = revertSecuredValue(getJSONParameter($arrParameters_a, 'paymentmethodid'), 'id', false);
		$arrCard = getJSONParameter($arrParameters_a, 'card');

		// initialisations
		$strClientID = $_SESSION['server_loggedin_clientid'];
		$strUserID = $_SESSION['server_loggedin_userid'];
		$strLogin = $_SESSION['server_loggedin_user'];
        
        updateTransactionLinePrices($objConn_a, $strClientID);
        updateTransactionPrices($objConn_a, $strClientID);
        
		$blnIsImmediate = true;
		$strPaymentDate = getISODate();
		if (strlen($strPaymentMethodID) > 0)
		{
			$strSQL = "select ffb775d2a9_59b7_453b_91ce_7621548ffe81_isimmediate returnvalue from ~TABLENAMEPAYMENTMETHOD~ where id = ~PAYMENTMETHODID~";
			$strSQL = str_replace('~TABLENAMEPAYMENTMETHOD~', ff($strTableNamePaymentMethod), $strSQL);	
			$strSQL = str_replace('~PAYMENTMETHODID~', ff($strPaymentMethodID), $strSQL);	
			$blnIsImmediate = toBoolean(dbReadValue($objConn_a, $strSQL, __FUNCTION__));
		}

		if ($blnIsImmediate)
		{
			$strSQL = "select id returnvalue from ~TABLENAMETRANSACTION~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
			$strTransactionID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			
			//generateReceipt($objConn_a, $strClientID, $strUserID, $strTransactionID, true);
		}
		
		$arrSubmitPaymentResult = submitPayment($objConn_a, $strClientID, $strLogin, $strPaymentDate, $blnIsImmediate, $strPaymentMethodID, $arrCard);	// can be an immediate payment of $0
		        
        $strError = $arrSubmitPaymentResult['error'];
        
		if (strlen($strError) == 0) 
		{
            $strTransactionID = $arrSubmitPaymentResult['newtransaction_id'];
            
			dbBeginTrans($objConn_a, __FUNCTION__);
                        
			if ($blnIsImmediate)
			{
				// clear document_id and receipt number
				
				$strSQL = "select jsondata returnvalue from ~TABLENAMETRANSACTIONPENDING~ where id = ~TRANSACTIONID~";
				$strSQL = str_replace('~TABLENAMETRANSACTIONPENDING~', ff($strTableNameTransactionPending), $strSQL);
				$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
				$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

				$arrJSONData = json_decode($strJSONData, true);

				$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "DOCUMENT", '', '');
				//$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "RECEIPTNUMBER", '');
				
				$strJSONData = json_encode($arrJSONData);

				$strSQL = "update ~TABLENAMETRANSACTIONPENDING~ set jsondata = '~JSONDATA~', document_id = NULL where id= ~TRANSACTIONID~";
				$strSQL = str_replace('~TABLENAMETRANSACTIONPENDING~', ff($strTableNameTransactionPending), $strSQL);
				$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
				$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
				dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

				exposeEntityData($objConn_a, 'SYSTEMFORM', 'TRANSACTIONPENDING', $strTransactionID, $strJSONData);
			}
            
            //generateReceipt($objConn_a, $strClientID, $strUserID, $strTransactionID, false);
            
			if (!commitProducts($objConn_a, $strClientID))
			{
				if ($blnIsImmediate)
				{
					$strError = "Payment has been accepted, however there was an system error. Please contact support for assistance. (REF#P001)";
				}
			}
			                        
            clearCart($objConn_a, $strClientID);
            
			if (!dbEndTrans($objConn_a, __FUNCTION__))
			{
				if ($blnIsImmediate)
				{
					$strError = "Payment has been accepted, however there was an system error. Please contact support for assistance. (REF#P002)";
				}
			}
		}
	}
			
	if (strlen($strError) == 0)
	{
		$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, "", array());
	}
	else 
	{ 
		$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strError, array());
	}

    return $strResult;
}