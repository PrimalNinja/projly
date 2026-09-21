<?php

// confirm user password reset
// note: returning a value is considered success
function resetPasswordConfirmation($objConn_a, $blnInteractive_a, $strSecurityToken_a, $strIPAddress_a)
{
    $strResult = "";
	$blnResult = false;

    $strTableNameAccount = getTableNameEntity("account", false);
    $strTableNameResetPassword = getTableNameEntity("resetpassword", false);
    $strTemplateClientID = getSystemClientID($objConn_a);

    if (dependencies('security/resetPasswordUpdate,security/userPasswordUpdate'))
    {
        $strToken = $strSecurityToken_a;

		$strDateToday = getDateStringOut(getDateOnly());
		
        if (strlen($strToken) > 0) 
		{
			$strRandomPassword = "";
			$strEditingUserId = "";
			$strAccountName = "";
			$strClientID = "";
			$strClientCode = "";
			$strClientEmailAddress = "";
			$strEmailAddress = "";
			$strBusinessName = "";
			$strBusinessPhone = "";
			$strBusinessEmail = "";
			$strBusinessWebsite = "";

			$strSQL = "select g14b59919_5bef_40e8_8eb5_a25b870230aa_accountemailaddress returnvalue from ~TABLENAMERESETPASSWORD~ where token = '~TOKEN~' and is_confirmed = 'N' limit 1";
			$strSQL = str_replace('~TABLENAMERESETPASSWORD~', ff($strTableNameResetPassword), $strSQL);
			$strSQL = str_replace('~TOKEN~', ff($strSecurityToken_a), $strSQL);
			$strEmailAddress = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			
			if (strlen($strEmailAddress) > 0)
			{
				$strSQL = "select data_client_id client_id, user_id user_id, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_accountname accountname, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_businessname businessname, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_clientcode clientcode, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_accountemailaddress emailaddress, ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_phonenumber phonenumber from ~TABLENAMEACCOUNT~ where ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_accountemailaddress = '~EMAILADDRESS~'";
				$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
				$strSQL = str_replace('~EMAILADDRESS~', ff($strEmailAddress), $strSQL);

				$objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
				if ($arrRow = dbReadRecord($objResult)) 
				{
						$strRandomPassword = allocatePasswordRandom();
						$strEditingUserId = $arrRow['user_id'];
						$strAccountName = $arrRow['accountname'];
						$strClientID = $arrRow['client_id'];
						$strClientCode = $arrRow['clientcode'];
						$strClientEmailAddress = $arrRow['emailaddress'];
						$strEmailAddress = $arrRow['emailaddress'];
						$strBusinessName = $arrRow['businessname'];
						$strBusinessPhone = $arrRow['phonenumber'];
						$strBusinessEmail = $arrRow['emailaddress'];
						$strBusinessWebsite = "";
				}
				dbCloseRecordset($objResult);

				$strSQL = "select id returnvalue from ~TABLENAMERESETPASSWORD~ where token = '~OLDTOKEN~'";
				$strSQL = str_replace('~TABLENAMERESETPASSWORD~', ff($strTableNameResetPassword), $strSQL);	
				$strSQL = str_replace('~OLDTOKEN~', ff($strToken), $strSQL);
				$strResetPasswordID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

				if ((strlen($strEmailAddress) > 0) && (strlen($strResetPasswordID) > 0))
				{
					dbBeginTrans($objConn_a, __FUNCTION__);
					userPasswordUpdate($objConn_a, $strClientID, $strEditingUserId, $strRandomPassword);	// update the user's password
					resetPasswordUpdate($objConn_a, $strResetPasswordID, "Y", $strIPAddress_a);			// set the resetPassword entry to Y
					
					$arrPlaceholders = Array(
						'DATE' => $strDateToday,
						'APPLICATIONNAME' => APPNAME,
						'FULLNAME' => $strAccountName,
						'GIVENNAMES' => $strAccountName,
						'CLIENTCODE' => $strClientCode,
						'EMAILADDRESS' => $strEmailAddress,
						'PASSWORD' => $strRandomPassword,
						'BUSINESSNAME' => $strBusinessName,
						'BUSINESSPHONE' => $strBusinessPhone,
						'BUSINESSEMAILADDRESS' => $strBusinessEmail,
						'BUSINESSWEBSITE' => $strBusinessWebsite,
						'RESETPASSWORDLINK' =>  ""
					);

					sendMessageTemplatedMail($objConn_a, GENERAL_EMAIL_DOMAIN, GENERAL_EMAIL_ADDRESS, GENERAL_EMAIL_FROMNAME, $strClientEmailAddress, $strClientID, "", $strTemplateClientID, "PWD_RESET_CONF", $arrPlaceholders, "", "", true);
					$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
					
					if ($blnResult)
					{
						$strResult = "SUCCESS";
					}
				}
			}
		}
    }

    return $strResult;
}
