<?php

// update a registrant
function registerUpdate($objConn_a, $strRegistrationID_a, $strIsConfirmed_a, $strToken_a, $strUserAgent_a, $strIPAddress_a)
{
	$strTableNameRegistration = getTableNameEntity("registration", false);
	
	dbBeginTrans($objConn_a, __FUNCTION__);

	// update the token
	$strSQL = "select jsondata returnvalue from ~TABLENAMEREGISTRATION~ where id = ~REGISTRATIONID~";
	$strSQL = str_replace('~TABLENAMEREGISTRATION~', ff($strTableNameRegistration), $strSQL);	
	$strSQL = str_replace('~REGISTRATIONID~', ff($strRegistrationID_a), $strSQL);
	$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			
	$arrJSONData = json_decode($strJSONData, true);

	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "ISCONFIRMED", $strIsConfirmed_a);
	if (strlen($strUserAgent_a) > 0)
	{
		$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "USERAGENT", $strUserAgent_a);
	}
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "ffdcbf4797-ab6f-4cd7-bf5b-a74097e8e303", "IPADDRESS", $strIPAddress_a);
	$strJSONData = json_encode($arrJSONData);

	$strSQL = "update ~TABLENAMEREGISTRATION~ set token = '~TOKEN~', is_confirmed = '~ISCONFIRMED~', jsondata = '~JSONDATA~' where id = ~REGISTRATIONID~";
	$strSQL = str_replace('~TABLENAMEREGISTRATION~', ff($strTableNameRegistration), $strSQL);	
	$strSQL = str_replace('~REGISTRATIONID~', ff($strRegistrationID_a), $strSQL);
	$strSQL = str_replace('~ISCONFIRMED~', ff($strIsConfirmed_a), $strSQL);
	$strSQL = str_replace('~TOKEN~', ff($strToken_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	exposeEntityData($objConn_a, 'SYSTEMFORM', 'REGISTRATION', $strRegistrationID_a, $strJSONData);

	return dbEndTrans($objConn_a, __FUNCTION__);
}
