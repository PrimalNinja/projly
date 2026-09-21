<?php

// add an account
// accounts have to be added before the client because the client cannot have a client_id of client.id (itself) when inserting
// we are therefore, using the account.id as the client.id (both must be the same), but first before we can know the client id
// add the account, then the client (with the account-allocated client id), then update the account again
function accountAddFromRegistrationForm($objConn_a, $strClientCode_a, $strDescription_a, $strEnabled_a, $strEmailAddress_a, $strPhoneNumber_a, $strRegistrationTypeID_a, $arrRegistrationFormJSON_a)
{
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNameRegistrationType = getTableNameEntity("registrationtype", false);
	$strTableNameState = getTableNameEntity("state", false);
	
    $strAccountID = "";
	$strStateDescription = "";
	$strStateID = "";
	
	dbBeginTrans($objConn_a, __FUNCTION__);

	$strLogin = $_SESSION['server_loggedin_user'];
	
	$strRegistrationType = formValueGetBySectionCodeFieldCode($arrRegistrationFormJSON_a, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "REGISTRATIONTYPEDESC");

	// primary contact details
	$strFullName = formValueGetBySectionCodeFieldCode($arrRegistrationFormJSON_a, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "FULLNAME");
	$strAddressLine1 = formValueGetBySectionCodeFieldCode($arrRegistrationFormJSON_a, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ADDRESSLINE1");
	$strAddressLine2 = formValueGetBySectionCodeFieldCode($arrRegistrationFormJSON_a, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ADDRESSLINE2");
	$strSuburb = formValueGetBySectionCodeFieldCode($arrRegistrationFormJSON_a, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "SUBURB");
	//$strState = formDescriptionGetBySectionCodeFieldCode($arrRegistrationFormJSON_a, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "STATE");
	$arrJSONStateField = formFieldGetBySectionCodeFieldCode($arrRegistrationFormJSON_a, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "STATE");
	if (count($arrJSONStateField) > 0)
	{
		$strStateID = $arrJSONStateField['p_value'];
		$strStateDescription = $arrJSONStateField['p_valuedescription'];
	}
	$strPostcode = formValueGetBySectionCodeFieldCode($arrRegistrationFormJSON_a, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "POSTCODE");
	$strCountry = formValueGetBySectionCodeFieldCode($arrRegistrationFormJSON_a, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "COUNTRY");
	$strPhoneNumber = formValueGetBySectionCodeFieldCode($arrRegistrationFormJSON_a, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "PHONENUMBER");
	$strEmailAddress = formValueGetBySectionCodeFieldCode($arrRegistrationFormJSON_a, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "EMAILADDRESS");
	

	// other details
	//$strDateOfBirth = formValueGetBySectionCodeFieldCode($arrRegistrationFormJSON_a, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "DATEOFBIRTH");

	$arrJSONData = formTemplateGetFromDBByEntityCode($objConn_a, "ACCOUNT");
		
	// get the entity ids
	$strEntityID = getEntityID($objConn_a, "systemform");
	$strDataEntityID = getEntityID($objConn_a, "account");

	// transfer data from Registration form to the Account form
	$arrJSONData = formTransferSectionValues($arrRegistrationFormJSON_a, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", $arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437");

	// account details
	$strDescription = $strDescription_a;
	if (strlen($strDescription) == 0)
	{
		$strDescription = formValueGetBySectionCodeFieldCode($arrRegistrationFormJSON_a, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ACCOUNTNAME");
	}
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "ACCOUNTNAME", $strDescription);
	
	$strClientCode = $strClientCode_a;
	if (strlen($strClientCode) == 0)
	{
		$strClientCode = formValueGetBySectionCodeFieldCode($arrRegistrationFormJSON_a, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "CLIENTCODE");
	}
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "CLIENTCODE", $strClientCode);

	$strEmailAddress = $strEmailAddress_a;
	if (strlen($strEmailAddress) == 0)
	{
		$strEmailAddress = formValueGetBySectionCodeFieldCode($arrRegistrationFormJSON_a, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ACCOUNTEMAILADDRESS");
	}
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "ACCOUNTEMAILADDRESS", $strEmailAddress);
	
	$strPhoneNumber = $strPhoneNumber_a;
	if (strlen($strPhoneNumber) == 0)
	{
		$strPhoneNumber = formValueGetBySectionCodeFieldCode($arrRegistrationFormJSON_a, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ACCOUNTPHONENUMBER");
	}
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "ACCOUNTPHONENUMBER", $strPhoneNumber);
	
	// primary contact suburb picker on jsondata that will be transferred
	$strSuburbPopulateID = getSuburbIDBySuburbFields($objConn_a, $strSuburb, $strPostcode, $strStateDescription, $strCountry);
	$strSuburbPopulateDescription = $strSuburb . ' | ' . $strStateDescription . ' | ' . $strPostcode;
	$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "SUBURBPOPULATE", $strSuburbPopulateID, $strSuburbPopulateDescription);
	
	// postal details
	$strPostalAddresseeDescription = $strFullName;
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALADDRESSEE", $strPostalAddresseeDescription);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALADDRESSLINE1", $strAddressLine1);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALADDRESSLINE2", $strAddressLine2);
	$strPostalSuburbPopulateDescription = $strSuburb . ' | ' . $strStateDescription . ' | ' . $strPostcode;
	$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALSUBURBPOPULATE", $strSuburbPopulateID, $strPostalSuburbPopulateDescription);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALSUBURB", $strSuburb);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALPOSTCODE", $strPostcode);
	if (strlen($strStateID) > 0)
	{
		$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALSTATE", $strStateID, $strStateDescription);
	}
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "POSTALCOUNTRY", $strCountry);
	
	// billing details
	$strBillingBillToDescription = $strFullName;
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGBILLTO", $strBillingBillToDescription);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGADDRESSLINE1", $strAddressLine1);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGADDRESSLINE2", $strAddressLine2);
	$strBillingSuburbPopulateDescription = $strSuburb . ' | ' . $strStateDescription . ' | ' . $strPostcode;
	$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGSUBURBPOPULATE", $strSuburbPopulateID, $strBillingSuburbPopulateDescription);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGSUBURB", $strSuburb);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGPOSTCODE", $strPostcode);
	if (strlen($strStateID) > 0)
	{
		$arrJSONData = formValueAndDescriptionUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGSTATE", $strStateID, $strStateDescription);
	}
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "BILLINGCOUNTRY", $strCountry);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "ACCOUNTTYPE", $strRegistrationType);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ff7ae74c1e-baff-4966-8b2e-b21e40eb4437", "CREATEDATE", getDateTime());

	$strJSONData = json_encode($arrJSONData);

	$strSQL =
		"
insert into ~TABLENAMEACCOUNT~ (client_id, entity_id, dataentity_id, code, description, is_enabled, jsondata, data_client_id, modifyuser, modifydatetime, user_id, avatar_document_id, registrationtype_id, viewcount, offencecount)
values (null, ~ENTITYID~, ~DATAENTITYID~, '~CODE~', '~DESCRIPTION~', '~ENABLED~', '~JSONDATA~', null, '~MODIFYUSER~', '~MODIFYDATETIME~', null, null, ~REGISTRATIONTYPEID~, 0, 0)
";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	//$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~ENTITYID~', ff($strEntityID), $strSQL);
	$strSQL = str_replace('~DATAENTITYID~', ff($strDataEntityID), $strSQL);
	$strSQL = str_replace('~CODE~', ff($strClientCode), $strSQL);
	$strSQL = str_replace('~DESCRIPTION~', ff($strDescription), $strSQL);
	$strSQL = str_replace('~ENABLED~', ff($strEnabled_a), $strSQL);
	//$strSQL = str_replace('~DATACLIENTID~', ff($strClientID_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	$strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
	$strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);
	//$strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
	$strSQL = str_replace('~REGISTRATIONTYPEID~', ff($strRegistrationTypeID_a), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
	$strAccountID = dbLastInsertID($objConn_a);
	
	exposeEntityData($objConn_a, 'SYSTEMFORM', 'ACCOUNT', $strAccountID, $strJSONData);

	dbEndTrans($objConn_a, __FUNCTION__);

    return $strAccountID;
}
