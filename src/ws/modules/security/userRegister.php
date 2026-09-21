<?php

// register a user
function userRegister($objConn_a, $strSecurityToken_a, $strDeviceIDCookie_a, $strRegistrationTypeCode_a, $strClientCode_a, $strLogin_a, $strPassword_a, $strUserAgent_a, $strIPAddress_a, $strCapabilities_a, $strRegistrationData_a, $strAccountEmailAddress_a, $strAccountPhoneNumber_a)
{
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameRegistration = getTableNameEntity("registration", false);

    $strResult = "";
    $blnSendEmail = false;
	$strToken = "";
	$strRegistrationID = "";

    if (dependencies('security/registerAdd,security/registerUpdate,security/userRegisterConfirmation,security/registerVerify,security/clientCodeUniquify'))
	{
		$blnContinue = true;
		
		
		if ($blnContinue) {
			if (strlen($strLogin_a) == 0) {
				$strResult = "A login is required.";
				$blnContinue = false;
			}
		}
		
		if (($blnContinue) && (strlen($strResult) == 0)) 
		{
			if (strlen($strPassword_a) == 0) {
				$strResult = "A password is required.";
				$blnContinue = false;
			}
		}

		if (($blnContinue) && (strlen($strResult) == 0)) 
		{
			// check if the email already exists, if not then add it
			$strSQL = "select count(*) returnvalue from ~TABLENAMECLIENT~ where f8d9bfb3a_3eb0_4620_ad86_af7b4e222325_login = '~LOGIN~' limit 1";
			$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
			$strSQL = str_replace('~LOGIN~', ff($strLogin_a), $strSQL);
			$intCount = intval(dbReadValue($objConn_a, $strSQL, __FUNCTION__));

			if ($intCount > 0) {
				$strResult = "The provided login has already been registered.";
			}
        }
                
		if (($blnContinue) && (strlen($strResult) == 0)) {
			dbBeginTrans($objConn_a, __FUNCTION__);

			// check if the email already exists, if not then add it
			$strSQL = "select count(*) returnvalue from ~TABLENAMEREGISTRATION~ where ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_login = '~LOGIN~' limit 1";
			$strSQL = str_replace('~TABLENAMEREGISTRATION~', ff($strTableNameRegistration), $strSQL);	
			$strSQL = str_replace('~LOGIN~', ff($strLogin_a), $strSQL);
			$intCount = intval(dbReadValue($objConn_a, $strSQL, __FUNCTION__));

			if ($intCount == 0) {
				$strToken = getGUID();
				$strEncryptCode = ''; // for future use
				$strEncryptLogin = $strLogin_a;
				$strPassword = encryptPassword2Way($strEncryptCode, $strEncryptLogin, $strPassword_a);

                // create unique client code
                //$strClientCode = clientCodeUniquify($objConn_a, $strClientCode_a);
            
				$strRegistrationID = registerAdd($objConn_a, $strToken, $strRegistrationTypeCode_a, $strClientCode_a, $strLogin_a, $strPassword, $strUserAgent_a, $strIPAddress_a, $strRegistrationData_a, $strAccountEmailAddress_a, $strAccountPhoneNumber_a);
				if (strlen($strRegistrationID) > 0)
				{
					$blnSendEmail = true;
				}
				else
				{
					$strResult = EMAIL_REGISTRATION_FAILURE;
				}
			} 
			else 
			{
				$blnSendEmail = true;
			}
			dbEndTrans($objConn_a, __FUNCTION__);

			// userRegisterConfirmation logs the user out if it wasn't interactive, so we can't continue the transaction here
			if (strlen($strResult) == 0)
			{
				if (toBoolean(IMMEDIATE_REGISTRATION))
				{
					$strClientID = userRegisterConfirmation($objConn_a, true, $strToken, $strIPAddress_a, $strCapabilities_a);
                    
					if (strlen($strClientID) == 0)
					{
						$strResult = EMAIL_REGISTRATION_FAILURE;
					}
                    
                    // if (toBoolean(SENDVERIFICATIONEMAIL))
                    // {
                        // create a token
						// $strToken = getGUID();
                        // $blnResult = sendVerificationRegistrationMessage($objConn_a, $strClientCode_a, $strEmailAddress_a, $strToken);

                        // if ($blnResult)
                        // {
                            // registerVerify($objConn_a, $strRegistrationID, "N", $strToken);
                        // }
                        // else
                        // {
                        	// $strResult = EMAIL_REGISTRATION_FAILURE;
                        // }
                    // }
				}
                else
                {
                    if ($blnSendEmail) 
                    {
						// create a token
						$strToken = getGUID();
						$blnResult = sendRegistrationMessageInit($objConn_a, $strClientCode_a, $strLogin_a, $strPassword_a, $strToken);

						if ($blnResult == true && strlen($strRegistrationID) > 0) 
						{
							registerUpdate($objConn_a, $strRegistrationID, "N", $strToken, $strUserAgent_a, $strIPAddress_a);
						} 
						else 
						{
							// respond to the user with a message
							$strResult = EMAIL_REGISTRATION_FAILURE;
						}
					}
                }                                
			}							
		}
	}

    return $strResult;
}
