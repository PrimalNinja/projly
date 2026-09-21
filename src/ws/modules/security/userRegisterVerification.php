<?php

// confirm user registration
// note: returning a value is considered success
function userRegisterVerification($objConn_a, $blnInteractive_a, $strSecurityToken_a, $strIPAddress_a, $strCapabilites_a)
{
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameRegistration = getTableNameEntity("registration", false);
	$strTableNameRegistrationType = getTableNameEntity("registrationtype", false);
	
    $strResult = "";
    $strClientCode = "";
    $strClientLogin = "";
    
    if (dependencies('security/userLogin,security/userLogout,security/registerVerify')) 
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

			$blnResult = userLogin($objConn_a, $strBatchToken, $strBatchCookie, BATCH_CLIENT, BATCH_LOGIN, BATCH_PASSWORD, '', 'N', $strBatchAgent, $strBatchHost, $strCapabilites_a, false, $strClientDB, false, '');
		}

        $strToken = $strSecurityToken_a;

        if (strlen($strToken) > 0) 
		{
			dbBeginTrans($objConn_a, __FUNCTION__);

            // check if the email already exists, if not then add it
            $strSQL = "select count(*) returnvalue from ~TABLENAMEREGISTRATION~ where token = '~TOKEN~' and is_verified = 'N' limit 1";
			$strSQL = str_replace('~TABLENAMEREGISTRATION~', ff($strTableNameRegistration), $strSQL);	
            $strSQL = str_replace('~TOKEN~', ff($strToken), $strSQL);
            $intCount = intval(dbReadValue($objConn_a, $strSQL, __FUNCTION__));

            if ($intCount > 0) 
			{
                $strSQL = "select id, client_id, ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_clientcode clientcode, ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_login login, jsondata from ~TABLENAMEREGISTRATION~ where token = '~TOKEN~' and is_verified = 'N'";
				$strSQL = str_replace('~TABLENAMEREGISTRATION~', ff($strTableNameRegistration), $strSQL);	
                $strSQL = str_replace('~TOKEN~', ff($strToken), $strSQL);
                $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
                
                if ($arrRow = dbReadRecord($objResult)) 
				{
                    $strRegistrationID = $arrRow['id'];
                    $strClientID = $arrRow['client_id'];
                    $strClientCode = $arrRow['clientcode'];
                    $strClientLogin = $arrRow['login'];
                }
                
                dbCloseRecordset($objResult);

                if (strlen($strRegistrationID) > 0) 
				{						
                    // create a token
                    $strNewToken = getGUID();
                    //registerUpdate($objConn_a, $strRegistrationID, "Y", $strNewToken, "", $strIPAddress_a);
                    registerVerify($objConn_a, $strRegistrationID, "Y", $strNewToken);

                    $strSQL = "select code returnvalue from ~TABLENAMECLIENT~ where id = ~CLIENTID~";
                    $strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);	
                    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
                    $strClientCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

                    $_SESSION['last_clientcode'] = $strClientCode;
                    $_SESSION['last_login'] = $strClientLogin;

                    $strResult = $strClientID;
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
