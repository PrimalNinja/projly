<?php

// add a registrant
function registerAdd($objConn_a, $strToken_a, $strRegistrationTypeCode_a, $strClientCode_a, $strLogin_a, $strPassword_a, $strUserAgent_a, $strIPAddress_a, $strRegistrationData_a, $strAccountEmailAddress_a, $strAccountPhoneNumber_a)
{
	$strTableNameClient = getTableNameEntity("client", false);
    $strTableNameProduct = getTableNameEntity("product", false);
	$strTableNameProductType = getTableNameEntity("producttype", false);
	$strTableNameRegistration = getTableNameEntity("registration", false);
	$strTableNameRegistrationType = getTableNameEntity("registrationtype", false);
	$strTableNameState = getTableNameEntity("state", false);

    $strRegistrationID = "";

	dbBeginTrans($objConn_a, __FUNCTION__);

	$strLogin = $_SESSION['server_loggedin_user'];

	$strSystemClientID = getSystemClientID($objConn_a);

	$strSQL = "select description returnvalue from ~TABLENAMEREGISTRATIONTYPE~ where code = '~REGISTRATIONTYPECODE~'";
	$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);
	$strSQL = str_replace('~REGISTRATIONTYPECODE~', ff($strRegistrationTypeCode_a), $strSQL);
	$strRegistrationTypeDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	$arrRegistrationData = json_decode($strRegistrationData_a, true);

	// account details
	$strAccountName = getJSONParameter($arrRegistrationData, 'accountname');
	$strFullName = getJSONParameter($arrRegistrationData, 'fullname');

	if (strlen($strAccountName) == 0)
	{
		$strAccountName = trim($strFullName);
	}

	// other details
	$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "REGISTRATION");

	// get the entity ids
	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, "registration");

	// now get the registrationtype usind ID
	$blnIsEmployer = false;
	$blnIsIndividual = false;
	$strRegistrationAccountType = "DEFAULT";
	$strRegistrationAccountTypeDescription = "";

	$strSQL = "select code returnvalue from ~TABLENAMEREGISTRATIONTYPE~ where code = '~REGISTRATIONTYPECODE~'";
	$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);
	$strSQL = str_replace('~REGISTRATIONTYPECODE~', ff($strRegistrationTypeCode_a), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strSystemClientID), $strSQL);
	$strRegistrationAccountSubType = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	if (strlen($strRegistrationAccountSubType) > 0)
	{
		if (!$blnIsEmployer && !$blnIsIndividual)
		{
			$strSQL = "select ff4909db1e_8bee_4c2b_be10_6d0853789635_isemployer returnvalue from ~TABLENAMEREGISTRATIONTYPE~ where code = '~REGISTRATIONTYPECODE~'";
			$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);
			$strSQL = str_replace('~REGISTRATIONTYPECODE~', ff($strRegistrationAccountSubType), $strSQL);
			$blnIsEmployer = toBoolean(dbReadValue($objConn_a, $strSQL, __FUNCTION__));
		}

		if (!$blnIsEmployer)
		{
			$strSQL = "select ff4909db1e_8bee_4c2b_be10_6d0853789635_isindividual returnvalue from ~TABLENAMEREGISTRATIONTYPE~ where code = '~REGISTRATIONTYPECODE~'";
			$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);
			$strSQL = str_replace('~REGISTRATIONTYPECODE~', ff($strRegistrationAccountSubType), $strSQL);
			$blnIsIndividual = toBoolean(dbReadValue($objConn_a, $strSQL, __FUNCTION__));
		}

		if ($blnIsEmployer)
		{
			$strRegistrationAccountType = ACCOUNTTYPE_BUSINESS;
			$strRegistrationAccountTypeDescription = ACCOUNTTYPE_BUSINESS;
		}
		else if ($blnIsIndividual)
		{
			$strRegistrationAccountType = ACCOUNTTYPE_INDIVIDUAL;
			$strRegistrationAccountTypeDescription = ACCOUNTTYPE_INDIVIDUAL;
		}
    }
    
    if (strlen($strRegistrationAccountType) > 0)
	{
		// check if the product of $strRegistrationAccountType exists as a type
		$strSQL = "select p.description returnvalue from ~TABLENAMEPRODUCT~ p, ~TABLENAMEPRODUCTTYPE~ pt where pt.id = p.producttype_id and pt.code = 'TYPE' and p.code = '~PRODUCTCODE~'";
		$strSQL = str_replace('~TABLENAMEPRODUCT~', ff($strTableNameProduct), $strSQL);
		$strSQL = str_replace('~TABLENAMEPRODUCTTYPE~', ff($strTableNameProductType), $strSQL);
		$strSQL = str_replace('~PRODUCTCODE~', ff($strRegistrationAccountType), $strSQL);
		$strRegistrationAccountTypeDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
	}

	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ACCOUNTTYPE", $strRegistrationAccountTypeDescription);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "REGISTRATIONTYPEDESC", $strRegistrationTypeDescription);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ACCOUNTNAME", $strAccountName);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "CLIENTCODE", $strClientCode_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "LOGIN", $strLogin_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ACCOUNTEMAILADDRESS", $strAccountEmailAddress_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ACCOUNTPHONENUMBER", $strAccountPhoneNumber_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ISCONFIRMED", 'N');

	$strSalesPersonCode = getJSONParameter($arrRegistrationData, 'salespersoncode');

	// primary contact details
	$strContactFullName = getJSONParameter($arrRegistrationData, 'contactfullname');
	$strContactAddressLine1 = getJSONParameter($arrRegistrationData, 'contactaddressline1');
	$strContactAddressLine2 = getJSONParameter($arrRegistrationData, 'contactaddressline2');
	$strContactSuburb = getJSONParameter($arrRegistrationData, 'contactsuburb');
	$strContactState = getJSONParameter($arrRegistrationData, 'contactstate');
	$strContactPostcode = getJSONParameter($arrRegistrationData, 'contactpostcode');
	$strContactCountry = getJSONParameter($arrRegistrationData, 'contactcountry');
	$strContactPhoneNumber = getJSONParameter($arrRegistrationData, 'contactphonenumber');
	$strContactEmailAddress = getJSONParameter($arrRegistrationData, 'contactemailaddress');

	// secondary contact details
	$strContactFullNameB = getJSONParameter($arrRegistrationData, 'contactFullname_2');
	$strContactAddressLine1B = getJSONParameter($arrRegistrationData, 'contactaddressline1_2');
	$strContactAddressLine2B = getJSONParameter($arrRegistrationData, 'contactaddressline2_2');
	$strContactSuburbB = getJSONParameter($arrRegistrationData, 'contactsuburb_2');
	$strContactStateB = getJSONParameter($arrRegistrationData, 'contactstate_2');
	$strContactPostcodeB = getJSONParameter($arrRegistrationData, 'contactpostcode_2');
	$strContactCountryB = getJSONParameter($arrRegistrationData, 'contactcountry_2');
	$strContactPhoneNumberB = getJSONParameter($arrRegistrationData, 'contactphonenumber_2');
	$strContactEmailAddressB = getJSONParameter($arrRegistrationData, 'contactemailaddress_2');

	// primary contact state
	// state lookup by description as the registration form is freeform text
	$strSQL = "select id returnvalue from ~TABLENAMESTATE~ where description = '~DESCRIPTION~'";
	$strSQL = str_replace('~TABLENAMESTATE~', ff($strTableNameState), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strContactState), $strSQL);
	$strStateID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	// this really just fixes the case of the provided state to become the case of the reference data
	$strSQL = "select description returnvalue from ~TABLENAMESTATE~ where description = '~DESCRIPTION~'";
	$strSQL = str_replace('~TABLENAMESTATE~', ff($strTableNameState), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strContactState), $strSQL);
	$strStateDescription = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	// secondary contact state
	// state lookup by description as the registration form is freeform text
	$strSQL = "select id returnvalue from ~TABLENAMESTATE~ where description = '~DESCRIPTION~'";
	$strSQL = str_replace('~TABLENAMESTATE~', ff($strTableNameState), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strContactStateB), $strSQL);
	$strStateIDB = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	// this really just fixes the case of the provided state to become the case of the reference data
	$strSQL = "select description returnvalue from ~TABLENAMESTATE~ where description = '~DESCRIPTION~'";
	$strSQL = str_replace('~TABLENAMESTATE~', ff($strTableNameState), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strContactStateB), $strSQL);
	$strStateDescriptionB = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "SALESPERSONCODE", $strSalesPersonCode);

	// primary contact details
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "FULLNAME", $strContactFullName);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ADDRESSLINE1", $strContactAddressLine1);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ADDRESSLINE2", $strContactAddressLine2);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "SUBURB", $strContactSuburb);
	$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "STATE", $strStateID, $strStateDescription);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "POSTCODE", $strContactPostcode);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "COUNTRY", $strContactCountry);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "PHONENUMBER", $strContactPhoneNumber);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "EMAILADDRESS", $strContactEmailAddress);

	// secondary contact details
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "FULLNAMEB", $strContactFullNameB);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ADDRESSLINE1B", $strContactAddressLine1B);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ADDRESSLINE2B", $strContactAddressLine2B);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "SUBURBB", $strContactSuburbB);
	$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "STATEB", $strStateIDB, $strStateDescriptionB);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "POSTCODEB", $strContactPostcodeB);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "COUNTRYB", $strContactCountryB);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "PHONENUMBERB", $strContactPhoneNumberB);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "EMAILADDRESSB", $strContactEmailAddressB);

	// business details
	$strBusinessName = getJSONParameter($arrRegistrationData, 'businessname');
	$strABN = getJSONParameter($arrRegistrationData, 'abn');
	$strAbout = getJSONParameter($arrRegistrationData, 'about');

	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "BUSINESSNAME", $strBusinessName);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ABN", $strABN);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ABOUT", $strAbout);

	$strTermsConditions = getJSONParameter($arrRegistrationData, 'termsconditions');
	$strPrivacy = getJSONParameter($arrRegistrationData, 'privacy');

	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "TERMSCONDITIONS", $strTermsConditions);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "PRIVACY", $strPrivacy);

	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "USERAGENT", $strUserAgent_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "IPADDRESS", $strIPAddress_a);
	$strJSONData = json_encode($arrJSONData);

	$strRegistrationDescription = $strAccountEmailAddress_a;
	if (strlen($strRegistrationDescription) == 0)
	{
		$strRegistrationDescription = $strAccountPhoneNumber_a;
	}

	$strSQL = "insert into ~TABLENAMEREGISTRATION~ (client_id, entity_id, dataentity_id, code, description, is_enabled, data_client_id, jsondata, registrationtypecode, password, token, is_confirmed, is_verified, modifydatetime)
				values (~CLIENTID~, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', 'N', '~CLIENTID~', '~JSONDATA~', '~REGISTRATIONTYPECODE~', '~PASSWORD~', '~TOKEN~', 'N', 'N', '~MODIFYDATETIME~')";

	$strSQL = str_replace('~TABLENAMEREGISTRATION~', ff($strTableNameRegistration), $strSQL);
	$strSQL = str_replace('~REGISTRATIONTYPECODE~', ff($strRegistrationTypeCode_a), $strSQL);
	$strSQL = str_replace('~CLIENTID~', ff($strSystemClientID), $strSQL);
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
	$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strClientCode_a), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strRegistrationDescription), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~PASSWORD~', ff($strPassword_a), $strSQL);
	$strSQL = str_replace('~TOKEN~', ff($strToken_a), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	$strRegistrationID = dbLastInsertID($objConn_a);

	exposeEntityData($objConn_a, 'SYSTEMFORM', 'REGISTRATION', $strRegistrationID, $strJSONData);

	dbEndTrans($objConn_a, __FUNCTION__);

    return $strRegistrationID;
}
