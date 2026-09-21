<?php
function actionPublicRegister($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a, $strDeviceIDCookie_a)
{
	$strTableNameOperatingLocality = getTableNameEntity("operatinglocality", false);
	$strTableNameRegistrationType = getTableNameEntity("registrationtype", false);
	
    $strResult = "";

    if (dependencies('security/userRegister') &&
        dependencies('system/analytics')) 
	{
        
		// permission check
		if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}
		
        // parameters
        $strClientCode = strtolower(getJSONParameter($arrParameters_a, 'clientcode'));
        $strLogin = strtolower(getJSONParameter($arrParameters_a, 'login')); // we register using the login as the email address
        $strPassword = strtolower(getJSONParameter($arrParameters_a, 'password'));
        $strUserAgent = getJSONParameter($arrParameters_a, 'useragent');
        $strCapabilities = getJSONParameter($arrParameters_a, 'capabilities');
        $strIPAddress = $_SERVER["REMOTE_ADDR"];
        $strEmailAddress = $strLogin;	// users register with email address
        $strPhoneNumber = "";
        
        $blnIsTermsConditions = toBoolean(getJSONParameter($arrParameters_a, 'termsconditions'));
        $strIsTermsConditions = 'N';
        if ($blnIsTermsConditions) 
		{
            $strIsTermsConditions = 'Y';
        }

        
        $blnIsPrivacy = toBoolean(getJSONParameter($arrParameters_a, 'privacy'));
        $strIsPrivacy = 'N';
        if ($blnIsPrivacy) 
		{
            $strIsPrivacy = 'Y';
        }
        
        
        // optional because the simple registration does not take in a type, if not provided we want the default
        $strRegistrationTypeID = revertSecuredValue(getJSONParameter($arrParameters_a, 'registrationtype_id'), 'registrationtype_id', false);
        $strRegistrationTypeCode = DEFAULT_REGISTRATIONTYPE;
        if (strlen($strRegistrationTypeID) > 0)
        {
            $strSQL = "select code returnvalue from ~TABLENAMEREGISTRATIONTYPE~ where id = ~REGISTRATIONTYPEID~";
			$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);	
            $strSQL = str_replace('~REGISTRATIONTYPEID~', ff($strRegistrationTypeID), $strSQL);
            $strRegistrationTypeCode = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
        }
        
		foreach ($arrParameters_a as &$arrElement) 
		{
			// remove password from the JSON
			if ($arrElement["name"] == 'password') 
			{
				//unset($arrElement);
				$arrElement['value'] = "";
			}
        
            //update privacy
            if ($arrElement["name"] == 'privacy') 
			{
                $arrElement["value"] = $strIsPrivacy;
            }
            
            //update termsconditions
            if ($arrElement["name"] == 'termsconditions') 
			{
                $arrElement["value"] = $strIsTermsConditions;
            }
        }

        $strRegistrationData = json_encode($arrParameters_a);

        $strResult = userRegister($objConn_a, $strSecurityToken_a, $strDeviceIDCookie_a, $strRegistrationTypeCode, $strClientCode, $strLogin, $strPassword, $strUserAgent, $strIPAddress, $strCapabilities, $strRegistrationData, $strEmailAddress, $strPhoneNumber);

        if (strlen($strResult) == 0) 
		{
            analyticsTrack($strIPAddress, $strUserAgent, __FUNCTION__, 'register success');            
            $strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', array());
        } 
		else 
		{
            analyticsTrack($strIPAddress, $strUserAgent, __FUNCTION__, 'register failure');
            //$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Registration failed.', array());
            $strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, $strResult, array());
        }
    }

    return $strResult;
}
