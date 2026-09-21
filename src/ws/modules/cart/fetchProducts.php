<?php

// fetch purchasable products
function fetchProducts($objConn_a, $strClientID_a, $strFilter_a, $blnSecure_a)
{
	$strTableNameTransactionHistory = getTableNameEntity("transactionhistory", false);
	$strTableNameTransactionLineHistory = getTableNameEntity("transactionlinehistory", false);
	$strTableNameTransactionLinePending = getTableNameEntity("transactionlinepending", false);
	$strTableNameTransactionPending = getTableNameEntity("transactionpending", false);
	
    $arrResult = array();

	if (dependencies('cart/calculateNegotiatedPrice') && 
		dependencies('entity/dataaccess/clientproduct') &&
		dependencies('entity/dataaccess/paymentstatus') &&
		dependencies('entity/dataaccess/product') &&
		dependencies('entity/dataaccess/transaction') &&
		dependencies('entity/dataaccess/transactionline'))
	{
		$strApplicantType = "CLIENT";

		$arrCustomWhere = [["s", "code", "COMPLIMENTARY"]];
		$strComplimentaryID = fetchValue_paymentstatus($objConn_a, "id", "", $arrCustomWhere);

		$arrCustomWhere = [["s", "code", "FREE"]];
		$strFreeID = fetchValue_paymentstatus($objConn_a, "id", "", $arrCustomWhere);

		$arrCustomWhere = [["s", "code", "PAID"]];
		$strPaidID = fetchValue_paymentstatus($objConn_a, "id", "", $arrCustomWhere);

		// get products already in cart so they can be hidden
		$arrCustomWhere = [["n", "client_id", $strClientID_a]];
		$strTransactionID = fetchValue_transaction($objConn_a, "id", "", $arrCustomWhere);

		$strProductIDsAlreadyInCart = '';
		if (strlen($strTransactionID) > 0)
		{
			$arrCustomWhere = [["n", "transaction_id", $strTransactionID]];
			$objResult = fetch_transactionline($objConn_a, "product_id", "", $arrCustomWhere);
			while ($arrRowCart = dbReadRecord($objResult))
			{
				$strProductID = $arrRowCart['product_id'];
				
				if (strlen($strProductIDsAlreadyInCart) > 0)
				{
					$strProductIDsAlreadyInCart .= ',';
				}
				
				$strProductIDsAlreadyInCart .= $strProductID;
			}
			dbCloseRecordset($objResult);
		}

		// get active products already purchased so that we can ensure product dependencies are met
		// and also exclude them from the selection
		// exclude pending products
		$strProductIDsAlreadyPurchased = '';
		$strProductCodesAlreadyPurchased = "";

		// COMPLIMENTARY
		if (strlen($strComplimentaryID) > 0)
		{
			$arrCustomWhere = [["n", "client_id", $strClientID_a],
							   ["n", "paymentstatus_id", $strComplimentaryID],
							   ["s", "is_enabled", "Y"]];
			$objResult = fetch_clientproduct($objConn_a, "product_id", "", $arrCustomWhere);
			while ($arrRow = dbReadRecord($objResult))
			{
				$strProductID = $arrRow['product_id'];
				$strProductCode = fetchValue_product($objConn_a, "code", $strProductID, []);

				// product IDs to later exclude
				if (strlen($strProductIDsAlreadyPurchased) > 0)
				{
					$strProductIDsAlreadyPurchased .= ',';
				}
				
				$strProductIDsAlreadyPurchased .= $strProductID;

				// product codes to ensure requirements are met
				if (strlen($strProductCode) > 0)
				{
					if (strlen($strProductCodesAlreadyPurchased) > 0)
					{
						$strProductCodesAlreadyPurchased .= " or ";
					}

					$strProductCodesAlreadyPurchased .= "requirements like '%~~PRODUCTCODE~~%'";   
					$strProductCodesAlreadyPurchased = str_replace('~PRODUCTCODE~', ffn($strProductCode), $strProductCodesAlreadyPurchased);
				}
			}
			dbCloseRecordset($objResult);
		}

		// FREE
		if (strlen($strFreeID) > 0)
		{
			$arrCustomWhere = [["n", "client_id", $strClientID_a],
							   ["n", "paymentstatus_id", $strFreeID],
							   ["s", "is_enabled", "Y"]];
			$objResult = fetch_clientproduct($objConn_a, "product_id", "", $arrCustomWhere);
			while ($arrRow = dbReadRecord($objResult))
			{
				$strProductID = $arrRow['product_id'];
				$strProductCode = fetchValue_product($objConn_a, "code", $strProductID, []);

				// product IDs to later exclude
				if (strlen($strProductIDsAlreadyPurchased) > 0)
				{
					$strProductIDsAlreadyPurchased .= ',';
				}
				
				$strProductIDsAlreadyPurchased .= $strProductID;

				// product codes to ensure requirements are met
				if (strlen($strProductCode) > 0)
				{
					if (strlen($strProductCodesAlreadyPurchased) > 0)
					{
						$strProductCodesAlreadyPurchased .= " or ";
					}

					$strProductCodesAlreadyPurchased .= "requirements like '%~~PRODUCTCODE~~%'";   
					$strProductCodesAlreadyPurchased = str_replace('~PRODUCTCODE~', ffn($strProductCode), $strProductCodesAlreadyPurchased);
				}
			}
			dbCloseRecordset($objResult);
		}

		// PAID
		if (strlen($strPaidID) > 0)
		{
			$arrCustomWhere = [["n", "client_id", $strClientID_a],
							   ["n", "paymentstatus_id", $strPaidID],
							   ["s", "is_enabled", "Y"]];
			$objResult = fetch_clientproduct($objConn_a, "product_id", "", $arrCustomWhere);
			while ($arrRow = dbReadRecord($objResult))
			{
				$strProductID = $arrRow['product_id'];
				$strProductCode = fetchValue_product($objConn_a, "code", $strProductID, []);

				// product IDs to later exclude
				if (strlen($strProductIDsAlreadyPurchased) > 0)
				{
					$strProductIDsAlreadyPurchased .= ',';
				}
				
				$strProductIDsAlreadyPurchased .= $strProductID;

				// product codes to ensure requirements are met
				if (strlen($strProductCode) > 0)
				{
					if (strlen($strProductCodesAlreadyPurchased) > 0)
					{
						$strProductCodesAlreadyPurchased .= " or ";
					}

					$strProductCodesAlreadyPurchased .= "requirements like '%~~PRODUCTCODE~~%'";   
					$strProductCodesAlreadyPurchased = str_replace('~PRODUCTCODE~', ffn($strProductCode), $strProductCodesAlreadyPurchased);
				}
			}
			dbCloseRecordset($objResult);
		}
		
		// customers must have at least 1 product, even if it is DEFAULT
		if (strlen($strProductCodesAlreadyPurchased) > 0)
		{
			// this query fetches all products not pending and not already got that meet the dependencies
			$strCustomWhere = "is_enabled = 'Y' and 
								(gffe35a8ad_d290_4ae3_8800_f2935dd30d07_behaviourcategory = '~APPLICANTTYPE~') and 
								((gffe35a8ad_d290_4ae3_8800_f2935dd30d07_applications = '') or 
									(gffe35a8ad_d290_4ae3_8800_f2935dd30d07_applications is null) or 
									(gffe35a8ad_d290_4ae3_8800_f2935dd30d07_applications like '%~APPCODE~%'))";

			$strCustomWhere .= " and (" . $strProductCodesAlreadyPurchased . ")";
			
			if (strlen($strFilter_a) > 0)
			{
				$strCustomWhere .= " and (filter like '%~~FILTER~~%')";
			}

			if (strlen($strProductIDsAlreadyInCart) > 0)
			{
				$strCustomWhere .= " and id not in (" . $strProductIDsAlreadyInCart . ")";
			}
			
			if (strlen($strProductIDsAlreadyPurchased) > 0)
			{
				$strCustomWhere .= " and id not in (" . $strProductIDsAlreadyPurchased . ")";
			}
			
			$strCustomWhere = str_replace('~APPCODE~', ff(APP_CODE), $strCustomWhere);
			$strCustomWhere = str_replace('~CLIENTID~', ff($strClientID_a), $strCustomWhere);
			$strCustomWhere = str_replace('~FILTER~', ff($strFilter_a), $strCustomWhere);
			$strCustomWhere = str_replace('~APPLICANTTYPE~', ff($strApplicantType), $strCustomWhere);

			$strOrderBy = "cast(gffe35a8ad_d290_4ae3_8800_f2935dd30d07_displayorder as unsigned)";

			$objResult = fetch_product($objConn_a, "id, code, description, gffe35a8ad_d290_4ae3_8800_f2935dd30d07_priceincgst priceincgst, gffe35a8ad_d290_4ae3_8800_f2935dd30d07_priceexgst priceexgst, gffe35a8ad_d290_4ae3_8800_f2935dd30d07_graceperiod graceperiod", "", $strCustomWhere, $strOrderBy);
			while ($arrRow = dbReadRecord($objResult)) 
			{
				$strProductID = $arrRow['id'];
				$strCode = $arrRow['code'];
				$strDescription = $arrRow['description'];
				$strPriceIncGST = $arrRow['priceincgst'];
				$strPriceExGST = $arrRow['priceexgst'];
				$strGracePeriod = $arrRow['graceperiod'];
				
				// work out if within grace period or not
				$strInGracePeriod = 'N';

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
					$strSQL = str_replace('~APPLICANTID~', ff($strClientID_a), $strSQL);
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
					$strProductID = secureEntityValue('PRODUCT', $strProductID);
				}
				
		//logDebug($strApplicationDate . ', ' . $strGracePeriodTimeStamp . ', ' . time() . ', ' . $strApplicationDate . " + $strNumber $strTimePeriod" . ', ' . $strInGracePeriod, '');        
				$arrResult[] = array(
					"id" => $strProductID,
					"code" => $strCode,
					"description" => $strDescription,
					"price_incgst" => $arrNegotiatedPrice['priceincgst'],
					"price_exgst" => $arrNegotiatedPrice['priceexgst'],
					"ingraceperiod" => $strInGracePeriod
				);     
			}  
			dbCloseRecordset($objResult); 
		}
	}

    return $arrResult;
}
