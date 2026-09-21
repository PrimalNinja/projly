<?php

function registerVerify($objConn_a, $strRegistrationID_a, $strIsVerified_a, $strToken_a)
{
	$strTableNameRegistration = getTableNameEntity("registration", false);
	
	dbBeginTrans($objConn_a, __FUNCTION__);

	$strSQL = "update ~TABLENAMEREGISTRATION~ set is_verified = '~ISVERIFIED~', token = '~TOKEN~' where id = ~REGISTRATIONID~";
	$strSQL = str_replace('~TABLENAMEREGISTRATION~', ff($strTableNameRegistration), $strSQL);	
	$strSQL = str_replace('~REGISTRATIONID~', ff($strRegistrationID_a), $strSQL);
	$strSQL = str_replace('~ISVERIFIED~', ff($strIsVerified_a), $strSQL);
    $strSQL = str_replace('~TOKEN~', ff($strToken_a), $strSQL);

    dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
    
	return dbEndTrans($objConn_a, __FUNCTION__);
}