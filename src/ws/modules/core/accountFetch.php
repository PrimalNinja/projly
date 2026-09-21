<?php

// fetch an account
function accountFetch($objConn_a, $strSecurityToken_a, $strDataID_a, $strClientID_a, $strAccountID_a)
{
	$strTableNameAccount = getTableNameEntity("account", false);
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameOperatingLocality = getTableNameEntity("operatinglocality", false);
	$strTableNameRegistrationType = getTableNameEntity("registrationtype", false);
	$strTableNameUser = getTableNameEntity("user", false);
	
    $arrResult = array();
	
    $strCurrentClientID = $_SESSION['server_loggedin_clientid'];
	
	$strIsOwned = "N";
	if ($strCurrentClientID == $strClientID_a)
	{
		$strIsOwned = "Y";
	}

    // fetch
    $strSQL =
        "
select c.code clientcode, u.id userid, u.login login, a.id, a.ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_emailaddress email_address, a.ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_accountname accountname, a.description, a.avatar_document_id, a.registrationtype_id, rt.description registrationtype, a.ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_businessname businessname, a.ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_abn abn, a.ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_addressline1 address1, a.ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_addressline2 address2, a.ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_suburb suburb, a.ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_state state, a.ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_postcode postcode, a.ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_country country, a.viewcount, a.offencecount, a.ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_fullname fullname, a.ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_phonenumber business_phone, a.ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_emailaddress business_email, a.is_enabled, a.ff7ae74c1e_baff_4966_8b2e_b21e40eb4437_createdate createdate, a.modifyuser, a.modifydatetime
from ~TABLENAMEACCOUNT~ a, ~TABLENAMECLIENT~ c, ~TABLENAMEUSER~ u, ~TABLENAMEREGISTRATIONTYPE~ rt
where c.id = ~CLIENTID~ and a.client_id = c.id and a.user_id = u.id and a.id = ~ACCOUNTID~ and a.registrationtype_id = rt.id
";
	$strSQL = str_replace('~TABLENAMEACCOUNT~', ff($strTableNameAccount), $strSQL);
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEREGISTRATIONTYPE~', ff($strTableNameRegistrationType), $strSQL);	
	$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
    $strSQL = str_replace('~ACCOUNTID~', ff($strAccountID_a), $strSQL);

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    
    if ($arrRow = dbReadRecord($objResult)) {
        $arrResult[] = array(
            "id" => secureEntityValue('ACCOUNT', $arrRow['id']),
            "user_id" => secureEntityValue('USER', $arrRow['userid']),
            "clientcode" => $arrRow['clientcode'],
            "login" => $arrRow['login'],
            "email_address" => $arrRow['email_address'],
            "accountname" => $arrRow['accountname'],
            "description" => $arrRow['description'],
            "avatar_document_id" => secureEntityValue('DOCUMENT', $arrRow['avatar_document_id']),
            "registrationtype_id" => secureEntityValue('REGISTRATIONTYPE', $arrRow['registrationtype_id']),
            "registrationtype" => $arrRow['registrationtype'],
            "businessname" => $arrRow['businessname'],
            "abn" => $arrRow['abn'],
            "address1" => $arrRow['address1'],
            "address2" => $arrRow['address2'],
            "suburb" => $arrRow['suburb'],
            "state" => $arrRow['state'],
            "postcode" => $arrRow['postcode'],
            "country" => $arrRow['country'],
            "viewcount" => $arrRow['viewcount'],
            "offencecount" => $arrRow['offencecount'],
            "business_contact" => $arrRow['fullname'],
            "business_phone" => $arrRow['business_phone'],
            "business_email" => $arrRow['business_email'],
            "is_privacy" => 'Y',
            "is_agreement" => 'Y',
            "is_enabled" => $arrRow['is_enabled'],
			"is_owned" => $strIsOwned
        );
    }
    dbCloseRecordset($objResult);

    return $arrResult;
}
