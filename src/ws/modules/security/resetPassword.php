<?php

function resetPassword($objConn_a, $strAccountID_a)
{
    $strTableNameClient = getTableNameEntity("client", false);
    $strTableNameAccount = getTableNameEntity("account", false);
    $strTemplateClientID = getSystemClientID($objConn_a);

    $blnResult = false;

    if (dependencies('security/resetPasswordAdd'))
    {
		$strEditingUserId = "";
		$strAccountName = "";
		$strClientID = "";
		$strClientCode = "";
		$strClientEmailAddress = "";
		$strEmail = "";
		$strBusinessName = "";
		$strBusinessPhone = "";
		$strBusinessEmail = "";
		$strBusinessWebsite = "";

		$strDateToday = getDateStringOut(getDateOnly());
		
        $strSQL = "select data_client_id client_id, user_id user_id, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_accountname accountname, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_businessname businessname, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_clientcode clientcode, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_accountemailaddress emailaddress, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_phonenumber phonenumber from ~TABLENAMEACCOUNT~ where id = ~ID~";
        $strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
        $strSQL = str_replace('~ID~', ff($strAccountID_a), $strSQL);

        $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
        if ($arrRow = dbReadRecord($objResult)) 
		{
			$strEditingUserId = $arrRow['user_id'];
			$strAccountName = $arrRow['accountname'];
			$strClientID = $arrRow['client_id'];
			$strClientCode = $arrRow['clientcode'];
			$strClientEmailAddress = $arrRow['emailaddress'];
			$strEmail = $arrRow['emailaddress'];
			$strBusinessName = $arrRow['businessname'];
			$strBusinessPhone = $arrRow['phonenumber'];
			$strBusinessEmail = $arrRow['emailaddress'];
			$strBusinessWebsite = "";
        }
        dbCloseRecordset($objResult);

        if (strlen($strEmail) > 0)
        {
            dbBeginTrans($objConn_a, __FUNCTION__);

			$strToken = getGUID();
			$strURL = CONFIRMATION_URL;
			$strURL = str_replace('~TOKEN~', $strToken, $strURL);

			resetPasswordAdd($objConn_a, $strClientID, $strClientEmailAddress, $strToken);

			$arrPlaceholders = Array(
				'DATE' => $strDateToday,
				'APPLICATIONNAME' => APPNAME,
				'FULLNAME' => $strAccountName,
				'GIVENNAMES' => $strAccountName,
				'CLIENTCODE' => $strClientCode,
				'EMAILADDRESS' => $strEmail,
				'PASSWORD' => "",
				'BUSINESSNAME' => $strBusinessName,
				'BUSINESSPHONE' => $strBusinessPhone,
				'BUSINESSEMAILADDRESS' => $strBusinessEmail,
				'BUSINESSWEBSITE' => $strBusinessWebsite,
				'RESETPASSWORDLINK' => $strURL
			);

			sendMessageTemplatedMail($objConn_a, GENERAL_EMAIL_DOMAIN, GENERAL_EMAIL_ADDRESS, GENERAL_EMAIL_FROMNAME, $strClientEmailAddress, $strClientID, "", $strTemplateClientID, "PWD_RESET_INIT", $arrPlaceholders, "", "", true);
			$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
        }
		else
		{
			dbRaiseCustomError($objConn_a, "No email is configured for this account.");
		}
    }

    return $blnResult;
}