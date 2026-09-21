<?php

function actionMessageFlagSet($objConn_a, $strSecurityToken_a, $strDataID_a, $arrParameters_a) { 
    
    $strTableNameInternalMessage = getTableNameEntity("internalmessage", false);

    $arrResult = array();
    
	// permission check
	if (!hasPermission($objConn_a, 'TODO', __FUNCTION__, true)) {return false;}

    // parameters
    $strMessageID = revertSecuredValue(getJSONParameter($arrParameters_a, 'id'), 'id', true);
    
    $strIsFlagged = getJSONParameter($arrParameters_a, 'isflagged');
    
	// initialisations
    $strClientID = $_SESSION['server_loggedin_clientid'];
    
    // make sure the value of this is only Y and N
    if ($strIsFlagged == 'Y' || $strIsFlagged == 'N') 
	{ 
        dbBeginTrans($objConn_a, __FUNCTION__);
        
        $strSQL = "select jsondata returnvalue from ~TABLENAMEINTERNALMESSAGE~ where id = ~MESSAGEID~";
        $strSQL = str_replace('~TABLENAMEINTERNALMESSAGE~', ff($strTableNameInternalMessage), $strSQL);
        $strSQL = str_replace('~MESSAGEID~', ff($strMessageID), $strSQL);
        $arrJSONData = json_decode(dbReadValue($objConn_a, $strSQL, __FUNCTION__), true);
        $arrJSONData = formValueUpdateBySectionCodeFieldCode($arrJSONData, 'g8938a0d4-8efc-41ed-a420-4af7b808b3f0', 'ISFLAGGED', $strIsFlagged);
        $strJSONData = json_encode($arrJSONData);
        
        $strSQL ="update ~TABLENAMEINTERNALMESSAGE~ set jsondata='~JSONDATA~' where client_id = ~CLIENTID~ and id = ~MESSAGEID~";
        $strSQL = str_replace('~TABLENAMEINTERNALMESSAGE~', ff($strTableNameInternalMessage), $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
        $strSQL = str_replace('~MESSAGEID~', ff($strMessageID), $strSQL);
        $strSQL = str_replace('~JSONDATA~', ff($strJSONData), $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        
        exposeEntityData($objConn_a, 'SYSTEMFORM', 'INTERNALMESSAGE', $strMessageID, $strJSONData);

        /*
        $strSQL = "update ~TABLENAMEINTERNALMESSAGE~ set g8938a0d4_8efc_41ed_a420_4af7b808b3f0_isflagged='~ISFLAGGED~' where client_id = ~CLIENTID~ and id = ~MESSAGEID~";
        $strSQL = str_replace('~TABLENAMEINTERNALMESSAGE~', ff($strTableNameInternalMessage), $strSQL);
        $strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
        $strSQL = str_replace('~MESSAGEID~', ff($strMessageID), $strSQL);
        $strSQL = str_replace('~ISFLAGGED~', ff($strIsFlagged), $strSQL);
        dbExecuteSQL($objConn_a, $strSQL, __FUNCTION__);
        */

        dbEndTrans($objConn_a, __FUNCTION__);
    }
    
    return createJSONResponse($strDataID_a, RESPONSE_OK, '', $arrResult);
}
