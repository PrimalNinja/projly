<?php

function generateReceipt($objConn_a, $strClientID_a, $strUserID_a, $strTransactionID_a, $blnInCart_a) 
{
	$strTableNameTransaction = getTableNameEntity("transaction", false);
	$strTransactionField = 'transaction_id';
	if (!$blnInCart_a)
	{
		$strTableNameTransaction = getTableNameEntity("transactionpending", false);
		$strTransactionField = 'transactionpending_id';
	}
	
	$strInCart = 'Y';
	if (!$blnInCart_a)
	{
		$strInCart = 'N';
	}
	
    $arrResult = array();
	$strDocumentID = "";
    
    $strDeviceID = $_SESSION['server_deviceid'];
    
    if (dependencies('docs/documentDownload,batch/batchJobAdd,setting/invoiceCodeAllocate,cart/populateEntityReceipt')) 
	{
        // note: we cannot generate based on clientid and userid as these may not be the same when changing the payment status on the transaction forms
		// $strSQL = "select id returnvalue from ~TABLENAMETRANSACTION~ where client_id = ~CLIENTID~ and user_id = ~USERID~";
		// $strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
		// $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
		// $strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
		// $strTransactionID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
                
		if (strlen($strTransactionID_a) > 0)
		{
			$strSQL = "select g31f68ccc_e168_40b8_a7db_693353aa7aea_receiptnumber returnvalue from ~TABLENAMETRANSACTION~ where id = ~TRANSACTIONID~";
			$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);                                
            $strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID_a), $strSQL);
            $strReceiptNumber = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
            
			if (strlen($strReceiptNumber) === 0) 
			{
				// allocate a receipt / tax invoice number
				$strClientIDSystemOwner = getSystemOwnerClientID($objConn_a);
				$strInvoiceCode = invoiceCodeAllocate($objConn_a, $strClientIDSystemOwner);
				// $strInvoiceCode = "";
				
				dbBeginTrans($objConn_a, __FUNCTION__);
				
				$strSQL = "select jsondata returnvalue from ~TABLENAMETRANSACTION~ where id = ~TRANSACTIONID~";
				$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
				$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID_a), $strSQL);
				$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
				
				$arrJSONData = json_decode($strJSONData, true);
				
                $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "RECEIPTNUMBER", $strInvoiceCode);
				
				$strJSONData = json_encode($arrJSONData);
				
				$strSQL = "update ~TABLENAMETRANSACTION~ set jsondata = '~JSONDATA~' where id = ~TRANSACTIONID~";
				$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
				
				$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID_a), $strSQL);
				$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
				//$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
				dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

				if ($blnInCart_a)
				{
					exposeEntityData($objConn_a, 'SYSTEMFORM', 'TRANSACTION', $strTransactionID_a, $strJSONData);
				}
				else
				{
					exposeEntityData($objConn_a, 'SYSTEMFORM', 'TRANSACTIONPENDING', $strTransactionID_a, $strJSONData);
				}
				
				dbEndTrans($objConn_a, __FUNCTION__);
			}
			
			$strSQL = "select document_id returnvalue from ~TABLENAMETRANSACTION~ where id = ~TRANSACTIONID~";
			$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
			$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID_a), $strSQL);
			$strDocumentID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			
			if (strlen($strDocumentID) == 0) 
			{
				// creating tax invoice document
				$arrJSON = array();
				$arrJSON[] = array(
						"userid" => $strUserID_a,
						"deviceid" => $strDeviceID,
						"printjob" => array(
								'type' => 'receipt',
								'preview' => 'Y',
								'filter' => array(
									array(
										'field' => $strTransactionField, //'cart_id',
										'value' => $strTransactionID_a
									),
                                    array(
										'field' => 'in_cart', 
										'value' => $strInCart
									)
								)
							)
					);

				$strJSON = json_encode($arrJSON);
				$arrResult = batchJobAdd($objConn_a, $strClientID_a, $strUserID_a, '2', '', 'Receipt', 'PROCESS_GENERATEDOCUMENT', $strJSON, true, true);

				if (strlen($arrResult['tag']) > 0) 
				{ 
					$strDocumentID = $arrResult['tag'];
					
					dbBeginTrans($objConn_a, __FUNCTION__);

                    $strSQL = "select g31f68ccc_e168_40b8_a7db_693353aa7aea_receiptnumber receiptnumber, jsondata from ~TABLENAMETRANSACTION~ where id = ~TRANSACTIONID~";
					$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
					$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID_a), $strSQL);
					$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
					
					if ($arrRow = dbReadRecord($objResult)) 
					{
						$strReceiptNumber = $arrRow['receiptnumber'];
						$strJSONData = $arrRow['jsondata'];
						$arrJSONData = json_decode($strJSONData, true);
						
						$arrDocuments = [];
						$arrDocuments[] = ['documentid' => $strDocumentID, 'filename' => $strReceiptNumber];
						$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "DOCUMENT", $arrDocuments);
						
						$strJSONData = json_encode($arrJSONData);
											
						$strSQL = "update ~TABLENAMETRANSACTION~ set document_id = ~DOCUMENTID~, jsondata = '~JSONDATA~' where id = ~TRANSACTIONID~";
						$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
						$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID_a), $strSQL);
						$strSQL = str_replace('~DOCUMENTID~', ff($strDocumentID), $strSQL);
						$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
						dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

						exposeEntityData($objConn_a, 'SYSTEMFORM', 'TRANSACTION', $strTransactionID_a, $strJSONData);
					}
					
					dbCloseRecordset($objResult);
					
					dbEndTrans($objConn_a, __FUNCTION__);
				}
                
                populateEntityReceipt($objConn_a, $strClientID_a, $strTransactionID_a, $blnInCart_a);
			}
		}
    }
    
    $arrResult['document_id'] = $strDocumentID;
    
    return $arrResult;
}
