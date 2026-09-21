<?php

define('PAYMENT_TIMEOUT', '5000');

function pluginPayment_NabTransactTest($strFullName_a, $strEmail_a, $strAddress_a, $strPostcode_a, $strInvoiceDescription_a, $strInvoiceRef_a, $strCCName_a, $strCCNumber_a, $strCCMonth_a, $strCCYear_a, $strCCSecurityCode_a, $fltAmount_a)
{
	$strResult = '';

	$fltAmount = 12.08;	// uncomment for testing
	$strInvoiceRef_a = "TESTING";	// uncomment for testing
	//$fltAmount = $fltAmount_a;	// uncomment for production

	$blnResult = false;

	$strCardholderName = $strFullName_a;
	$strExpiryDate = $strCCMonth_a . '/' . strRight($strCCYear_a, 2);

	$strTimestamp = date("YmdHis") . "000000";
	$strMessageID = session_id() . '-' . $strTimestamp; // strMessageID = Session.SessionID & "-" & strTimestamp

	$strMerchantID = NABTRANSACT_TEST_MERCHANTID;
	$strMerchantPassword = NABTRANSACT_TEST_MERCHANTPWD;
	$strURL = NABTRANSACT_TEST_URL;

	$xmlRequest = '<?xml version="1.0" encoding="UTF-8"?>';
	$xmlRequest .= '<NABTransactMessage>';
		$xmlRequest .= '<MessageInfo>';
			$xmlRequest .= '<messageID>' . xmlEnc($strMessageID) . '</messageID>';
			$xmlRequest .= '<messageTimestamp>' . xmlEnc($strTimestamp) . '</messageTimestamp>';
			$xmlRequest .= '<timeoutValue>' . PAYMENT_TIMEOUT . '</timeoutValue>';
			$xmlRequest .= '<apiVersion>xml-4.2</apiVersion>';
		$xmlRequest .= '</MessageInfo>';
		$xmlRequest .= '<MerchantInfo>';
			$xmlRequest .= '<merchantID>' . xmlEnc($strMerchantID) . '</merchantID>';
			$xmlRequest .= '<password>' . xmlEnc($strMerchantPassword) . '</password>';
		$xmlRequest .= '</MerchantInfo>';
		$xmlRequest .= '<RequestType>Payment</RequestType>';
		$xmlRequest .= '<Payment>';
			$xmlRequest .= '<TxnList count="1">';
				$xmlRequest .= '<Txn ID="1">';	// only 1 transaction per submit for now
					$xmlRequest .= '<txnType>0</txnType>';	// 0 means this is a payment
					$xmlRequest .= '<txnSource>23</txnSource>';	// 23 means this is an XML message
					$xmlRequest .= '<txnChannel>0</txnChannel>';	// 0 means this message came via the internet
					$xmlRequest .= '<amount>' . xmlEnc($fltAmount * 100) . '</amount>';
					$xmlRequest .= '<currency>AUD</currency>'; // NAB only supports AUD at present
					$xmlRequest .= '<purchaseOrderNo>' . xmlEnc($strInvoiceRef_a) . '</purchaseOrderNo>';
					$xmlRequest .= '<CreditCardInfo>';
						$xmlRequest .= '<cardNumber>' . xmlEnc($strCCNumber_a) . '</cardNumber>';
						$xmlRequest .= '<expiryDate>' . xmlEnc($strExpiryDate) . '</expiryDate>';
						//$xmlRequest .= '<cvv>' . xmlEnc($strCCSecurityCode_a) . '</cvv>';
						$xmlRequest .= '<cardHolderName>' . xmlEnc($strCardholderName) . '</cardHolderName>';
					$xmlRequest .= '</CreditCardInfo>';
					$xmlRequest .= '<BuyerInfo>';
						$xmlRequest .= '<emailAddress>' . xmlEnc($strEmail_a) . '</emailAddress>';
					$xmlRequest .= '</BuyerInfo>';
				$xmlRequest .= '</Txn>';
			$xmlRequest .= '</TxnList>';
		$xmlRequest .= '</Payment>';
	$xmlRequest .= '</NABTransactMessage>';

//echo("Request: <br>" . $xmlRequest);

	// uncomment for debugging
	//

//echo $url;

	// submit the payment
	$objCurl = curl_init();
	curl_setopt($objCurl, CURLOPT_URL, $strURL);
	curl_setopt($objCurl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($objCurl, CURLOPT_POST, 1);
	curl_setopt($objCurl, CURLOPT_POSTFIELDS, $xmlRequest);
	curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1);
	$xmlResponse = curl_exec ($objCurl);
	curl_close ($objCurl);

//print_r($xmlResponse);
//die();

	// uncomment if you want to write the XML response during debugging
	//$myFile = "response.xml";
	//$fh = fopen($myFile, 'w') or die("can't open file");
	//fwrite($fh, $xmlResponse);
	//fclose($fh);

	//echo("<hr>Response: <br>" . $xmlResponse); 	// uncomment for debugging

	// parse the XML response
	$objXML = new SimpleXMLElement($xmlResponse);
	$objStatus = $objXML->Status;
	$strStatusCode = (string)$objStatus->statusCode;
	$strStatusDescription = (string)$objStatus->statusDescription;

	if (($strStatusCode == "000") && (strtoupper($strStatusDescription) == "NORMAL"))
	{
		// our request was processed successfully, so now check that the payment was successful
		$objTransaction = $objXML->Payment->TxnList->Txn;
		$strResponseCode = (string)$objTransaction->responseCode;
		$strResponseText = (string)$objTransaction->responseText;

		if (($strResponseCode == "00") || ($strResponseCode == "08") || ($strResponseCode == "11") || ($strResponseCode == "16"))
		{
			// approved response
			$blnResult = true;
//debug($strResponseText);
			//$strResult = $strResponseText;
		}
		else
		{
			// declined response
			$strResult = $strResponseText;
		}
	}
	else
	{
		$strResult = $strStatusDescription;
	}

    logDebugPayment( __METHOD__, $strFullName_a, $strEmail_a, $strAddress_a, $strInvoiceDescription_a, $strInvoiceRef_a, $fltAmount_a);
    
    return $strResult;
}
