<?php

define('EWAY_PAYMENT_LIVE_REAL_TIME', 'https://www.eway.com.au/gateway/xmlpayment.asp');
define('EWAY_PAYMENT_LIVE_REAL_TIME_TESTING_MODE', 'https://www.eway.com.au/gateway/xmltest/testpage.asp');
define('EWAY_PAYMENT_LIVE_REAL_TIME_CVN', 'https://www.eway.com.au/gateway_cvn/xmlpayment.asp');
define('EWAY_PAYMENT_LIVE_REAL_TIME_CVN_TESTING_MODE', 'https://www.eway.com.au/gateway_cvn/xmltest/testpage.asp');
define('EWAY_PAYMENT_LIVE_GEO_IP_ANTI_FRAUD', 'https://www.eway.com.au/gateway_beagle/xmlbeagle.asp');
define('EWAY_PAYMENT_LIVE_GEO_IP_ANTI_FRAUD_TESTING_MODE', 'https://www.eway.com.au/gateway_beagle/test/xmlbeagle_test.asp'); //in testing mode process with REAL-TIME
define('EWAY_PAYMENT_HOSTED_REAL_TIME', 'https://www.eway.com.au/gateway/payment.asp');
define('EWAY_PAYMENT_HOSTED_REAL_TIME_TESTING_MODE', 'https://www.eway.com.au/gateway/payment.asp');
define('EWAY_PAYMENT_HOSTED_REAL_TIME_CVN', 'https://www.eway.com.au/gateway_cvn/payment.asp');
define('EWAY_PAYMENT_HOSTED_REAL_TIME_CVN_TESTING_MODE', 'https://www.eway.com.au/gateway_cvn/payment.asp');
define('EWAY_PAYMENT_LIVE_RECURRING', 'https://www.eway.com.au/gateway/rebill/upload.aspx');
define('EWAY_PAYMENT_TEST_RECURRING', 'https://www.eway.com.au/gateway/rebill/test/upload_test.aspx');

function pluginPayment_EwayDirect($strFullName_a, $strEmail_a, $strAddress_a, $strPostcode_a, $strInvoiceDescription_a, $strInvoiceRef_a, $strCCName_a, $strCCNumber_a, $strCCMonth_a, $strCCYear_a, $strCCSecurityCode_a, $fltAmount_a)
{
	$strResult = '';
	
    if (dependencies('3p/Eway/EwayPaymentLive,3p/Eway/RebillPayment,3p/Eway/GatewayConnector,3p/Eway/RebillResponse'))
    {
        $strTrnStatus = '';
        $strAuthCode = '';
        $strError = '';
        $strTrnRef = '';
        $strReturnAmount = '';
        $strTransactionID = '';
        $strOption1 = '';
        $strOption2 = '';
        $strOption3 = '';

        $strFirstName = $strFullName_a;
        $strLastName = '';

        $eway = new EwayPaymentLive(EWAY_DEFAULT_CUSTOMER_ID, EWAY_DEFAULT_PAYMENT_METHOD, EWAY_USELIVE);
        $eway->setTransactionData("CustomerFirstName", $strFirstName);
        $eway->setTransactionData("CustomerLastName", $strLastName);
        $eway->setTransactionData("CustomerEmail", $strEmail_a);
        $eway->setTransactionData("CustomerAddress", $strAddress_a);
        $eway->setTransactionData("CustomerPostcode", $strPostcode_a);
        $eway->setTransactionData("CustomerInvoiceDescription", $strInvoiceDescription_a);
        $eway->setTransactionData("CustomerInvoiceRef", $strInvoiceRef_a);
        $eway->setTransactionData("CardHoldersName", $strCCName_a);
        $eway->setTransactionData("CardNumber", $strCCNumber_a);
        $eway->setTransactionData("CardExpiryMonth", $strCCMonth_a);
        $eway->setTransactionData("CardExpiryYear", $strCCYear_a);
        $eway->setTransactionData("TotalAmount", $fltAmount_a * 100); // needs to be in cents
        $eway->setTransactionData("TrxnNumber", "");
        $eway->setTransactionData("Option1", '');
        $eway->setTransactionData("Option2", '');
        $eway->setTransactionData("Option3", '');

        $eway->setCurlPreferences(CURLOPT_SSL_VERIFYPEER, 0); // Require for Windows hosting

        $objResponse = $eway->doPayment();
		$arrResponse = (array)$objResponse;
        
        if (strtolower($arrResponse["EWAYTRXNSTATUS"]) == "false")
        {
            $strResult = $arrResponse["EWAYTRXNERROR"];
        }
        else if (strtolower($arrResponse["EWAYTRXNSTATUS"]) == "true")
        {
            // $strTrnStatus = $arrResponse["EWAYTRXNSTATUS"];
            // $strAuthCode = $arrResponse["EWAYAUTHCODE"];
            // $strError = $arrResponse["EWAYTRXNERROR"];
            // $strTrnRef = $arrResponse["EWAYTRXNREFERENCE"];
            // $strReturnAmount = $arrResponse["EWAYRETURNAMOUNT"];
            // $strTransactionID = $arrResponse["EWAYTRXNNUMBER"];
            // $strOption1 = $arrResponse["EWAYTRXNOPTION1"];
            // $strOption2 = $arrResponse["EWAYTRXNOPTION2"];
            // $strOption3 = $arrResponse["EWAYTRXNOPTION3"];

			// we have no error
			$strResult = '';
        }
        else
        {
            $strResult =  "An invalid response was received from the payment gateway.";
        }

        logDebugPayment( __METHOD__, $strFullName_a, $strEmail_a, $strAddress_a, $strInvoiceDescription_a, $strInvoiceRef_a, $fltAmount_a);
     }

    return $strResult;
}


