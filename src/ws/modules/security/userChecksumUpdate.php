<?php

// checksum a user
function userChecksumUpdate($objConn_a, $strClientID_a, $strUserID_a)
{
	$strTableNameUser = getTableNameEntity("user", false);
		
    $blnResult = true;

    if (dependencies('security/userChecksumCalculate')) {
        dbBeginTrans($objConn_a, __FUNCTION__);
        $strChecksum = userChecksumCalculate($objConn_a, $strClientID_a, $strUserID_a);

        // don't include modifydatetime and modifyuser in here as they are actually part of the checksum
        $strSQL = "update ~TABLENAMEUSER~ set checksum = '~CHECKSUM~' where client_id = ~CLIENTID~ and id = ~USERID~";
		$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
        $strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
        $strSQL = str_replace('~CHECKSUM~', ff($strChecksum), $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

        $blnResult = dbEndTrans($objConn_a, __FUNCTION__);
    }

    return $blnResult;
}
