<?php

// notes on the cart system in place.
// currently it supports buying 1 product at a time only. instead of a user being able to add multiple products to the cart, 
// it assumes if there is no products it is a new product and if there is some products that are pending then we want to update the existing.
// currently the security client product is added in a pending state and it is used as the basis of what the customer wants to buy instead
// of what actually they want to buy. it just happends to work with the 1 product scenario.

function actionCartAdd($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) 
{ 
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNamePaymentStatus = getTableNameEntity("paymentstatus", false);
	$strTableNameProduct = getTableNameEntity("product", false);
	$strTableNameTransaction = getTableNameEntity("transaction", false);
	$strTableNameTransactionLine = getTableNameEntity("transactionline", false);
    
    $strResult = "";
    
	// permissions
	if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}
	
	// parrameters
	$strProductIDList = getParameterIDList($arrParameters_a, true);
	$strApplicantName = getJSONParameter($arrParameters_a, 'applicantname');
	
	// initialisations
	$strClientID = $_SESSION['server_loggedin_clientid'];
	$strUserID = $_SESSION['server_loggedin_userid'];
	$strLogin = $_SESSION['server_loggedin_user'];
	$strSystemOwnerClientID = getSystemOwnerClientID($objConn_a);

	$strApplicantID = $strClientID;
	$strApplicantType = "CLIENT";
	
	dbBeginTrans($objConn_a, __FUNCTION__);

	if (strlen($strProductIDList) > 0) 
	{		
		$arrProductIDList = explode(",", $strProductIDList);
		for ($intI = 0; $intI < count($arrProductIDList); $intI++)
		{
			$strProductID = $arrProductIDList[$intI];
			$strProductDescription = dbGetDescriptionFromID($objConn_a, $strTableNameProduct, $strProductID, __FUNCTION__);
			
			$strDescription = $strProductDescription . " for " . $strApplicantName;	// append the applicant name
			$strDescription = trim($strDescription);
				
			$strPriceIncGST = "0";
			$strPriceExGST = "0";

			$strSQL = "select gffe35a8ad_d290_4ae3_8800_f2935dd30d07_priceincgst returnvalue from ~TABLENAMEPRODUCT~ where id = ~PRODUCTID~";
			$strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);
			$strSQL = str_replace('~PRODUCTID~', ff($strProductID), $strSQL);
			$strPriceIncGST = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
				
			$strSQL = "select gffe35a8ad_d290_4ae3_8800_f2935dd30d07_priceexgst returnvalue from ~TABLENAMEPRODUCT~ where id = ~PRODUCTID~";
			$strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);
			$strSQL = str_replace('~PRODUCTID~', ff($strProductID), $strSQL);
			$strPriceExGST = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
				
			$strSQL = "select id returnvalue from ~TABLENAMETRANSACTION~ where client_id = ~CLIENTID~";
			$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
			//$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
			$strTransactionID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			
			$strSQL = "select id returnvalue from ~TABLENAMEPAYMENTSTATUS~ where code = 'PENDING'";
			$strSQL = str_replace('~TABLENAMEPAYMENTSTATUS~', ff($strTableNamePaymentStatus), $strSQL);
			$strPaymentStatusID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

			if (strlen($strTransactionID) == 0) 
			{
				// initialise the payer
				$strAccountID = $_SESSION['server_loggedin_accountid'];
				
				$strPayerName = "";
				$strPayerAddressLine1 = "";
				$strPayerAddressLine2 = "";
				$strPayerSuburb = "";
				$strPayerState = "";
				$strPayerPostcode = "";
				
				if ($strSystemOwnerClientID != $strClientID)
				{
					$strSQL = "select
						ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_fullname fullname,
						ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_addressline1 addressline1,
						ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_addressline2 addressline2,
						ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_suburb suburb,
						ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_state state,
						ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_postcode postcode
						from ~TABLENAMEACCOUNT~ where id = ~ACCOUNTID~";
					$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
					$strSQL = str_replace('~ACCOUNTID~', ff($strAccountID), $strSQL);
					$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
					if ($arrRow = dbReadRecord($objResult))
					{
						$strPayerName = $arrRow['fullname'];
						$strPayerAddressLine1 = $arrRow['addressline1'];
						$strPayerAddressLine2 = $arrRow['addressline2'];
						$strPayerSuburb = $arrRow['suburb'];
						$strPayerState = $arrRow['state'];
						$strPayerPostcode = $arrRow['postcode'];
					}
					dbCloseRecordset($objResult);
				}
				
				// get the entity ids
				$strEntityID = getEntityID($objConn_a, "systemform");
				$strDataEntityID = getEntityID($objConn_a, "transaction");
				$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "TRANSACTION");
				
				$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYMENTSTATUS", $strPaymentStatusID, 'PENDING');
				$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "IS_PAID", 'N');
				
				$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYERNAME", $strPayerName);
				$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYERADDRESSLINE1", $strPayerAddressLine1);
				$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYERADDRESSLINE2", $strPayerAddressLine2);
				$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYERSUBURB", $strPayerSuburb);
				$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYERSTATE", $strPayerState);
				$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYERPOSTCODE", $strPayerPostcode);
				$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "PAYERNOTES", "");
				
				$strJSONData = json_encode($arrJSONData);
							
				// create a new transaction
				$strSQL = "
insert into ~TABLENAMETRANSACTION~ (
client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime, applicationdate,
document_id, 
progress
) values (
~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '', '', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~', '~APPLICATIONDATE~',
null, 
'')
";
				$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
				$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
				//$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
				$strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
				$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
				$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
				$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
				$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
				$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
				$strSQL = str_replace('~APPLICATIONDATE~', getDateTime(), $strSQL);
				dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				$strTransactionID = dbLastInsertID($objConn_a);

				exposeEntityData($objConn_a, 'SYSTEMFORM', 'TRANSACTION', $strTransactionID, $strJSONData);
			}
			
			// get the entity ids
			$strEntityID = getEntityID($objConn_a, "systemform");
			$strDataEntityID = getEntityID($objConn_a, "transactionline");
			$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "TRANSACTIONLINE");
			
			$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'gd6f57b36-a2bb-47b9-a6ed-7df0335fc0c2', "PRODUCT", $strProductID, $strProductDescription);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gd6f57b36-a2bb-47b9-a6ed-7df0335fc0c2', "DESCRIPTION", $strDescription);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gd6f57b36-a2bb-47b9-a6ed-7df0335fc0c2', "APPLICANT", $strApplicantName);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gd6f57b36-a2bb-47b9-a6ed-7df0335fc0c2', "APPLICANTTYPE", $strApplicantType);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gd6f57b36-a2bb-47b9-a6ed-7df0335fc0c2', "PRICEINCGST", $strPriceIncGST);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'gd6f57b36-a2bb-47b9-a6ed-7df0335fc0c2', "PRICEEXGST", $strPriceExGST);
			
			
			$strJSONData = json_encode($arrJSONData);
						
			// add the product to the transaction
			$strSQL = "
insert into ~TABLENAMETRANSACTIONLINE~ (
client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, modifyuser, modifydatetime,
transaction_id, 
applicantproduct_id, 
applicant_id, 
product_id,
is_processed
) values (
~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '', '', '~ISENABLED~', ~CLIENTID~, '~JSONDATA~', '~MODIFYUSER~', '~MODIFYDATETIME~',
~TRANSACTIONID~, 
NULL, 
~APPLICANTID~, 
~PRODUCTID~,
'N')
";
			$strSQL = str_replace('~TABLENAMETRANSACTIONLINE~', ff($strTableNameTransactionLine), $strSQL);
			$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
			//$strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
			$strSQL = str_replace('~ISENABLED~', 'Y', $strSQL);
			$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
			$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
			$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
			$strSQL = str_replace('~PRODUCTID~', ff($strProductID), $strSQL);
			$strSQL = str_replace('~APPLICANTID~', ff($strApplicantID), $strSQL);
			$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
			$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
			$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
			
			$strTransactionLineID = dbLastInsertID($objConn_a);
			
			exposeEntityData($objConn_a, 'SYSTEMFORM', 'TRANSACTIONLINE', $strTransactionLineID, $strJSONData);
			
			
			// clear document_id and receipt number
			$strSQL = "select jsondata returnvalue from ~TABLENAMETRANSACTION~ where id = ~TRANSACTIONID~";
			$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
			$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
			$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			
			$arrJSONData = json_decode($strJSONData, true);
			
			$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, 'g31f68ccc-e168-40b8-a7db-693353aa7aea', "DOCUMENT", '', '');
			
			$strJSONData = json_encode($arrJSONData);
						
			$strSQL = "update ~TABLENAMETRANSACTION~ set jsondata = '~JSONDATA~', document_id = null where id= ~TRANSACTIONID~";
			$strSQL = str_replace('~TABLENAMETRANSACTION~', ff($strTableNameTransaction), $strSQL);
			$strSQL = str_replace('~TRANSACTIONID~', ff($strTransactionID), $strSQL);
			$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
			dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

			exposeEntityData($objConn_a, 'SYSTEMFORM', 'TRANSACTION', $strTransactionID, $strJSONData);
		}
	}
	
	if (dbEndTrans($objConn_a, __FUNCTION__)) 
	{      
		$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', secureValue("TRANSACTIONLINE", $strTransactionLineID));
	} 
	else 
	{
		$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Error adding product to cart.', array());
	}
        
    return $strResult;
}
