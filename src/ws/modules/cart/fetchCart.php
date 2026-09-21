<?php

function fetchCart($objConn_a, $strClientID_a, $blnSecure_a)
{
	$strTableNameTransactionHistory = getTableNameEntity("transactionhistory", false);
	$strTableNameTransactionLineHistory = getTableNameEntity("transactionlinehistory", false);
	$strTableNameTransactionLinePending = getTableNameEntity("transactionlinepending", false);
	$strTableNameTransactionPending = getTableNameEntity("transactionpending", false);

    $arrResult = array();

	if (dependencies('cart/calculateNegotiatedPrice') && 
		dependencies('entity/dataaccess/paymentstatus') &&
		dependencies('entity/dataaccess/product') &&
		dependencies('entity/dataaccess/transaction') &&
		dependencies('entity/dataaccess/transactionline'))
	{
		$strPaymentMethodID = '';
		$strPayerName = '';
		$strPayerAddressLine1 = '';
		$strPayerAddressLine2 = '';
		$strPayerSuburb = '';
		$strPayerState = '';
		$strPayerPostcode = '';
		$strPayerNotes = '';
		$strProgress = '';
		$arrTransactionLines = array();
		
		$arrCustomWhere = [["s", "code", "PAID"]];
		$strPaidID = fetchValue_paymentstatus($objConn_a, "id", "", $arrCustomWhere);

		$arrCustomWhere = [["n", "client_id", $strClientID_a]];
		$strTransactionID = fetchValue_transaction($objConn_a, "id", "", $arrCustomWhere);

		if (strlen($strTransactionID) > 0)
		{
			$objResult = fetch_transaction($objConn_a, "id, jsondata, paymentmethod_id, progress", $strTransactionID, []);
			if ($arrRow = dbReadRecord($objResult))
			{
				$strPaymentMethodID = $arrRow['paymentmethod_id'];
				$strProgress = $arrRow['progress'];
				$strJSONData = $arrRow['jsondata'];
				$arrJSONData = json_decode($strJSONData, true);

				$strPayerName = formValueGetBySectionCodeFieldCode($arrJSONData, "g31f68ccc-e168-40b8-a7db-693353aa7aea", "PAYERNAME");
				$strPayerAddressLine1 = formValueGetBySectionCodeFieldCode($arrJSONData, "g31f68ccc-e168-40b8-a7db-693353aa7aea", "PAYERADDRESSLINE1");
				$strPayerAddressLine2 = formValueGetBySectionCodeFieldCode($arrJSONData, "g31f68ccc-e168-40b8-a7db-693353aa7aea", "PAYERADDRESSLINE2");
				$strPayerSuburb = formValueGetBySectionCodeFieldCode($arrJSONData, "g31f68ccc-e168-40b8-a7db-693353aa7aea", "PAYERSUBURB");
				$strPayerState = formValueGetBySectionCodeFieldCode($arrJSONData, "g31f68ccc-e168-40b8-a7db-693353aa7aea", "PAYERSTATE");
				$strPayerPostcode = formValueGetBySectionCodeFieldCode($arrJSONData, "g31f68ccc-e168-40b8-a7db-693353aa7aea", "PAYERPOSTCODE");
				$strPayerNotes = formValueGetBySectionCodeFieldCode($arrJSONData, "g31f68ccc-e168-40b8-a7db-693353aa7aea", "PAYERNOTES");
			}
			dbCloseRecordset($objResult);

			$arrCustomWhere = [["n", "transaction_id", $strTransactionID]];
			$objResult = fetch_transactionline($objConn_a, "id, jsondata, product_id, applicant_id", "", $arrCustomWhere);
			while ($arrRow = dbReadRecord($objResult))
			{
				$strTransactionLineID = $arrRow['id'];
				$strProductID = $arrRow['product_id'];
				$strApplicantID = $arrRow['applicant_id'];
				$strJSONData = $arrRow['jsondata'];
				$arrJSONData = json_decode($strJSONData, true);
				
				$strApplicantType = formDescriptionGetBySectionCodeFieldCode($arrJSONData, "gd6f57b36-a2bb-47b9-a6ed-7df0335fc0c2", "APPLICANTTYPE");
				$strDescription = formValueGetBySectionCodeFieldCode($arrJSONData, "gd6f57b36-a2bb-47b9-a6ed-7df0335fc0c2", "DESCRIPTION");
				$strPriceIncGST = formValueGetBySectionCodeFieldCode($arrJSONData, "gd6f57b36-a2bb-47b9-a6ed-7df0335fc0c2", "PRICEINCGST");
				$strPriceExGST = formValueGetBySectionCodeFieldCode($arrJSONData, "gd6f57b36-a2bb-47b9-a6ed-7df0335fc0c2", "PRICEEXGST");

				// work out if within grace period or not
				$strInGracePeriod = 'N';
				$strGracePeriod = fetchValue_product($objConn_a, "gffe35a8ad_d290_4ae3_8800_f2935dd30d07_graceperiod", $strProductID, []);

				// get the most recent datetime for a purchased product from both pending and history tables
				// (when it was applied)
				if (strlen($strGracePeriod) > 0)
				{
					$strSQL = "";
					if ($strApplicantType == 'CLIENT')
					{
						$strSQL = "select max(applicationdate) returnvalue from (
										select t.applicationdate 
										from ~TABLENAMETRANSACTIONHISTORY~ t, ~TABLENAMETRANSACTIONLINEHISTORY~ tl
										where 
										t.client_id = ~CLIENTID~ and 
										tl.transactionhistory_id = t.id and 
										tl.gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicanttype = 'CLIENT' and 
										tl.applicant_id = ~APPLICANTID~ and 
										tl.product_id = ~PRODUCTID~
										union
										select t.applicationdate 
										from ~TABLENAMETRANSACTIONPENDING~ t, ~TABLENAMETRANSACTIONLINEPENDING~ tl
										where 
										t.client_id = ~CLIENTID~ and 
										tl.transactionpending_id = t.id and 
										tl.gd6f57b36_a2bb_47b9_a6ed_7df0335fc0c2_applicanttype = 'CLIENT' and 
										tl.applicant_id = ~APPLICANTID~ and 
										tl.product_id = ~PRODUCTID~ and 
										t.paymentstatus_id = ~PAIDID~
									) temp";
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
						// first it removes the time periods to get the count
						$strCount = $strGracePeriod;
						$strCount = str_replace("d", "", $strCount);
						$strCount = str_replace("m", "", $strCount);
						$strCount = str_replace("y", "", $strCount);
						
						// then it removes the count to get the unit
						$strUnit = str_replace($strCount, "", $strGracePeriod);

						// then looks up the array for the time period
						$arrTimePeriod = array('d' => 'day', 'm' => 'month', 'y' => 'year');
						$strTimePeriod = $arrTimePeriod[$strUnit];

						$strGracePeriodTimeStamp = strtotime($strApplicationDate . " + $strCount $strTimePeriod");

						if ($strGracePeriodTimeStamp > time())
						{
							$strInGracePeriod = 'Y';
						}
					}
				}

				$arrNegotiatedPrice = calculateNegotiatedPrice($objConn_a, $strProductID, $strClientID_a, $strPriceIncGST);

				if ($blnSecure_a)
				{
					$strTransactionLineID = secureEntityValue('TRANSACTIONLINE', $strTransactionLineID);
					$strProductID = secureEntityValue('PRODUCT', $strProductID);
				}
				
				$arrTransactionLines[] = array(
					'id' => $strTransactionLineID,
					'productid' => $strProductID,
					'description' => $strDescription,
					'priceincgst' => $arrNegotiatedPrice['priceincgst'],
					'priceexgst' => $arrNegotiatedPrice['priceexgst'],
					'ingraceperiod' => $strInGracePeriod
				);
			}
			dbCloseRecordset($objResult);
			
			if ($blnSecure_a)
			{
				$strPaymentMethodID = secureEntityValue('PAYMENTMETHOD', $strPaymentMethodID);
			}
		}

		$arrResult[] = array(
			'paymentmethodid' => $strPaymentMethodID,
			'payername' => $strPayerName,
			'payeraddressline1' => $strPayerAddressLine1,
			'payeraddressline2' => $strPayerAddressLine2,
			'payersuburb' => $strPayerSuburb,
			'payerstate' => $strPayerState,
			'payerpostcode' => $strPayerPostcode,
			'payernotes' => $strPayerNotes,
			'progress' => $strProgress,
			'transactionlines' => $arrTransactionLines
		);	
	}

    return $arrResult;
}
