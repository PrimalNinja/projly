<?php

function sendPurchaseLetter($objConn_a, $strClientID_a, $strUserID_a, $strTransactionID_a)
{
    $strTableNameAccount = getTableNameEntity("account", false);
    
    $blnResult = false;
    
    if (dependencies('cart/purchaseLetterTaxInvoice'))
    {
        $strTemplateClientID = getSystemClientID($objConn_a);
        $strDateToday = getDateStringOut(getDateOnly());

        $strSQL = "select client_id, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_accountname accountname, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_clientcode clientcode, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_accountemailaddress emailaddress from ~TABLENAMEACCOUNT~ where client_id = ~CLIENTID~ and user_id = '~USERID~'";
        $strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
        $strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);

        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

        $strAccountName = "";
        $strClientID = "";
        $strClientCode = "";
        $strClientEmailAddress = "";
        $strEmailAddress = "";
        $strBusinessName = "";
        $strBusinessPhone = "";
        $strBusinessEmail = "";
        $strBusinessWebsite = "";

        if ($arrRow = dbReadRecord($objResult)) 
        {
            $strAccountName = $arrRow['accountname'];
            $strClientID = $arrRow['client_id'];
            $strClientCode = $arrRow['clientcode'];
            $strClientEmailAddress = $arrRow['emailaddress'];
            $strEmailAddress = $arrRow['emailaddress'];
        }

        dbCloseRecordset($objResult);

        if (dependencies('setting/settingGet')) {
            // fetch
            $strBusinessName = settingGet($objConn_a, 'CORE', 'BNAME', $strClientID, '', '', '', __FUNCTION__);
            $strBusinessNumber = settingGet($objConn_a, 'CORE', 'BNUM', $strClientID, '', '', '', __FUNCTION__);
            $strBusinessPhone = settingGet($objConn_a, 'CORE', 'BPHONE', $strClientID, '', '', '', __FUNCTION__);
            $strBusinessAddress = settingGet($objConn_a, 'CORE', 'BADDR', $strClientID, '', '', '', __FUNCTION__);
        }

        $strTaxInvoice = purchaseLetterTaxInvoice($objConn_a, $strClientID_a, $strTransactionID_a);

        $arrPlaceholders = Array(
            'DATE' => $strDateToday,
            'APPLICATIONNAME' => APPNAME,
            'FULLNAME' => $strAccountName,
            'GIVENNAMES' => $strAccountName,
            'CLIENTCODE' => $strClientCode,
            'EMAILADDRESS' => $strEmailAddress,
            'BUSINESSNAME' => $strBusinessName,
            'BUSINESSPHONE' => $strBusinessPhone,
            'BUSINESSEMAILADDRESS' => $strBusinessEmail,
            'BUSINESSWEBSITE' => $strBusinessWebsite,
            'TAXINVOICE' => $strTaxInvoice
        );

        sendMessageTemplatedMail($objConn_a, GENERAL_EMAIL_DOMAIN, GENERAL_EMAIL_ADDRESS, GENERAL_EMAIL_FROMNAME, $strClientEmailAddress, $strClientID, "", $strTemplateClientID, "SKUPURCHASELETTER", $arrPlaceholders, "", "");                    

        $blnResult = true;
    
    }
    
    return $blnResult;
}
