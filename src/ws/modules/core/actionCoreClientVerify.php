<?php

// check if a client code is already in use
function actionCoreClientVerify($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a)
{
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameRegistration = getTableNameEntity("registration", false);
	
    $arrResult = array();
    $intClientCount = 0;
	
	// permission check
	if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}
	
    // parameters
    $strClientCode = getJSONParameter($arrParameters_a, 'clientcode');
    $strLogin = getJSONParameter($arrParameters_a, 'login');

    // fetch
    $strSQL = "select count(*) returnvalue from ~TABLENAMECLIENT~ where code = '~CLIENTCODE~'";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
    $strSQL = str_replace('~CLIENTCODE~', ff($strClientCode), $strSQL);
	$intClientCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

    $strSQL = "select count(*) returnvalue from ~TABLENAMEREGISTRATION~ where ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_clientcode = '~CLIENTCODE~' or ffdcbf4797_ab6f_4cd7_bf5b_a74097e8e303_accountemailaddress = '~EMAILADDRESS~'";
	$strSQL = str_replace('~TABLENAMEREGISTRATION~', ff($strTableNameRegistration), $strSQL);	
    $strSQL = str_replace('~CLIENTCODE~', ff($strClientCode), $strSQL);
    $strSQL = str_replace('~EMAILADDRESS~', ff($strLogin), $strSQL);
	$intClientCount += dbReadValue($objConn_a, $strSQL, __FUNCTION__);

	if ($intClientCount == 0)
	{
		$strResult = createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
	} 
	else 
	{
		$strResult = createJSONResponse($strDataID_a, RESPONSE_ERRORMESSAGE, 'Account code or email address already exists.', array());
	}

    return $strResult;
}
