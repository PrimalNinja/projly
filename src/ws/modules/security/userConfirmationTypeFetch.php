<?php

function userConfirmationTypeFetch($objConn_a, $strToken_a)
{
    $strResult = "";
    $strToken = $strToken_a;

    $strTableNameResetPassword = getTableNameEntity("resetpassword", false);
    $strTableNameRegistration = getTableNameEntity("registration", false);


    $strSQL = "select count(*) returnvalue from ~TABLENAMEREGISTRATION~ where is_confirmed = 'N' and token = '~TOKEN~'";
    $strSQL = str_replace('~TABLENAMEREGISTRATION~', ff($strTableNameRegistration), $strSQL);
    $strSQL = str_replace('~TOKEN~', ff($strToken), $strSQL);
    $intCount = intval(dbReadValue($objConn_a, $strSQL, __FUNCTION__));

    if ($intCount > 0)
    {
        $strResult = 'CONFIRM_REGISTRATION';
    }
    else
    {
        $strSQL = "select count(*) returnvalue from ~TABLENAMERESETPASSWORD~ where is_confirmed = 'N' and token = '~TOKEN~' ";
        $strSQL = str_replace('~TABLENAMERESETPASSWORD~', ff($strTableNameResetPassword), $strSQL);
        $strSQL = str_replace('~TOKEN~', ff($strToken), $strSQL);
        $intCount = intval(dbReadValue($objConn_a, $strSQL, __FUNCTION__));

        $strResult = 'RESET_PASSWORD';
    }

    return $strResult;
}
