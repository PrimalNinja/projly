<?php

// confirm user registration
// note: returning a value is considered success
function userRegisterConfirmation($objConn_a, $blnInteractive_a, $strSecurityToken_a, $strIPAddress_a, $strCapabilities_a)
{
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameRegistration = getTableNameEntity("registration", false);
	$strTableNameRegistrationType = getTableNameEntity("registrationtype", false);
	
    $strResult = "";
	$strRegistrationTypeCode = "";
    $strClientCode = "";
    $strLogin = "";
    $strPassword = "";
	$strRegistrationFormJSON = "";
    $strClientEmailAddress = "";
	$strClientPhoneNumber = "";

    if (dependencies('security/clientInitialise,security/registerUpdate,security/userLogin,security/userLogout,security/registerVerify')) 
	{
		if (!$blnInteractive_a)
		{
			// fake batch login as it happens behind the scenes
			$strBatchToken = getGUID();
			$strBatchCookie = 'BatchCookie';
			$strBatchAgent = 'PHP';
			$strBatchHost = 'localhost';
			$strClientID = '';
			$strClientDB = getSessionDB(__FUNCTION__);

			$blnResult = userLogin($objConn_a, $strBatchToken, $strBatchCookie, BATCH_CLIENT, BATCH_LOGIN, BATCH_PASSWORD, '', 'N', $strBatchAgent, $strBatchHost, $strCapabilities_a, false, $strClientDB, false, '');
		}

        $strToken = $strSecurityToken_a;

        if (strlen($strToken) > 0) 
		{
			dbBeginTrans($objConn_a, __FUNCTION__);

            // check if the email already exists, if not then add it
            $strSQL = "select count(*) returnvalue from ~TABLENAMEREGISTRATION~ where token = '~TOKEN~' and ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_isconfirmed = 'N' limit 1";
			$strSQL = str_replace('~TABLENAMEREGISTRATION~', ff($strTableNameRegistration), $strSQL);	
            $strSQL = str_replace('~TOKEN~', ff($strToken), $strSQL);
            $intCount = intval(dbReadValue($objConn_a, $strSQL, __FUNCTION__));

            if ($intCount > 0) 
			{
                $strSQL = "select registrationtypecode, ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_clientcode clientcode, ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_login login, password, jsondata, ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_accountemailaddress emailaddress, ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_accountphonenumber phonenumber from ~TABLENAMEREGISTRATION~ where token = '~TOKEN~' and ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_isconfirmed = 'N'";
				$strSQL = str_replace('~TABLENAMEREGISTRATION~', ff($strTableNameRegistration), $strSQL);	
                $strSQL = str_replace('~TOKEN~', ff($strToken), $strSQL);
                $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
                if ($arrRow = dbReadRecord($objResult)) 
				{
					$strRegistrationTypeCode = $arrRow['registrationtypecode'];
                    $strClientCode = $arrRow['clientcode'];
                    $strLogin = $arrRow['login'];
                    $strPassword = $arrRow['password'];
					$strRegistrationFormJSON = $arrRow['jsondata'];
                    $strClientEmailAddress = $arrRow['emailaddress'];
					$strClientPhoneNumber = $arrRow['phonenumber'];
                }
                dbCloseRecordset($objResult);

                $strDecryptCode = ''; // for future use
                $strDecryptLogin = $strLogin;
                $strPassword = decryptPassword2Way($strDecryptCode, $strDecryptLogin, $strPassword);

				$strSQL = "select id returnvalue from ~TABLENAMEREGISTRATIONTYPE~ where code = '~REGISTRATIONTYPECODE~'";
				$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);	
				$strSQL = str_replace('~REGISTRATIONTYPECODE~', ff($strRegistrationTypeCode), $strSQL);
				$strRegistrationTypeID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

				$strSQL = "select ff4909db1e_8bee_4c2b_be10_6d0853789635_isemployer returnvalue from ~TABLENAMEREGISTRATIONTYPE~ where id = ~REGISTRATIONTYPEID~";
				$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);	
				$strSQL = str_replace('~REGISTRATIONTYPEID~', ff($strRegistrationTypeID), $strSQL);
				$blnIsEmployer = toBoolean(dbReadValue($objConn_a, $strSQL, __FUNCTION__));

				if (dependencies('security/clientCodeUniquify'))
				{
					$strClientCode = clientCodeUniquify($objConn_a, $strClientCode);
				}

				if (strlen($strClientEmailAddress) > 0)
				{
					$blnResult = sendRegistrationConfirmationMessage($objConn_a, $strClientCode, $strClientEmailAddress, $strPassword);
				}
				else
				{
					$blnResult = true;
				}

                if ($blnResult == true) 
				{
                    // create the client
					$arrRegistrationFormJSON = json_decode($strRegistrationFormJSON, true);

					$strClientDescription = formValueGetBySectionCodeFieldCode($arrRegistrationFormJSON, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ACCOUNTNAME");
					$strUserDescription = $strClientDescription;
					$strUserEmailAddress = $strClientEmailAddress;
					$strUserPhoneNumber = $strClientPhoneNumber;
					$strUserLogin = $strLogin;
					
					if ($blnIsEmployer)
					{
						$strUserDescription .= " Admin";
					}
					
					if (strlen($strUserDescription) == 0)
					{
						$strClientDescription = $strClientCode;
						$strUserDescription = $strClientCode;
					}

					// clientInitialise actually does the client creation also
					$strClientID = clientInitialise($objConn_a, true, "", $strClientCode, $strClientDescription, $strClientEmailAddress, $strUserPhoneNumber, "Y", $strUserDescription, $strUserEmailAddress, $strUserLogin, $strPassword, $strRegistrationTypeID, $strRegistrationFormJSON);
					if (strlen($strClientID) > 0)
					{
						$strSQL = "select id returnvalue from ~TABLENAMEREGISTRATION~ where token = '~OLDTOKEN~'";
						$strSQL = str_replace('~TABLENAMEREGISTRATION~', ff($strTableNameRegistration), $strSQL);	
						$strSQL = str_replace('~OLDTOKEN~', ff($strToken), $strSQL);
						$strRegistrationID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

						if (strlen($strRegistrationID) > 0)
						{
							// create a token
							$strNewToken = getGUID();
							registerUpdate($objConn_a, $strRegistrationID, "Y", $strNewToken, "", $strIPAddress_a);
                            registerVerify($objConn_a, $strRegistrationID, "Y", $strNewToken);
						}

						$strSQL = "select code returnvalue from ~TABLENAMECLIENT~ where id = ~CLIENTID~";
						$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);	
						$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
						$strClientCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

						$_SESSION['last_clientcode'] = $strClientCode;
						$_SESSION['last_login'] = $strLogin;
						
						$strResult = $strClientID;
					}
                }
            }

            dbEndTrans($objConn_a, __FUNCTION__);
        }

		if (!$blnInteractive_a)
		{
			userLogout();
		}
    }

    return $strResult;
}
