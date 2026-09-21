<?php

// checksum a user
function userChecksumCalculate($objConn_a, $strClientID_a, $strUserID_a)
{
	$strTableNameUser = getTableNameEntity("user", false);

    //dbBeginTrans($objConn_a, __FUNCTION__);

    $strResult = '';
	
    $strSQL = "select id, client_id, description, email_address, login, password, is_defined, is_enabled, modifyuser, modifydatetime from ~TABLENAMEUSER~ where client_id = ~CLIENTID~ and id = ~USERID~";
	$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
    $strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    if ($arrRow = dbReadRecord($objResult)) {
        $strUser = $arrRow['id'] . $arrRow['client_id'] . $arrRow['description'] . $arrRow['email_address'] . $arrRow['login'] . $arrRow['password'] . $arrRow['is_defined'] . $arrRow['is_enabled'] . $arrRow['modifyuser'] . $arrRow['modifydatetime'];
//debug("X:" . $strUser);
        $strResult = md5($strUser);
    }
    dbCloseRecordset($objResult);

    //dbEndTrans($objConn_a, __FUNCTION__);

    return $strResult;
}
