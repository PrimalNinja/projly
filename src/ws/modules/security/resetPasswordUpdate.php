<?php

// update a password
function resetPasswordUpdate($objConn_a, $strResetPasswordID_a, $strIsConfirmed_a, $strIPAddress_a)
{
	$strTableNameResetPassword = getTableNameEntity("resetpassword", false);
	
	dbBeginTrans($objConn_a, __FUNCTION__);

	$strSQL = "select jsondata returnvalue from ~TABLENAMERESETPASSWORD~ where id = ~RESETPASSWORDID~";
	$strSQL = str_replace('~TABLENAMERESETPASSWORD~', ff($strTableNameResetPassword), $strSQL);	
	$strSQL = str_replace('~RESETPASSWORDID~', ff($strResetPasswordID_a), $strSQL);
	$strJSONData = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
			
	$arrJSONData = json_decode($strJSONData, true);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g14b59919-5bef-40e8-8eb5-a25b870230aa", "IPADDRESS", $strIPAddress_a);
	$arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, "g14b59919-5bef-40e8-8eb5-a25b870230aa", "ISCONFIRMED", $strIsConfirmed_a);
	$strJSONData = json_encode($arrJSONData);

	$strSQL = "update ~TABLENAMERESETPASSWORD~ set is_confirmed = '~ISCONFIRMED~', jsondata = '~JSONDATA~' where id = ~RESETPASSWORDID~";
	$strSQL = str_replace('~TABLENAMERESETPASSWORD~', ff($strTableNameResetPassword), $strSQL);	
	$strSQL = str_replace('~RESETPASSWORDID~', ff($strResetPasswordID_a), $strSQL);
	$strSQL = str_replace('~ISCONFIRMED~', ff($strIsConfirmed_a), $strSQL);
	$strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

	exposeEntityData($objConn_a, 'SYSTEMFORM', 'RESETPASSWORD', $strResetPasswordID_a, $strJSONData);

	return dbEndTrans($objConn_a, __FUNCTION__);
}
