<?php

// checksum a user
function userChecksumCalculateByLogin($objConn_a, $strClientID_a, $strLogin_a)
{
	$strTableNameUser = getTableNameEntity("user", false);

    //dbBeginTrans($objConn_a, __FUNCTION__);

    $strResult = '';
	
    $strSQL = "select id, client_id, description, email_address, login, password, is_defined, is_enabled, modifyuser, modifydatetime from ~TABLENAMEUSER~ where client_id = ~CLIENTID~ and login = '~LOGIN~'";
	$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
    $strSQL = str_replace('~LOGIN~', ff($strLogin_a), $strSQL);

    $objResult = dbOpenRecordset($objConn_a, $strSQL, __FUNCTION__);
    if ($arrRow = dbReadRecord($objResult)) {
        $strUser = $arrRow['id'] . $arrRow['client_id'] . $arrRow['description'] . $arrRow['email_address'] . $arrRow['login'] . $arrRow['password'] . $arrRow['is_defined'] . $arrRow['is_enabled'] . $arrRow['modifyuser'] . $arrRow['modifydatetime'];
//debug($strUser);
        $strResult = md5($strUser);
//debug($strResult);
    }
    dbCloseRecordset($objResult);

    //dbEndTrans($objConn_a, __FUNCTION__);

    return $strResult;
}
