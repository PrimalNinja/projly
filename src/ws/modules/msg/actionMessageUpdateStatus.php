<?php

function actionMessageUpdateStatus($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) { 
    
    $strTableNameInternalMessage = getTableNameEntity("internalmessage", false);

	// permission check
	if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

    $arrParams = getJSONParameter($arrParameters_a, 'params');
        
    $strMessageID     = revertSecuredValue($arrParams['id'], 'id', true);
    $strUnreadStatus  = $arrParams['status'];

	// initialisations
    $strClientID = $_SESSION['server_loggedin_clientid'];
    
	dbBeginTrans($objConn_a, __FUNCTION__);
	
    $strSQL = "select jsondata returnvalue from ~TABLENAMEINTERNALMESSAGE~ where id = ~MESSAGEID~";
    $strSQL = str_replace('~TABLENAMEINTERNALMESSAGE~', ff($strTableNameInternalMessage), $strSQL);
    $strSQL = str_replace('~MESSAGEID~', ff($strMessageID), $strSQL);
    $arrJSONData = json_decode(dbReadValue($objConn_a, $strSQL, __FUNCTION__), true);
    $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g8938a0d4-8efc-41ed-a420-4af7b808b3f0', 'ISUNREAD', $strUnreadStatus);
    $strJSONData = json_encode($arrJSONData);
	// create the message
	//$strSQL ="update ~TABLENAMEINTERNALMESSAGE~ set g8938a0d4_8efc_41ed_a420_4af7b808b3f0_isunread='~UNREAD_STATUS~' where client_id = ~CLIENTID~ and id = ~MESSAGEID~";
	$strSQL ="update ~TABLENAMEINTERNALMESSAGE~ set jsondata='~JSONDATA~' where client_id = ~CLIENTID~ and id = ~MESSAGEID~";
	$strSQL = str_replace('~TABLENAMEINTERNALMESSAGE~', ff($strTableNameInternalMessage), $strSQL);
    $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
	$strSQL = str_replace('~MESSAGEID~', ff($strMessageID), $strSQL);
    $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
	//$strSQL = str_replace('~UNREAD_STATUS~', ff($strUnreadStatus), $strSQL);
	dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        
    exposeEntityData($objConn_a, 'SYSTEMFORM', 'INTERNALMESSAGE', $strMessageID, $strJSONData);

	dbEndTrans($objConn_a, __FUNCTION__);

	return createJSONResponse($strDataID_a, RESPONSE_OK, '', array());
}
