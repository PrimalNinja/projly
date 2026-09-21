<?php

// $strFormDataID_a = ACCOUNT

// code in before display add events:
//		default fields within the JSON for display purposes before a user starts filling in a form
//
// code in after events: 
//		exposing fields
//		populating manually created fields
//
// code in before events: 
// 		modifying the json that is to be stored (it is stored automatically)
//		validation such as uniqueness (don't forget to put unique indexes on field combinations you need to be unique)
//
// event order:  
//		before events, saving of json and common fields, after events, transfer of exposed field's values
//
function beforeDisplayAddUpdate_account($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $blnUpdate_a, $strMode_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeAddUpdate_account($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	
			// first do any json updates required before validation
		// primary contact details
		$strFullName = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "FULLNAME");
		$strAddressLine1 = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "ADDRESSLINE1");
		$strAddressLine2 = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "ADDRESSLINE2");
		$strSuburb = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "SUBURB");
		$strStateID = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "STATE");
		$strState = formDescriptionGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "STATE");
		$strPostcode = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTCODE");
		$strCountry = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "COUNTRY");
		$arrSuburbPopulate = [];
		$strSuburbPopulateID = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "SUBURBPOPULATE");

		// if a suburb is selected with the entity picker, set the suburb fields
		if (strlen($strSuburbPopulateID) > 0)
		{
			$arrSuburbPopulate = suburbInfoGetFromDBBySuburbID($objConn_a, $strSuburbPopulateID);
			if (count($arrSuburbPopulate) > 0)
			{
				$strSuburb = $arrSuburbPopulate["suburb"];
				$strStateID = $arrSuburbPopulate["stateid"];
				$strState = $arrSuburbPopulate["state"];
				$strPostcode = $arrSuburbPopulate["postcode"];
				$strCountry = $arrSuburbPopulate["country"];
			}
			
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "SUBURB", $strSuburb);
			$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "STATE", $strStateID, $strState);
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTCODE", $strPostcode);		
			$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "COUNTRY", $strCountry);		
		}
		
		// validate main suburb, state, postcode combination if any of those fields are not left as empty
		$blnMainAddressSuburbStatePostcodeValid = true;
		
		if ((strlen($strSuburb) > 0) || (strlen($strState) > 0) || (strlen($strPostcode) > 0))
		{
			$blnMainAddressSuburbStatePostcodeValid = validateSuburbStatePostcodeCombination($objConn_a, $strSuburb, $strState, $strPostcode);
		}

		if (!$blnMainAddressSuburbStatePostcodeValid)
		{
			$blnError = true;
			dbRaiseCustomError($objConn_a, "Invalid combination of suburb, state and postcode.");
		}
		
		$strSameForPostalAddress = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "SAMEFORPOSTALADDRESS");
		if ($strSameForPostalAddress == "Y")
		{
			// do nothing
		}
		else
		{			
			// postal address details
			$strAddressLine1 = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALADDRESSLINE1");
			$strAddressLine2 = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALADDRESSLINE2");
			$strSuburb = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALSUBURB");
			$strStateID = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALSTATE");
			$strState = formDescriptionGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALSTATE");
			$strPostcode = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALPOSTCODE");
			$strCountry = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALCOUNTRY");
			$arrSuburbPopulate = [];
			$strSuburbPopulateID = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALSUBURBPOPULATE");
			
			// if a postal suburb is selected with the entity picker, set the suburb fields
			if (strlen($strSuburbPopulateID) > 0)
			{
				$arrSuburbPopulate = suburbInfoGetFromDBBySuburbID($objConn_a, $strSuburbPopulateID);
				if (count($arrSuburbPopulate) > 0)
				{
					$strSuburb = $arrSuburbPopulate["suburb"];
					$strStateID = $arrSuburbPopulate["stateid"];
					$strState = $arrSuburbPopulate["state"];
					$strPostcode = $arrSuburbPopulate["postcode"];
					$strCountry = $arrSuburbPopulate["country"];
				}
			}
			
			// validate postal suburb, state, postcode combination if any of those fields are not left as empty
			$blnPostalAddressSuburbStatePostcodeValid = true;
			
			if ((strlen($strSuburb) > 0) || (strlen($strState) > 0) || (strlen($strPostcode) > 0))
			{
				$blnPostalAddressSuburbStatePostcodeValid = validateSuburbStatePostcodeCombination($objConn_a, $strSuburb, $strState, $strPostcode);
			}

			if (!$blnPostalAddressSuburbStatePostcodeValid)
			{
				$blnError = true;
				dbRaiseCustomError($objConn_a, "Invalid combination of postal suburb, state and postcode.");
			}
		}

		// set the postal address details
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALADDRESSLINE1", $strAddressLine1);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALADDRESSLINE2", $strAddressLine2);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALADDRESSEE", $strFullName);		
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALSUBURB", $strSuburb);
		$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALSTATE", $strStateID, $strState);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALPOSTCODE", $strPostcode);	
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALCOUNTRY", $strCountry);	

		// set the postal suburb entity picker
		if (strlen($strSuburbPopulateID) > 0)
		{
			$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALSUBURBPOPULATE", $strSuburbPopulateID, $strSuburb . ' | ' . $strState . ' | '  . $strPostcode);					
		}
		else
		{
			$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALSUBURBPOPULATE", "", "");					
		}
		
		$strSameForBillingAddress = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "SAMEFORBILLINGADDRESS");
		if ($strSameForBillingAddress == "Y")
		{
			// do nothing
		}
		else
		{			
			// billing address details
			$strAddressLine1 = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGADDRESSLINE1");
			$strAddressLine2 = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGADDRESSLINE2");
			$strSuburb = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGSUBURB");
			$strStateID = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGSTATE");
			$strState = formDescriptionGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGSTATE");
			$strPostcode = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGPOSTCODE");
			$strCountry = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGCOUNTRY");
			$arrSuburbPopulate = [];
			$strSuburbPopulateID = formValueGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGSUBURBPOPULATE");
			
			// if a billing suburb is selected with the entity picker, set the suburb fields
			if (strlen($strSuburbPopulateID) > 0)
			{
				$arrSuburbPopulate = suburbInfoGetFromDBBySuburbID($objConn_a, $strSuburbPopulateID);
				if (count($arrSuburbPopulate) > 0)
				{
					$strSuburb = $arrSuburbPopulate["suburb"];
					$strStateID = $arrSuburbPopulate["stateid"];
					$strState = $arrSuburbPopulate["state"];
					$strPostcode = $arrSuburbPopulate["postcode"];
					$strCountry = $arrSuburbPopulate["country"];
				}
			}
			
			// validate billing suburb, state, postcode combination if any of those fields are not left as empty
			$blnBillingAddressSuburbStatePostcodeValid = true;
			
			if ((strlen($strSuburb) > 0) || (strlen($strState) > 0) || (strlen($strPostcode) > 0))
			{
				$blnBillingAddressSuburbStatePostcodeValid = validateSuburbStatePostcodeCombination($objConn_a, $strSuburb, $strState, $strPostcode);
			}

			if (!$blnBillingAddressSuburbStatePostcodeValid)
			{
				$blnError = true;
				dbRaiseCustomError($objConn_a, "Invalid combination of billing suburb, state and postcode.");
			}
		}

		// set the billing address details
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGADDRESSLINE1", $strAddressLine1);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGADDRESSLINE2", $strAddressLine2);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGBILLTO", $strFullName);		
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGSUBURB", $strSuburb);
		$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGSTATE", $strStateID, $strState);
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGPOSTCODE", $strPostcode);	
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGCOUNTRY", $strCountry);	

		// set the billing suburb entity picker
		if (strlen($strSuburbPopulateID) > 0)
		{
			$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGSUBURBPOPULATE", $strSuburbPopulateID, $strSuburb . ' | ' . $strState . ' | '  . $strPostcode);					
		}
		else
		{
			$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGSUBURBPOPULATE", "", "");					
		}
	
	return $arrJSONData;
}

function afterAddUpdate_account($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	
	if (dependencies('security/clientUpdateFromAccount'))
	{
		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "ACCOUNTNAME");
		$strDescription = $arrJSONField['p_value'];

		$arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "ACCOUNTEMAILADDRESS");
		$strAccountEmailAddress = $arrJSONField['p_value'];

		if ($blnUpdate_a) 
		{
			clientUpdateFromAccount($objConn_a, $strFormDataID_a, $strDescription, $strAccountEmailAddress);
		}
	}

	return $arrJSONData;
}

// ***IMPORTANT*** PUT ALL FUNCTIONALITY THAT RELIES ON THE EXPOSED FIELDS IN HERE!!!
function afterAddUpdateExpose_account($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
	$arrJSONData = $arrJSONData_a;
	return $arrJSONData;
}

function beforeDelete_account($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

function afterDelete_account($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a) 
{
}

// called before fetching, useful if jsondata is null to dynamically create it from the fields
function beforeSelect_account($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
{
	// $strTableNameAccount = getTableNameEntity("account", false);
	// $strTableNameRegistrationType = getTableNameEntity("registrationtype", false);

	// $strSQL = "select id from ~TABLENAMEACCOUNT~ where jsondata is null";
	// $strSQL = str_replace("~TABLENAMEACCOUNT~", ff($strTableNameAccount), $strSQL);
	// $objResultLoop = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
	// while ($arrRowLoop = dbReadRecord($objResultLoop)) {
		// $strAccountID = $arrRowLoop['id'];
	
		// $strCode = "";
		// $strDescription = "";
		// $strIsEnabled = "";
		
		// $strRegistrationTypeID = "";	// need to populate a description REGISTRATIONTYPE
		// $strCreateDate = "";
		// $strAccountName = "";
		// $strEmailAddress = "";
		// $strCompanyName = "";
		// $strABN = "";
		// $strAbout = "";

		// $strBusinessContact = "";
		// $strBusinessPhone = "";
		// $strBusinessEmail = "";

		// $strAddress1 = "";
		// $strAddress2 = "";
		// $strSuburb = "";
		// $strState = "";
		// $strPostcode = "";
		// $strCountry = "";

		// $strIsPrivacy = "";
		// $strIsAgreement = "";

		// $strSQL = "select code, description, is_enabled,
// registrationtype_id, accountname, email_address, companyname, abn, about, business_contact, business_phone, business_email, address1, address2, suburb, state, postcode, country, is_privacy, is_agreement, createdate
// from ~TABLENAMEACCOUNT~ where id = ~ACCOUNTID~";			
		// $strSQL = str_replace("~TABLENAMEACCOUNT~", ff($strTableNameAccount), $strSQL);
		// $strSQL = str_replace("~ACCOUNTID~", ff($strAccountID), $strSQL);
		// $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
		// if ($arrRow = dbReadRecord($objResult)) {
			//$strCode = $arrRow['code'];
			//$strDescription = $arrRow['description'];
			// $strIsEnabled = $arrRow['is_enabled'];
			
			// $strRegistrationTypeID = $arrRow['registrationtype_id'];
			// $strCreateDate = $arrRow['createdate'];
			// $strAccountName = $arrRow['accountname'];
			// $strEmailAddress = $arrRow['email_address'];
			// $strCompanyName = $arrRow['companyname'];
			// $strABN = $arrRow['abn'];
			// $strAbout = $arrRow['about'];

			// $strBusinessContact = $arrRow['business_contact'];
			// $strBusinessPhone = $arrRow['business_phone'];
			// $strBusinessEmail = $arrRow['business_email'];

			// $strAddress1 = $arrRow['address1'];
			// $strAddress2 = $arrRow['address2'];
			// $strSuburb = $arrRow['suburb'];
			// $strState = $arrRow['state'];
			// $strPostcode = $arrRow['postcode'];
			// $strCountry = $arrRow['country'];

			// $strIsPrivacy = $arrRow['is_privacy'];
			// $strIsAgreement = $arrRow['is_agreement'];

			//$strUserID = $arrRow['user_id'];	// not part of blob
			//$strEmployeeID = $arrRow['employee_id'];	// not part of blob
			//$strViewCount = $arrRow['viewcount'];	// not part of blob
			//$strOffenceCount = $arrRow['offencecount'];	// not part of blob
		// }
		// dbCloseRecordset($objResult);
		
		// $strRegistrationType = dbGetDescriptionFromID($objConn_a, $strTableNameRegistrationType, $strRegistrationTypeID, __FUNCTION__);

		// $arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "ACCOUNT");
		
		//$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "DESCRIPTION", $strDescription);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "REGISTRATIONTYPE", $strRegistrationType);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "CREATEDATE", $strCreateDate);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "ACCOUNTNAME", $strAccountName);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "EMAILADDRESS", $strEmailAddress);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "ISENABLED", $strIsEnabled);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "COMPANYNAME", $strCompanyName);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "ABN", $strABN);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "ABOUT", $strAbout);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "CONTACTNAME", $strBusinessContact);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "CONTACTPHONE", $strBusinessPhone);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "CONTACTEMAILADDRESS", $strBusinessEmail);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "ADDRESSLINE1", $strAddress1);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "ADDRESSLINE2", $strAddress2);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "SUBURB", $strSuburb);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "STATE", $strState);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTCODE", $strPostcode);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "COUNTRY", $strCountry);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "ISPRIVACY", $strIsPrivacy);
		// $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "TERMSANDCONDITIONS", $strIsAgreement);

		// $strJSONData = json_encode($arrJSONData);

		// dbBeginTrans($objConn_a, __FUNCTION__);

		// $strSQL = "update ~TABLENAMEACCOUNT~ set jsondata = '~JSONDATA~' where id = ~ACCOUNTID~";
		// $strSQL = str_replace("~TABLENAMEACCOUNT~", ff($strTableNameAccount), $strSQL);
		// $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
		// $strSQL = str_replace('~ACCOUNTID~', ff($strAccountID), $strSQL);
		// dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
		
		// exposeEntityData($objConn_a, 'SYSTEMFORM', 'ACCOUNT', $strAccountID, $strJSONData);
		
		// dbEndTrans($objConn_a, __FUNCTION__);
	// }
	// dbCloseRecordset($objResultLoop);
}
