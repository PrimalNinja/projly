<?php

// adds a person to a relevant persongroup based on the date and the product code / name
function personGroupAddPersonAndEMail($objConn_a, $strClientID_a, $strPersonGroupID_a, $strToClientID_a, $strPersonID_a, $strEmailAddress_a, $blnRenewal_a, $blnExpiry_a, $strEmailTemplateCode_a, $strProductType_a, $strProcessProductID_a, $strProductCode_a, $strProductDescription_a, $strExpiryDate_a)
{ 
	$strTableNameClientProduct = getTableNameEntity("clientproduct", false);
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNamePerson = getTableNameEntity("person", false);
	$strTableNamePersonExtension = getTableNameEntityExtension("person");
	//$strTableNamePersonGroup = getTableNameEntity("persongroup", false);
	$strTableNamePersonGroupPerson = getTableNameEntity("persongroup_person", false);
	$strTableNamePersonProduct = getTableNameEntity("personproduct", false);
	
	$strDateToday = getDateStringOut(getDateOnly());		
	
	$blnResult = true;
	
    if (dependencies('batch/processes/personGroupAddPerson'))
    {
		$strLogin = $_SESSION['server_loggedin_user'];
		
		$strExpiryDate = $strExpiryDate_a;
		if (strlen($strExpiryDate) > 0)
		{
			$strExpiryDate = getDateReportOut($strExpiryDate);
		}
		
		dbBeginTrans($objConn_a, __FUNCTION__);

		// get the person details and add them to the group
		// $strSQL = "select 
		// 		   pe.befb634c_50f2_405a_b0a4_eb7461ec4780_fullname fullname, 
		// 		   pe.befb634c_50f2_405a_b0a4_eb7461ec4780_addressline1 addressline1, 
		// 		   pe.befb634c_50f2_405a_b0a4_eb7461ec4780_addressline2 addressline2, 
		// 		   pe.befb634c_50f2_405a_b0a4_eb7461ec4780_suburb suburb, 
		// 		   pe.befb634c_50f2_405a_b0a4_eb7461ec4780_postcode postcode, 
		// 		   pe.befb634c_50f2_405a_b0a4_eb7461ec4780_state state,
		// 		   pe.befb634c_50f2_405a_b0a4_eb7461ec4780_country country,
		// 		   pe.befb634c_50f2_405a_b0a4_eb7461ec4780_postaladdressline1 postaladdressline1, 
		// 		   pe.befb634c_50f2_405a_b0a4_eb7461ec4780_postaladdressline2 postaladdressline2, 
		// 		   pe.befb634c_50f2_405a_b0a4_eb7461ec4780_postalsuburb postalsuburb, 
		// 		   pe.befb634c_50f2_405a_b0a4_eb7461ec4780_postalpostcode postalpostcode, 
		// 		   pe.befb634c_50f2_405a_b0a4_eb7461ec4780_postalstate postalstate,
		// 		   pe.befb634c_50f2_405a_b0a4_eb7461ec4780_postalcountry postalcountry,
		// 		   pe.befb634c_50f2_405a_b0a4_eb7461ec4780_dateofbirth dateofbirth
		// 		   from ~TABLENAMEPERSON~ p, ~TABLENAMEPERSONEXTENSION~ pe
		// 		   where pe.id = p.id and p.id = ~PERSONID~";
		// $strSQL = str_replace('~TABLENAMEPERSON~', ff($strTableNamePerson), $strSQL);
		// $strSQL = str_replace('~TABLENAMEPERSONEXTENSION~', ff($strTableNamePersonExtension), $strSQL);
		// $strSQL = str_replace('~PERSONID~', ff($strPersonID_a), $strSQL);

		$strSQL = "select 
				   ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_fullname fullname, 
				   ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_addressline1 addressline1, 
				   ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_addressline2 addressline2, 
				   ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_suburb suburb, 
				   ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_postcode postcode, 
				   ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_state state,
				   ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_country country,
				   ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_postaladdressline1 postaladdressline1, 
				   ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_postaladdressline2 postaladdressline2, 
				   ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_postalsuburb postalsuburb, 
				   ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_postalpostcode postalpostcode, 
				   ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_postalstate postalstate,
				   ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_postalcountry postalcountry
				   from ~TABLENAMEACCOUNT~
				   where client_id = ~TOCLIENTID~";
		$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
		$strSQL = str_replace('~TOCLIENTID~', ff($strToClientID_a), $strSQL);

		$objResultPerson = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);

		if ($arrRowPerson = dbReadRecord($objResultPerson)) 
		{
			$strPersonFullName = $arrRowPerson['fullname'];
			$strPersonAddressLine1 = $arrRowPerson['addressline1'];
			$strPersonAddressLine2 = $arrRowPerson['addressline2'];
			$strPersonSuburb = $arrRowPerson['suburb'];
			$strPersonPostcode = $arrRowPerson['postcode'];
			$strPersonState = $arrRowPerson['state'];
			$strPersonCountry = $arrRowPerson['country'];
			$strPersonPostalAddressLine1 = $arrRowPerson['postaladdressline1'];
			$strPersonPostalAddressLine2 = $arrRowPerson['postaladdressline2'];
			$strPersonPostalSuburb = $arrRowPerson['postalsuburb'];
			$strPersonPostalPostcode = $arrRowPerson['postalpostcode'];
			$strPersonPostalState = $arrRowPerson['postalstate'];
			$strPersonPostalCountry = $arrRowPerson['postalcountry'];
			
			$strPersonGroupPersonID = personGroupAddPerson($objConn_a, $strClientID_a, $strPersonGroupID_a, $strPersonID_a, $strPersonFullName, $strEmailAddress_a);

			// send email
			if ((strlen($strPersonGroupPersonID) > 0) && (strlen($strEmailAddress_a) > 0) && filter_var($strEmailAddress_a, FILTER_VALIDATE_EMAIL))
			{ 
				$strTemplateClientID = getSystemClientID($objConn_a); 
				$strToClientID = $strToClientID_a;
				$strToUserID = "";	// leaving this blank will send to the client's main user
				
				$arrPlaceholders = array(
					'DATE' => $strDateToday,
					'APPLICATIONNAME' => APPNAME,
					'FULLNAME' => $strPersonFullName,
					// 'DATEOFBIRTH' => $strDateOfBirth,
					'ADDRESSLINE1' => $strPersonAddressLine1,
					'ADDRESSLINE2' => $strPersonAddressLine2,
					'SUBURB' => $strPersonSuburb,
					'STATE' => $strPersonState,
					'POSTCODE' => $strPersonPostcode,
					'COUNTRY' => $strPersonCountry,
					'POSTALADDRESSLINE1' => $strPersonPostalAddressLine1,
					'POSTALADDRESSLINE2' => $strPersonPostalAddressLine2,
					'POSTALSUBURB' => $strPersonPostalSuburb,
					'POSTALSTATE' => $strPersonPostalState,
					'POSTALPOSTCODE' => $strPersonPostalPostcode,
					'POSTALCOUNTRY' => $strPersonPostalCountry,
					'CLIENTCODE' => "",
					'CLIENTNAME' => "",
					'EMAILADDRESS' => "",
					'PASSWORD' => "",
					'BUSINESSNAME' => "",
					'BUSINESSPHONE' => "",
					'BUSINESSEMAILADDRESS' => "",
					'BUSINESSWEBSITE' => "",
					'RESETPASSWORDLINK' =>  "",
					'EXPIRYDATE' => $strExpiryDate,
					'SUBJECT' => "",
					'MESSAGE' => "",
					'FORMCOUNT' => "",
					'FORMCOUNTS' => "",
					'FORMERRORCOUNT' => "",
					'USERCOUNT' => "",
					'USERERRORCOUNT' => ""
				);

				sendMessageTemplatedMail($objConn_a, GENERAL_EMAIL_DOMAIN, GENERAL_EMAIL_ADDRESS, GENERAL_EMAIL_FROMNAME, $strEmailAddress_a, $strToClientID, $strToUserID, $strTemplateClientID, $strEmailTemplateCode_a, $arrPlaceholders, "", "", false);
				
				$strSQL = "select jsondata returnvalue from ~TABLENAMEPERSONGROUPPERSON~ where id = ~PERSONGROUPPERSONID~";
				$strSQL = str_replace('~TABLENAMEPERSONGROUPPERSON~', ff($strTableNamePersonGroupPerson), $strSQL);
				$strSQL = str_replace('~PERSONGROUPPERSONID~', ff($strPersonGroupPersonID), $strSQL);
				$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

				$arrJSONData = json_decode($strJSONData, true);

				$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g9c5f214c-6f8e-4813-8647-b2845ec1cf20", "PROCESSED", "Y");

				$strJSONData = json_encode($arrJSONData);

				$strSQL = "update ~TABLENAMEPERSONGROUPPERSON~ set jsondata = '~JSONDATA~' where id = ~PERSONGROUPPERSONID~";
				$strSQL = str_replace('~TABLENAMEPERSONGROUPPERSON~', ff($strTableNamePersonGroupPerson), $strSQL);            
				$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
				$strSQL = str_replace('~PERSONGROUPPERSONID~', ff($strPersonGroupPersonID), $strSQL);
				dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

				exposeEntityData($objConn_a, 'SYSTEMFORM', 'PERSONGROUP_PERSON', $strPersonGroupPersonID, $strJSONData);
			}

			// send emails to people with email address
			$strEmailDateField = "";
			if ($blnRenewal_a)
			{ 
				$strEmailDateField = 'emailrenewaldate';
			}
			else if ($blnExpiry_a)
			{ 
				$strEmailDateField = 'emailexpireddate';
			}
			
			if (strlen($strEmailDateField) > 0)
			{
				if ($strProductType_a == 'clientproduct')
				{
					$strSQL = "update ~TABLENAMECLIENTPRODUCT~ set ~EMAILDATEFIELD~ = '~DATETIME~' where id = ~CLIENTPRODUCTID~";
					$strSQL = str_replace('~TABLENAMECLIENTPRODUCT~', ff($strTableNameClientProduct), $strSQL);            
					$strSQL = str_replace('~EMAILDATEFIELD~', $strEmailDateField, $strSQL);
					$strSQL = str_replace('~DATETIME~', getDateTime(), $strSQL);
					$strSQL = str_replace('~CLIENTPRODUCTID~', ff($strProcessProductID_a), $strSQL);
					dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				}
				// else	// must be personproduct
				// {
				// 	$strSQL = "update ~TABLENAMEPERSONPRODUCT~ set ~EMAILDATEFIELD~ = '~DATETIME~' where id = ~PERSONPRODUCTID~";
				// 	$strSQL = str_replace('~TABLENAMEPERSONPRODUCT~', ff($strTableNamePersonProduct), $strSQL);            
				// 	$strSQL = str_replace('~EMAILDATEFIELD~', ff($strEmailDateField), $strSQL);
				// 	$strSQL = str_replace('~DATETIME~', getDateTime(), $strSQL);
				// 	$strSQL = str_replace('~PERSONPRODUCTID~', ff($strProcessProductID_a), $strSQL);
				// 	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
				// }
			}
		}

		dbCloseRecordset($objResultPerson);

		$blnResult = dbEndTrans($objConn_a, __FUNCTION__);
	}
	
	return $blnResult;
}
