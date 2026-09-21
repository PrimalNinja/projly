<?php

// change password
// note: users from the system area can update any area's passwords
function userPasswordUpdate($objConn_a, $strClientID_a, $strUserID_a, $strPassword_a)
{
	$strTableNameUser = getTableNameEntity("user", false);
	
    $blnResult = true;

    if (dependencies('security/userChecksumUpdate')) {
        dbBeginTrans($objConn_a, __FUNCTION__);

        $blnSystemArea = $_SESSION['server_loggedin_system'];
        $strLogin = $_SESSION['server_loggedin_user'];
		
        //$strLoginToChange = '';
        $strClientID = '';

        if ($blnSystemArea) 
		{
            // sa can change any clients so we need to find out the actual client id (not the one we have which is sa's)
            $strSQL = "select client_id returnvalue from ~TABLENAMEUSER~ where id = ~USERID~";
			$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
            $strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
            $strClientID = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

            //$strSQL = "select login returnvalue from ~TABLENAMEUSER~ where id = ~USERID~";
			//$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
            //$strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
            //$strLoginToChange = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

            $strSQL = "update ~TABLENAMEUSER~ set password = '~PASSWORD~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where id = ~USERID~";
        } 
		else 
		{
            // non-sa can only change their own clients
            $strClientID = $strClientID_a;

            //$strSQL = "select login returnvalue from ~TABLENAMEUSER~ where client_id = ~CLIENTID~ and id = ~USERID~";
            //$strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
            //$strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
            //$strLoginToChange = dbReadValue($objConn_a, $strSQL, __FUNCTION__);

            $strSQL = "update ~TABLENAMEUSER~ set password = '~PASSWORD~', modifyuser = '~MODIFYUSER~', modifydatetime = '~MODIFYDATETIME~' where client_id = ~CLIENTID~ and id = ~USERID~";
        }
		$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID_a), $strSQL);
        $strSQL = str_replace('~USERID~', ff($strUserID_a), $strSQL);
        $strSQL = str_replace('~PASSWORD~', ff(encryptPassword1Way($strClientID, $strUserID_a, strtolower($strPassword_a))), $strSQL);
        $strSQL = str_replace('~MODIFYUSER~', ff($strLogin), $strSQL);
        $strSQL = str_replace('~MODIFYDATETIME~', getDateTime(), $strSQL);

        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);

        userChecksumUpdate($objConn_a, $strClientID, $strUserID_a);

		//exposeEntityData($objConn_a, 'SYSTEMFORM', 'USER', $strUserID_a, $strJSONData);

        $blnResult = dbEndTrans($objConn_a, __FUNCTION__);
    }

    return $blnResult;
}
