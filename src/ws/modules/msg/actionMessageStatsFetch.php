<?php

// inbox status used by taskbar badges
function actionMessageStatsFetch($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) { 
	$strTableNameClient = getTableNameEntity("client", false);
	$strTableNameUser = getTableNameEntity("user", false);
    $strTableNameInternalMessage = getTableNameEntity("internalmessage", false);
   
    $arrResult = array();
        
    // permission check
    if (!hasPermission($objConn_a, 'NOCHECK', __FUNCTION__, true)) {return false;}
   
    // parameters

    // initialisations
    $strClientID = $_SESSION['server_loggedin_clientid'];
    $strUserID = $_SESSION['server_loggedin_userid'];
	
    // get table count    
    $strSQL =
    "
select count(*) returnvalue
from (
    select distinct m.id, m.fromclient_id, m.fromuser_id, m.toclient_id, m.touser_id, fc.description from_clientname, fu.description from_username, tc.description to_clientname, tu.description to_username, m.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_subject subject
    from ~TABLENAMEINTERNALMESSAGE~ m, ~TABLENAMEUSER~ tu, ~TABLENAMECLIENT~ tc, ~TABLENAMEUSER~ fu, ~TABLENAMECLIENT~ fc
    where m.touser_id = tu.id and m.toclient_id = tu.client_id and m.toclient_id = tc.id and m.fromuser_id = fu.id and m.fromclient_id = fu.client_id and m.fromclient_id = fc.id and m.fromuser_id = ~USERID~ and m.fromclient_id = ~CLIENTID~ and m.client_id = ~CLIENTID~
) temp";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
    $strSQL = str_replace('~TABLENAMEINTERNALMESSAGE~', ff($strTableNameInternalMessage), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
    $strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
    
    $intRecordCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
    
// get unread count    
    $strSQL =
    "
select count(*) returnvalue
from (
    select distinct m.id, m.fromclient_id, m.fromuser_id, m.toclient_id, m.touser_id, fc.description from_clientname, fu.description from_username, tc.description to_clientname, tu.description to_username, m.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_subject subject
    from ~TABLENAMEINTERNALMESSAGE~ m, ~TABLENAMEUSER~ tu, ~TABLENAMECLIENT~ tc, ~TABLENAMEUSER~ fu, ~TABLENAMECLIENT~ fc
    where m.touser_id = tu.id and m.toclient_id = tu.client_id and m.toclient_id = tc.id and m.fromuser_id = fu.id and m.fromclient_id = fu.client_id and m.fromclient_id = fc.id and m.touser_id = ~USERID~ and m.toclient_id = ~CLIENTID~ and m.client_id = ~CLIENTID~
    and m.g8938a0d4_8efc_41ed_a420_4af7b808b3f0_isunread='Y') temp";
	$strSQL = str_replace('~TABLENAMECLIENT~', ff($strTableNameClient), $strSQL);
	$strSQL = str_replace('~TABLENAMEUSER~', ff($strTableNameUser), $strSQL);
    $strSQL = str_replace('~TABLENAMEINTERNALMESSAGE~', ff($strTableNameInternalMessage), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
    $strSQL = str_replace('~USERID~', ff($strUserID), $strSQL);
    
    $intUnreadCount = dbReadValue($objConn_a, $strSQL, __FUNCTION__);
    
	$arrResult[] = array(
		'unreadcount' => $intUnreadCount,
		"recordcount" => $intRecordCount
	);
    
    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
