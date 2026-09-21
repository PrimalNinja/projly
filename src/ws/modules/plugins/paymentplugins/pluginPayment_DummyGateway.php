<?php

function pluginPayment_DummyGateway($strFullName_a, $strEmail_a, $strAddress_a, $strPostcode_a, $strInvoiceDescription_a, $strInvoiceRef_a, $strCCName_a, $strCCNumber_a, $strCCMonth_a, $strCCYear_a, $strCCSecurityCode_a, $fltAmount_a)
{
	$strResult = '';
	
	if (strlen($strInvoiceRef_a) == 0)
	{
		$strResult = 'A payment reference is required.';
	}

    logDebugPayment( __METHOD__, $strFullName_a, $strEmail_a, $strAddress_a, $strInvoiceDescription_a, $strInvoiceRef_a, $fltAmount_a);
    
    return $strResult;
}

